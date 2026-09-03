<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = date('Y-m-d');

        if ($user->isAdmin()) {
            $totalEmployees = User::where('role', '!=', 'admin')->count();
            $totalDepartments = Department::count();
            $totalLeaveRequests = Leave::count();
            $pendingRequests = Leave::where('status', 'pending')->count();
            $approvedRequests = Leave::where('status', 'approved')->count();
            $rejectedRequests = Leave::where('status', 'rejected')->count();

            $onLeaveToday = Leave::with(['user.department', 'leaveType'])
                ->where('status', 'approved')
                ->where('from_date', '<=', $today)
                ->where('to_date', '>=', $today)
                ->get();

            $upcomingLeaves = Leave::with(['user.department', 'leaveType'])
                ->where('status', 'approved')
                ->where('from_date', '>', $today)
                ->orderBy('from_date', 'asc')
                ->limit(5)
                ->get();

            $recentRequests = Leave::with(['user.department', 'leaveType'])
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            return view('dashboard.admin', compact(
                'totalEmployees', 'totalDepartments', 'totalLeaveRequests',
                'pendingRequests', 'approvedRequests', 'rejectedRequests',
                'onLeaveToday', 'upcomingLeaves', 'recentRequests'
            ));
        }

        if ($user->isManager()) {
            $teamMembers = User::where('manager_id', $user->id)->get();
            $teamUserIds = $teamMembers->pluck('id')->toArray();

            $pendingApprovals = Leave::with(['user', 'leaveType'])
                ->whereIn('user_id', $teamUserIds)
                ->where('status', 'pending')
                ->get();

            $onLeaveToday = Leave::with(['user', 'leaveType'])
                ->whereIn('user_id', $teamUserIds)
                ->where('status', 'approved')
                ->where('from_date', '<=', $today)
                ->where('to_date', '>=', $today)
                ->get();

            $upcomingTeamLeaves = Leave::with(['user', 'leaveType'])
                ->whereIn('user_id', $teamUserIds)
                ->where('status', 'approved')
                ->where('from_date', '>', $today)
                ->orderBy('from_date', 'asc')
                ->get();

            $approvedCount = Leave::whereIn('user_id', $teamUserIds)->where('status', 'approved')->count();
            $rejectedCount = Leave::whereIn('user_id', $teamUserIds)->where('status', 'rejected')->count();

            return view('dashboard.manager', compact(
                'teamMembers', 'pendingApprovals', 'onLeaveToday',
                'upcomingTeamLeaves', 'approvedCount', 'rejectedCount'
            ));
        }

        // Employee Dashboard
        $allocations = LeaveAllocation::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', date('Y'))
            ->get();

        $myLeaves = Leave::with('leaveType')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = $myLeaves->where('status', 'pending')->count();
        $approvedCount = $myLeaves->where('status', 'approved')->count();
        $rejectedCount = $myLeaves->where('status', 'rejected')->count();

        $upcomingLeaves = Leave::with('leaveType')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('from_date', '>=', $today)
            ->orderBy('from_date', 'asc')
            ->get();

        return view('dashboard.employee', compact(
            'allocations', 'myLeaves', 'pendingCount',
            'approvedCount', 'rejectedCount', 'upcomingLeaves'
        ));
    }
}
