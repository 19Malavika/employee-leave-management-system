@extends('layouts.app')

@section('title', 'Leave Applications & History')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    
    <!-- Leave Application Form -->
    <div class="card" id="apply-form" style="height: fit-content; margin-bottom: 0;">
        <div class="card-header">
            <h2 class="card-title">Apply for Leave</h2>
        </div>

        @if($errors->any())
            <div class="toast toast-error" style="position: static; width: 100%; margin-bottom: 20px; animation: none;">
                <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div style="font-size: 13px; font-weight: 500; color: #0f172a; margin-bottom: 2px;">Validation Error</div>
                    <ul style="font-size: 12px; color: #64748b; padding-left: 16px; margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf

            <!-- Leave Type -->
            <div>
                <label class="form-label">Leave Type *</label>
                <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                    <option value="">-- Select Leave Type --</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} (Max: {{ $type->annual_allocation }} days)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label class="form-label">From Date *</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ old('from_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required onchange="calculateDays()">
                </div>
                <div>
                    <label class="form-label">To Date *</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ old('to_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required onchange="calculateDays()">
                </div>
            </div>

            <!-- Auto Day Calculation Banner -->
            <div id="day-counter" style="background: #f1f5f9; color: #475569; padding: 12px; border-radius: 6px; font-size: 13px; font-weight: 500; text-align: center; border: 1px solid #e2e8f0;">
                Total Duration: <span id="total-days-span" style="font-weight: 600; color: #0f172a;">1</span> Day(s)
            </div>

            <!-- Reason -->
            <div>
                <label class="form-label">Reason for Leave *</label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Please state the reason for your leave application...">{{ old('reason') }}</textarea>
            </div>

            <!-- Document Upload -->
            <div>
                <label class="form-label">Supporting Document (Optional)</label>
                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="form-control" style="padding: 6px 12px;">
                <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Allowed: PDF, JPG, PNG, DOC, DOCX (Max: 5MB)</span>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 8px; width: 100%;">
                Submit Leave Request
            </button>
        </form>
    </div>

    <!-- Leave History Table -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
            <h2 class="card-title">Leave History & Requests</h2>
            
            <!-- Filters -->
            <form method="GET" action="{{ route('leaves.index') }}">
                <select name="status" class="form-control" style="width: auto; padding: 6px 10px;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>

        <div class="table-container" style="margin-top: 16px;">
            <table class="data-table">
                <thead>
                    <tr>
                        @if(!auth()->user()->isEmployee())
                        <th>Employee</th>
                        @endif
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Document</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        @if(!auth()->user()->isEmployee())
                        <td>
                            <div style="font-weight: 500; color: var(--text-main);">{{ $leave->user->name }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $leave->user->department?->name }}</div>
                        </td>
                        @endif
                        <td style="font-weight: 500;">{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->from_date->format('d M') }} - {{ $leave->to_date->format('d M, Y') }}</td>
                        <td style="font-weight: 600;">{{ $leave->total_days }}</td>
                        <td><span class="badge badge-{{ $leave->status }}">{{ $leave->status }}</span></td>
                        <td>
                            @if($leave->document_path)
                            <a href="{{ asset('storage/' . $leave->document_path) }}" target="_blank" style="color: var(--accent); font-size: 12px; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                View Doc
                            </a>
                            @else
                            <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($leave->user_id === auth()->id() && $leave->status === 'pending')
                            <form method="POST" action="{{ route('leaves.cancel', $leave) }}" onsubmit="return confirm('Are you sure you want to cancel this leave request?');">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: underline;">Cancel</button>
                            </form>
                            @else
                            <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">No leave records found matching your filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $leaves->links() }}
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function calculateDays() {
        const from = document.getElementById('from_date').value;
        const to = document.getElementById('to_date').value;

        if (from && to) {
            const d1 = new Date(from);
            const d2 = new Date(to);
            if (d2 >= d1) {
                const timeDiff = d2.getTime() - d1.getTime();
                const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                document.getElementById('total-days-span').innerText = days;
            } else {
                document.getElementById('total-days-span').innerText = 'Invalid Range!';
            }
        }
    }
    document.addEventListener('DOMContentLoaded', calculateDays);
</script>
@endsection
