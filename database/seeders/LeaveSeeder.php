<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        $employee = User::where('email', 'employee@company.com')->first();
        $alex = User::where('email', 'alex@company.com')->first();
        $manager = User::where('email', 'manager@company.com')->first();
        $casual = LeaveType::where('name', 'Casual Leave')->first();
        $sick = LeaveType::where('name', 'Sick Leave')->first();
        $annual = LeaveType::where('name', 'Annual Leave')->first();

        if ($employee && $casual && $manager) {
            // 1. Approved Leave for John Doe
            Leave::create([
                'user_id' => $employee->id,
                'leave_type_id' => $casual->id,
                'from_date' => '2026-09-10',
                'to_date' => '2026-09-12',
                'total_days' => 3,
                'reason' => 'Attending family wedding ceremony.',
                'status' => 'approved',
                'approved_by' => $manager->id,
            ]);

            // Update allocation
            $alloc = LeaveAllocation::where('user_id', $employee->id)
                ->where('leave_type_id', $casual->id)
                ->where('year', 2026)
                ->first();
            if ($alloc) {
                $alloc->increment('used_days', 3);
            }
        }

        if ($alex && $sick && $manager) {
            // 2. Approved Sick Leave for Alex
            Leave::create([
                'user_id' => $alex->id,
                'leave_type_id' => $sick->id,
                'from_date' => '2026-09-15',
                'to_date' => '2026-09-16',
                'total_days' => 2,
                'reason' => 'Severe viral fever and doctor recommended rest.',
                'status' => 'approved',
                'approved_by' => $manager->id,
            ]);

            $alloc = LeaveAllocation::where('user_id', $alex->id)
                ->where('leave_type_id', $sick->id)
                ->where('year', 2026)
                ->first();
            if ($alloc) {
                $alloc->increment('used_days', 2);
            }
        }

        if ($employee && $annual) {
            // 3. Pending Leave for John Doe
            Leave::create([
                'user_id' => $employee->id,
                'leave_type_id' => $annual->id,
                'from_date' => '2026-09-22',
                'to_date' => '2026-09-25',
                'total_days' => 4,
                'reason' => 'Planned annual vacation with family.',
                'status' => 'pending',
            ]);
        }
    }
}
