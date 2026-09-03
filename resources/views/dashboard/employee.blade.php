@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;">Welcome Back, {{ auth()->user()->name }}!</h1>
        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">View your leave balances, history, and apply for leaves.</p>
    </div>
    <a href="{{ route('leaves.index') }}#apply-form" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Apply for Leave
    </a>
</div>

<!-- Leave Balances Progress Cards -->
<h2 class="card-title" style="margin-bottom: 16px;">Your Leave Allocations ({{ date('Y') }})</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 32px;">
    @foreach($allocations as $alloc)
    @php
        $remaining = max(0, $alloc->allocated_days - $alloc->used_days);
        $percent = $alloc->allocated_days > 0 ? round(($alloc->used_days / $alloc->allocated_days) * 100) : 0;
    @endphp
    <div class="card" style="margin-bottom: 0; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div style="font-weight: 600; font-size: 15px; color: var(--text-main);">{{ $alloc->leaveType->name }}</div>
            <span class="badge badge-approved" style="font-size: 11px;">{{ $remaining }} Days Left</span>
        </div>
        <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">
            Allocated: <strong style="color: var(--text-main);">{{ $alloc->allocated_days }}</strong> • Used: <strong style="color: var(--text-main);">{{ $alloc->used_days }}</strong>
        </div>
        <!-- Progress Bar -->
        <div style="height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
            <div style="width: {{ $percent }}%; height: 100%; background: {{ $percent > 80 ? '#ef4444' : '#2563eb' }}; border-radius: 10px;"></div>
        </div>
    </div>
    @endforeach
</div>

<div class="card" style="padding: 0;">
    <div class="card-header" style="padding: 24px 24px 16px 24px; margin-bottom: 0; border-bottom: none;">
        <h2 class="card-title">My Recent Leave Requests</h2>
    </div>
    <div class="table-container" style="border: none; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myLeaves as $leave)
                <tr>
                    <td style="font-weight: 500;">{{ $leave->leaveType->name }}</td>
                    <td>{{ $leave->from_date->format('d M, Y') }}</td>
                    <td>{{ $leave->to_date->format('d M, Y') }}</td>
                    <td style="font-weight: 600;">{{ $leave->total_days }} Day(s)</td>
                    <td style="max-width: 250px;">
                        <span style="color: var(--text-muted);">{{ Str::limit($leave->reason, 40) }}</span>
                        @if($leave->rejection_reason)
                            <div style="font-size: 12px; color: #ef4444; margin-top: 4px; font-weight: 500;">Reason: {{ $leave->rejection_reason }}</div>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $leave->status }}">{{ $leave->status }}</span></td>
                    <td>
                        @if($leave->status === 'pending')
                        <form method="POST" action="{{ route('leaves.cancel', $leave) }}" onsubmit="return confirm('Cancel this leave request?');">
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
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">You haven't submitted any leave requests yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
