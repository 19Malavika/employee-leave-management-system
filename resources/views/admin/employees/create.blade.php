@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; font-weight: 800; color: var(--secondary);">Create Employee Profile</h1>
        <p style="font-size: 14px; color: var(--text-muted);">Fill in details to onboard a new employee and allocate leave balances.</p>
    </div>

    <div class="card">
        @if($errors->any())
            <div class="alert alert-error" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.employees.store') }}" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="john@company.com" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Employee ID *</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', 'EMP-'.rand(100, 999)) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Password *</label>
                    <input type="password" name="password" required placeholder="Minimum 8 characters" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Role *</label>
                    <select name="role" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Department</label>
                    <select name="department_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Manager</label>
                    <select name="manager_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                        <option value="">-- Select Reporting Manager --</option>
                        @foreach($managers as $mgr)
                            <option value="{{ $mgr->id }}" {{ old('manager_id') == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation') }}" placeholder="e.g. Software Engineer" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Joining Date</label>
                    <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Status *</label>
                    <select name="status" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 14px;">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 12px;">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save & Create Employee</button>
            </div>
        </form>
    </div>
</div>
@endsection
