@extends('layouts.app')

@section('title', 'Manage Leave Types')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Add Leave Type Form -->
    <div class="card" style="height: fit-content; margin-bottom: 0;">
        <div class="card-header">
            <h2 class="card-title">Add Leave Type</h2>
        </div>
        <form method="POST" action="{{ route('admin.leave-types.store') }}" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <div>
                <label class="form-label">Leave Type Name *</label>
                <input type="text" name="name" required placeholder="e.g. Casual Leave" class="form-control">
            </div>
            <div>
                <label class="form-label">Annual Allocation (Days) *</label>
                <input type="number" name="annual_allocation" value="12" min="1" max="365" required class="form-control">
            </div>
            <div>
                <label class="form-label">Status *</label>
                <select name="status" required class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of leave policy..." class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 8px; width: 100%;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Leave Type
            </button>
        </form>
    </div>

    <!-- Leave Types Table -->
    <div class="card" style="margin-bottom: 0; padding: 0;">
        <div class="card-header" style="padding: 24px 24px 16px 24px; margin-bottom: 0; border-bottom: none;">
            <h2 class="card-title">Configured Leave Types</h2>
        </div>
        <div class="table-container" style="border: none; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Annual Days</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $type)
                    <tr>
                        <td style="font-weight: 600; color: var(--text-main);">{{ $type->name }}</td>
                        <td style="font-weight: 700; color: var(--accent);">{{ $type->annual_allocation }} Days / Year</td>
                        <td>
                            <span class="badge {{ $type->status === 'active' ? 'badge-approved' : 'badge-rejected' }}">
                                {{ $type->status }}
                            </span>
                        </td>
                        <td style="color: var(--text-muted);">{{ $type->description ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.leave-types.destroy', $type) }}" onsubmit="return confirm('Are you sure you want to delete leave type {{ $type->name }}?');">
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
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">No leave types created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 16px 24px;">
            {{ $leaveTypes->links() }}
        </div>
    </div>
</div>
@endsection
