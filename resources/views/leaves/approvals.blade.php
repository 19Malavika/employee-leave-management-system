@extends('layouts.app')

@section('title', 'Pending Leave Approvals')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 800; color: var(--secondary);">Manager Approval Workflow</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Review team leave applications, check reasons, and approve or reject.</p>
</div>

<div class="card">
    <h2 class="card-title">Pending Leave Requests</h2>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Employee Details</th>
                    <th>Leave Type</th>
                    <th>Dates & Duration</th>
                    <th>Reason</th>
                    <th>Document</th>
                    <th>Approve / Reject Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingLeaves as $leave)
                <tr>
                    <td>
                        <div style="font-weight: 700; font-size: 15px;">{{ $leave->user->name }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">ID: {{ $leave->user->employee_id }} • {{ $leave->user->department?->name }}</div>
                    </td>
                    <td><span class="badge badge-pending">{{ $leave->leaveType->name }}</span></td>
                    <td>
                        <div style="font-weight: 700;">{{ $leave->from_date->format('d M') }} - {{ $leave->to_date->format('d M, Y') }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">Total: <strong>{{ $leave->total_days }} Day(s)</strong></div>
                    </td>
                    <td style="max-width: 260px;">{{ $leave->reason }}</td>
                    <td>
                        @if($leave->document_path)
                            <a href="{{ asset('storage/' . $leave->document_path) }}" target="_blank" class="btn btn-outline" style="font-size: 11px; padding: 4px 8px;">📄 View File</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <!-- Approve Action -->
                            <form method="POST" action="{{ route('manager.leaves.approve', $leave) }}" onsubmit="return confirm('Approve leave request for {{ $leave->user->name }}?');">
                                @csrf
                                <button type="submit" class="btn btn-success" style="font-size: 12px; padding: 6px 12px;">✅ Approve</button>
                            </form>

                            <!-- Reject Action Modal / Form -->
                            <button type="button" onclick="showRejectModal({{ $leave->id }}, '{{ addslashes($leave->user->name) }}')" class="btn btn-danger" style="font-size: 12px; padding: 6px 12px;">❌ Reject</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px 0;">
                        No pending leave requests requiring review.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $pendingLeaves->links() }}
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="reject-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 480px; margin: 0 20px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;" id="modal-title">Reject Leave Request</h3>
        <form id="reject-form" method="POST" action="">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px;">Reason for Rejection *</label>
                <textarea name="rejection_reason" rows="4" required placeholder="State why this leave request is being rejected..." style="width: 100%; padding: 10px; border-radius: 10px; border: 1px solid var(--border-color); font-size: 14px; font-family: inherit;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showRejectModal(leaveId, userName) {
        document.getElementById('modal-title').innerText = 'Reject Leave Request for ' + userName;
        document.getElementById('reject-form').action = '/manager/leaves/' + leaveId + '/reject';
        document.getElementById('reject-modal').style.display = 'flex';
    }
    function closeRejectModal() {
        document.getElementById('reject-modal').style.display = 'none';
    }
</script>
@endsection
