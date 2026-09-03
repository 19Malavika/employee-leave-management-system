@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;">Manager Overview</h1>
    <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Manage your team leave applications and approvals.</p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ count($teamMembers) }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Team Members</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ count($pendingApprovals) }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Pending Approvals</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ count($onLeaveToday) }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">On Leave Today</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #f3e8ff; color: #9333ea; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ count($upcomingTeamLeaves) }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Upcoming Leaves</div>
        </div>
    </div>
</div>

<!-- Pending Approvals Action List -->
<div class="card" style="padding: 0;">
    <div class="card-header" style="padding: 24px 24px 16px 24px; margin-bottom: 0; border-bottom: none;">
        <h2 class="card-title" style="margin-bottom: 0;">Pending Leave Requests Needing Review</h2>
        <a href="{{ route('manager.leaves.index') }}" class="btn btn-primary" style="font-size: 12px; padding: 6px 12px;">Manage Approvals</a>
    </div>

    <div class="table-container" style="border: none; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Dates</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingApprovals as $leave)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">{{ $leave->user->name }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $leave->user->employee_id }}</div>
                    </td>
                    <td style="font-weight: 500;">{{ $leave->leaveType->name }}</td>
                    <td>{{ $leave->from_date->format('d M') }} - {{ $leave->to_date->format('d M, Y') }}</td>
                    <td style="font-weight: 600;">{{ $leave->total_days }} Days</td>
                    <td style="max-width: 250px;"><span style="color: var(--text-muted);">{{ Str::limit($leave->reason, 40) }}</span></td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <form method="POST" action="{{ route('manager.leaves.approve', $leave) }}">
                                @csrf
                                <button type="submit" class="btn" style="padding: 4px 12px; font-size: 12px; background: #16a34a; color: white; border-radius: 6px;">Approve</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24" style="margin: 0 auto 8px auto; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        No pending approvals! All clear
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
