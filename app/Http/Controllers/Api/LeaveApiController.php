<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\LeaveStatusNotification;
use App\Notifications\NewLeaveApplicationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveApiController extends Controller
{
    // GET /api/leave-types
    public function leaveTypes()
    {
        $types = LeaveType::where('status', 'active')->get();
        return response()->json(['status' => 'success', 'data' => $types], 200);
    }

    // GET /api/leave-balance
    public function leaveBalance(Request $request)
    {
        $user = $request->user();
        $allocations = LeaveAllocation::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', date('Y'))
            ->get()
            ->map(function ($alloc) {
                return [
                    'leave_type' => $alloc->leaveType->name,
                    'allocated_days' => $alloc->allocated_days,
                    'used_days' => $alloc->used_days,
                    'remaining_days' => max(0, $alloc->allocated_days - $alloc->used_days),
                ];
            });

        return response()->json(['status' => 'success', 'data' => $allocations], 200);
    }

    // GET /api/leaves
    public function index(Request $request)
    {
        $user = $request->user();
        $leaves = Leave::with(['leaveType', 'approver'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $leaves], 200);
    }

    // GET /api/leaves/{id}
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $leave = Leave::with(['leaveType', 'approver', 'user'])->find($id);

        if (!$leave) {
            return response()->json(['status' => 'error', 'message' => 'Leave request not found.'], 404);
        }

        if ($leave->user_id !== $user->id && !$user->isManager() && !$user->isAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access.'], 403);
        }

        return response()->json(['status' => 'success', 'data' => $leave], 200);
    }

    // POST /api/leaves
    public function store(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->format('Y-m-d');

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date|after_or_equal:' . $today,
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string|min:5',
        ]);

        $fromDate = Carbon::parse($validated['from_date']);
        $toDate = Carbon::parse($validated['to_date']);
        $totalDays = $fromDate->diffInDays($toDate) + 1;

        // Rule 1: Balance check
        $allocation = LeaveAllocation::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', (int) $fromDate->format('Y'))
            ->first();

        $remaining = $allocation ? ($allocation->allocated_days - $allocation->used_days) : 0;
        if ($totalDays > $remaining) {
            return response()->json([
                'status' => 'error',
                'message' => "Insufficient leave balance. Requested {$totalDays} days, but remaining balance is {$remaining} days.",
            ], 422);
        }

        // Rule 2 & 3: Overlapping check
        $overlap = Leave::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('from_date', [$validated['from_date'], $validated['to_date']])
                  ->orWhereBetween('to_date', [$validated['from_date'], $validated['to_date']]);
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'status' => 'error',
                'message' => 'Leave request overlaps with an existing pending or approved leave.',
            ], 422);
        }

        $leave = Leave::create([
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        if ($user->manager) {
            $user->manager->notify(new NewLeaveApplicationNotification($leave));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Leave application submitted successfully.',
            'data' => $leave,
        ], 201);
    }

    // POST /api/leaves/{id}/cancel
    public function cancel(Request $request, $id)
    {
        $user = $request->user();
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json(['status' => 'error', 'message' => 'Leave request not found.'], 404);
        }

        if ($leave->user_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized action.'], 403);
        }

        if ($leave->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Only pending leave requests can be cancelled.'], 422);
        }

        $leave->update(['status' => 'cancelled']);

        return response()->json(['status' => 'success', 'message' => 'Leave request cancelled.'], 200);
    }

    // GET /api/leave-requests (Manager API)
    public function managerRequests(Request $request)
    {
        $user = $request->user();
        if (!$user->isManager() && !$user->isAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized manager action.'], 403);
        }

        $subordinates = User::where('manager_id', $user->id)->pluck('id')->toArray();
        $requests = Leave::with(['user.department', 'leaveType'])
            ->whereIn('user_id', $subordinates)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $requests], 200);
    }

    // POST /api/leave-requests/{id}/approve
    public function approve(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->isManager() && !$user->isAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized manager action.'], 403);
        }

        $leave = Leave::find($id);
        if (!$leave) {
            return response()->json(['status' => 'error', 'message' => 'Leave request not found.'], 404);
        }

        if ($leave->user_id === $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Self-approval is not allowed.'], 422);
        }

        // Rule 6: Balance deduction
        $year = (int) Carbon::parse($leave->from_date)->format('Y');
        $allocation = LeaveAllocation::where('user_id', $leave->user_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $year)
            ->first();

        if ($allocation) {
            $allocation->increment('used_days', $leave->total_days);
        }

        $leave->update(['status' => 'approved', 'approved_by' => $user->id]);

        $leave->user->notify(new LeaveStatusNotification($leave, 'Approved'));

        return response()->json(['status' => 'success', 'message' => 'Leave approved and balance deducted.'], 200);
    }

    // POST /api/leave-requests/{id}/reject
    public function reject(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->isManager() && !$user->isAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized manager action.'], 403);
        }

        $leave = Leave::find($id);
        if (!$leave) {
            return response()->json(['status' => 'error', 'message' => 'Leave request not found.'], 404);
        }

        $request->validate(['rejection_reason' => 'required|string']);

        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => $user->id,
        ]);

        $leave->user->notify(new LeaveStatusNotification($leave, 'Rejected'));

        return response()->json(['status' => 'success', 'message' => 'Leave rejected.'], 200);
    }
}
