<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'manager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $departments = Department::all();
        $managers = User::whereIn('role', ['admin', 'manager'])->get();

        return view('admin.employees.index', compact('employees', 'departments', 'managers'));
    }

    public function create()
    {
        $departments = Department::all();
        $managers = User::whereIn('role', ['admin', 'manager'])->get();
        return view('admin.employees.create', compact('departments', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:50'],
            'employee_id' => ['required', 'string', 'max:50', 'unique:users'],
            'role' => ['required', Rule::in(['admin', 'manager', 'employee'])],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $employee = User::create($validated);

        // Auto allocate leave types
        $leaveTypes = LeaveType::where('status', 'active')->get();
        $year = (int) date('Y');
        foreach ($leaveTypes as $type) {
            LeaveAllocation::updateOrCreate(
                [
                    'user_id' => $employee->id,
                    'leave_type_id' => $type->id,
                    'year' => $year,
                ],
                [
                    'allocated_days' => $type->annual_allocation,
                    'used_days' => 0,
                ]
            );
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully and leave balance allocated!');
    }

    public function edit(User $employee)
    {
        $departments = Department::all();
        $managers = User::whereIn('role', ['admin', 'manager'])->where('id', '!=', $employee->id)->get();
        return view('admin.employees.edit', compact('employee', 'departments', 'managers'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($employee->id)],
            'role' => ['required', Rule::in(['admin', 'manager', 'employee'])],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return back()->with('success', 'Employee deleted successfully.');
    }
}
