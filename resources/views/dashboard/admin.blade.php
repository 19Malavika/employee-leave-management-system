@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;">Admin Dashboard Overview</h1>
    <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Real-time system stats, leave counts, and company metrics.</p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalEmployees }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Total Employees</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalDepartments }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Departments</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $pendingRequests }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Pending Requests</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $approvedRequests }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Approved Leaves</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Recent Leave Requests -->
    <div class="card" style="padding: 0;">
        <div class="card-header" style="padding: 24px 24px 16px 24px; margin-bottom: 0; border-bottom: none;">
            <h2 class="card-title" style="margin-bottom: 0;">Recent Leave Requests</h2>
            <a href="{{ route('leaves.index') }}" class="btn btn-outline" style="font-size: 12px; padding: 6px 12px;">View All</a>
        </div>
        <div class="table-container" style="border: none; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests as $leave)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">{{ $leave->user->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $leave->user->department?->name ?? 'N/A' }}</div>
                        </td>
                        <td style="font-weight: 500;">{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->from_date->format('d M') }} - {{ $leave->to_date->format('d M, Y') }}</td>
                        <td style="font-weight: 600;">{{ $leave->total_days }}</td>
                        <td><span class="badge badge-{{ $leave->status }}">{{ $leave->status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- On Leave Today -->
    <div class="card">
        <h2 class="card-title" style="margin-bottom: 20px;">Employees on Leave Today</h2>
        @forelse($onLeaveToday as $leave)
            <div style="padding: 12px 0; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 600; font-size: 14px; color: var(--text-main);">{{ $leave->user->name }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $leave->leaveType->name }} • {{ $leave->user->department?->name }}</div>
                </div>
                <span class="badge badge-approved" style="font-size: 11px;">On Leave</span>
            </div>
        @empty
            <div style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24" style="margin: 0 auto 8px auto; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                No employees on leave today
            </div>
        @endforelse
    </div>
</div>
@endsection
