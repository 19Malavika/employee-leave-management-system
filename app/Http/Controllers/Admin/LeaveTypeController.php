<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::withCount('leaves')->paginate(10);
        return view('admin.leave_types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:leave_types'],
            'description' => ['nullable', 'string'],
            'annual_allocation' => ['required', 'integer', 'min:1', 'max:365'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $type = LeaveType::create($validated);

        // Sync with existing users
        $users = User::all();
        $year = (int) date('Y');
        foreach ($users as $user) {
            LeaveAllocation::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type_id' => $type->id,
                    'year' => $year,
                ],
                [
                    'allocated_days' => $type->annual_allocation,
                    'used_days' => 0,
                ]
            );
        }

        return back()->with('success', 'Leave Type created & allocated to users!');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('leave_types')->ignore($leaveType->id)],
            'description' => ['nullable', 'string'],
            'annual_allocation' => ['required', 'integer', 'min:1', 'max:365'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $leaveType->update($validated);

        return back()->with('success', 'Leave Type updated successfully!');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return back()->with('success', 'Leave Type deleted successfully.');
    }
}
