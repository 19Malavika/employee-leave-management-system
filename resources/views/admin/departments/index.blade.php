@extends('layouts.app')

@section('title', 'Manage Departments')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Add Department Form -->
    <div class="card" style="height: fit-content; margin-bottom: 0;">
        <div class="card-header">
            <h2 class="card-title">Add Department</h2>
        </div>
        <form method="POST" action="{{ route('admin.departments.store') }}" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <div>
                <label class="form-label">Department Name *</label>
                <input type="text" name="name" required placeholder="e.g. Development" class="form-control">
            </div>
            <div>
                <label class="form-label">Department Code *</label>
                <input type="text" name="code" required placeholder="e.g. DEV" class="form-control">
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of department..." class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 8px; width: 100%;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Department
            </button>
        </form>
    </div>

    <!-- Departments Table -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <h2 class="card-title">Company Departments</h2>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Employees</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td style="font-weight: 500; font-family: monospace;">{{ $dept->code }}</td>
                        <td style="font-weight: 500;">{{ $dept->name }}</td>
                        <td><span class="badge badge-pending">{{ $dept->employees_count }}</span></td>
                        <td style="color: var(--text-muted);">{{ $dept->description ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" onsubmit="return confirm('Are you sure you want to delete department {{ $dept->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 4px 8px; font-size: 12px; background: white; border: 1px solid #fecaca; color: #ef4444;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">No departments created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 16px;">
            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection
