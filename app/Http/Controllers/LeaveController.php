<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\LeaveStatusUpdatedNotification;
use App\Notifications\NewLeaveRequestNotification;
use App\Notifications\LeaveCancelledNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    // Employee Leave List & Apply Form
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Leave::with(['leaveType', 'user.department', 'approver']);

        if ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isManager()) {
            $subordinateIds = User::where('manager_id', $user->id)->pluck('id')->toArray();
            $subordinateIds[] = $user->id;
            $query->whereIn('user_id', $subordinateIds);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $leaveTypes = LeaveType::where('status', 'active')->get();
        $allocations = LeaveAllocation::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', date('Y'))
            ->get();

        return view('leaves.index', compact('leaves', 'leaveTypes', 'allocations'));
    }

    // Apply for Leave Action (Rule Validation)
    public function store(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');

        $validated = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'from_date' => ['required', 'date', 'after_or_equal:' . $today], // Rule 9: Past Dates
            'to_date' => ['required', 'date', 'after_or_equal:from_date'], // Rule 8: Date Validation
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,png,doc,docx', 'max:5120'], // Max 5MB
        ]);

        $fromDate = Carbon::parse($validated['from_date']);
        $toDate = Carbon::parse($validated['to_date']);
        $totalDays = $fromDate->diffInDays($toDate) + 1; // Auto calculate leave days

        // Rule 1 — Leave Balance Check
        $allocation = LeaveAllocation::where('user_id', $user->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', (int) $fromDate->format('Y'))
            ->first();

        $remainingDays = $allocation ? ($allocation->allocated_days - $allocation->used_days) : 0;

        if ($totalDays > $remainingDays) {
            return back()->withInput()->withErrors([
                'leave_type_id' => "Insufficient leave balance! You requested {$totalDays} day(s), but only {$remainingDays} day(s) remain in your allocation.",
            ]);
        }

        // Rule 2 — Overlapping Approved Leave
        $overlappingApproved = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function ($q) use ($validated) {
                $q->whereBetween('from_date', [$validated['from_date'], $validated['to_date']])
                  ->orWhereBetween('to_date', [$validated['from_date'], $validated['to_date']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('from_date', '<=', $validated['from_date'])
                         ->where('to_date', '>=', $validated['to_date']);
                  });
            })
            ->exists();

        if ($overlappingApproved) {
            return back()->withInput()->withErrors([
                'from_date' => '❌ You already have an APPROVED leave request overlapping with these dates.',
            ]);
        }

        // Rule 3 — Overlapping Pending Leave
        $overlappingPending = Leave::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function ($q) use ($validated) {
                $q->whereBetween('from_date', [$validated['from_date'], $validated['to_date']])
                  ->orWhereBetween('to_date', [$validated['from_date'], $validated['to_date']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('from_date', '<=', $validated['from_date'])
                         ->where('to_date', '>=', $validated['to_date']);
                  });
            })
            ->exists();

        if ($overlappingPending) {
            return back()->withInput()->withErrors([
                'from_date' => '❌ You already have a PENDING leave request overlapping with these dates.',
            ]);
        }

        // Supporting Document Upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('leave_documents', 'public');
        }

        $leave = Leave::create([
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        // Notify Manager / Admin
        if ($user->manager) {
            $user->manager->notify(new NewLeaveRequestNotification($leave));
        } else {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewLeaveRequestNotification($leave));
            }
        }

        return redirect()->route('leaves.index')->with('success', "Leave application submitted successfully for {$totalDays} day(s)!");
    }

    // Cancel Leave (Rule 4 — Employee Cancellation of Pending Requests)
    public function cancel(Leave $leave)
    {
        $user = auth()->user();

        if ($leave->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized action! You can only cancel your own leave requests.');
        }

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only PENDING leave requests can be cancelled.');
        }

        $leave->update(['status' => 'cancelled']);

        // Notify Manager / Admin about cancellation
        if ($user->manager) {
            $user->manager->notify(new LeaveCancelledNotification($leave));
        } else {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new LeaveCancelledNotification($leave));
            }
        }

        return back()->with('success', 'Leave request cancelled successfully.');
    }

    // Manager Pending Leave Requests List
    public function pendingApprovals(Request $request)
    {
        $user = auth()->user();
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $query = Leave::with(['user.department', 'leaveType'])->where('status', 'pending');

        if ($user->isManager()) {
            $subordinateIds = User::where('manager_id', $user->id)->pluck('id')->toArray();
            $query->whereIn('user_id', $subordinateIds);
        }

        $pendingLeaves = $query->orderBy('created_at', 'asc')->paginate(10);

        return view('leaves.approvals', compact('pendingLeaves'));
    }

    // Approve Leave (Rule 5 & Rule 6 — Manager Approval & Balance Deduction)
    public function approve(Request $request, Leave $leave)
    {
        $user = auth()->user();

        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Rule 5 — Cannot approve own leave
        if ($leave->user_id === $user->id) {
            return back()->with('error', '❌ Rule Violation: You cannot approve your own leave request.');
        }

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Leave request is no longer pending.');
        }

        // Rule 6 — Balance Deduction
        $year = (int) Carbon::parse($leave->from_date)->format('Y');
        $allocation = LeaveAllocation::where('user_id', $leave->user_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $year)
            ->first();

        if ($allocation) {
            $allocation->increment('used_days', $leave->total_days);
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => $user->id,
        ]);

        // Notify Employee
        $leave->user->notify(new LeaveStatusUpdatedNotification($leave));

        return back()->with('success', 'Leave request APPROVED successfully and employee balance updated!');
    }

    // Reject Leave (Rule 7 — Rejection does NOT reduce balance)
    public function reject(Request $request, Leave $leave)
    {
        $user = auth()->user();

        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        if ($leave->user_id === $user->id) {
            return back()->with('error', '❌ Rule Violation: You cannot perform approval actions on your own leave request.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => $user->id,
        ]);

        // Rule 7: Balance remains untouched

        // Notify Employee
        $leave->user->notify(new LeaveStatusUpdatedNotification($leave));

        return back()->with('success', 'Leave request REJECTED successfully.');
    }

    // Visual Leave Calendar View
    public function calendar(Request $request)
    {
        $user = auth()->user();
        $departments = Department::all();
        $leaveTypes = LeaveType::all();
        $employees = User::where('status', 'active')->get();

        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        $query = Leave::with(['user.department', 'leaveType'])
            ->whereIn('status', ['approved', 'pending']);

        if ($request->filled('department_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $calendarLeaves = $query->get();

        return view('leaves.calendar', compact('departments', 'leaveTypes', 'employees', 'calendarLeaves', 'month', 'year'));
    }
}
