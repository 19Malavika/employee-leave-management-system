@extends('layouts.app')

@section('title', 'Leave Calendar')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--secondary);">Company Leave Calendar</h1>
        <p style="font-size: 14px; color: var(--text-muted);">Visual schedule of who is on leave on any given date.</p>
    </div>
</div>

<!-- Calendar Filters -->
<div class="card" style="padding: 16px 24px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('leaves.calendar') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 4px;">DEPARTMENT</label>
            <select name="department_id" onchange="this.form.submit()" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px;">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 4px;">EMPLOYEE</label>
            <select name="employee_id" onchange="this.form.submit()" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px;">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 4px;">LEAVE TYPE</label>
            <select name="leave_type_id" onchange="this.form.submit()" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px;">
                <option value="">All Types</option>
                @foreach($leaveTypes as $type)
                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 4px;">MONTH & YEAR</label>
            <div style="display: flex; gap: 6px;">
                <select name="month" onchange="this.form.submit()" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
                <select name="year" onchange="this.form.submit()" style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px;">
                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Calendar Grid -->
@php
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDayOfWeek = date('N', strtotime("$year-$month-01")); // 1 (Mon) to 7 (Sun)
@endphp

<div class="card">
    <div style="text-align: center; margin-bottom: 16px;">
        <h2 style="font-size: 20px; font-weight: 800;">{{ date('F Y', strtotime("$year-$month-01")) }}</h2>
    </div>

    <!-- Calendar Table -->
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead>
                <tr style="background: #f8fafc; text-align: center;">
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: var(--text-muted); font-size: 12px;">MON</th>
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: var(--text-muted); font-size: 12px;">TUE</th>
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: var(--text-muted); font-size: 12px;">WED</th>
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: var(--text-muted); font-size: 12px;">THU</th>
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: var(--text-muted); font-size: 12px;">FRI</th>
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: #ef4444; font-size: 12px;">SAT</th>
                    <th style="padding: 12px; border: 1px solid var(--border-color); color: #ef4444; font-size: 12px;">SUN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentDay = 1;
                    $cellCount = 1;
                @endphp
                <tr>
                    @for($i = 1; $i < $firstDayOfWeek; $i++)
                        <td style="border: 1px solid var(--border-color); background: #f8fafc; height: 100px;"></td>
                        @php $cellCount++; @endphp
                    @endfor

                    @while($currentDay <= $daysInMonth)
                        @php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $currentDay);
                            $leavesOnDate = $calendarLeaves->filter(function($l) use ($dateStr) {
                                return $dateStr >= $l->from_date->format('Y-m-d') && $dateStr <= $l->to_date->format('Y-m-d');
                            });
                            $isToday = $dateStr === date('Y-m-d');
                        @endphp

                        <td style="border: 1px solid var(--border-color); vertical-align: top; padding: 8px; height: 100px; background: {{ $isToday ? '#eff6ff' : '#ffffff' }};">
                            <div style="font-weight: 800; font-size: 14px; margin-bottom: 4px; color: {{ $isToday ? '#2563eb' : 'var(--secondary)' }};">
                                {{ $currentDay }}
                                @if($isToday)<span style="font-size: 9px; background: #2563eb; color: white; padding: 2px 6px; border-radius: 10px; margin-left: 4px;">Today</span>@endif
                            </div>

                            @foreach($leavesOnDate as $leave)
                                <div style="font-size: 10px; padding: 4px 6px; border-radius: 6px; margin-bottom: 3px; font-weight: 700; background: {{ $leave->status === 'approved' ? '#dcfce7' : '#fef3c7' }}; color: {{ $leave->status === 'approved' ? '#166534' : '#92400e' }};" title="{{ $leave->user->name }} - {{ $leave->leaveType->name }} ({{ $leave->status }})">
                                    👤 {{ $leave->user->name }} ({{ $leave->leaveType->name }})
                                </div>
                            @endforeach
                        </td>

                        @if($cellCount % 7 == 0 && $currentDay != $daysInMonth)
                            </tr><tr>
                        @endif

                        @php
                            $currentDay++;
                            $cellCount++;
                        @endphp
                    @endwhile

                    @while(($cellCount - 1) % 7 != 0)
                        <td style="border: 1px solid var(--border-color); background: #f8fafc; height: 100px;"></td>
                        @php $cellCount++; @endphp
                    @endwhile
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
