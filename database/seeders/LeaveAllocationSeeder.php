<?php

namespace Database\Seeders;

use App\Models\LeaveAllocation;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveAllocationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();
        $year = (int) date('Y');

        foreach ($users as $user) {
            foreach ($leaveTypes as $type) {
                LeaveAllocation::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'leave_type_id' => $type->id,
                        'year' => $year,
                    ],
                    [
                        'allocated_days' => $type->annual_allocation,
                        'used_days' => 0,
                    ]
                );
            }
        }
    }
}
