@extends('layouts.app')

@section('title', 'Manage Employees')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;">Employee Directory</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Manage company employees, departments, roles, and profiles.</p>
    </div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Employee
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.employees.index') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 16px; align-items: center;">
        <div style="position: relative;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16" style="position: absolute; left: 12px; top: 10px; color: #94a3b8;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or ID..." class="form-control" style="padding-left: 36px;">
        </div>

        <select name="department_id" class="form-control" onchange="this.form.submit()">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>

        <select name="role" class="form-control" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
        </select>

        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button type="submit" class="btn btn-outline" style="height: 100%;">Filter</button>
    </form>
</div>

<!-- Employees Table -->
<div class="card" style="padding: 0;">
    <div class="table-container" style="border: none;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Emp ID</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Designation</th>
                    <th>Manager</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td style="font-weight: 500; font-family: monospace;">{{ $emp->employee_id ?? '-' }}</td>
                    <td>
                        <div style="font-weight: 500; color: var(--text-main);">{{ $emp->name }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $emp->email }}</div>
                    </td>
                    <td>{{ $emp->department?->name ?? '-' }}</td>
                    <td><span class="role-badge role-{{ $emp->role }}">{{ $emp->role }}</span></td>
                    <td>{{ $emp->designation ?? '-' }}</td>
                    <td>{{ $emp->manager?->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $emp->status === 'active' ? 'badge-approved' : 'badge-rejected' }}">
                            {{ $emp->status }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.employees.edit', $emp) }}" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}" onsubmit="return confirm('Are you sure you want to delete employee {{ $emp->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 4px 8px; font-size: 12px; background: white; border: 1px solid #fecaca; color: #ef4444;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">No employees found matching your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light);">
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
