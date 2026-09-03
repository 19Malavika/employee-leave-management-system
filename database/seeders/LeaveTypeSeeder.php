<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Casual Leave',
                'description' => 'For personal matters or short unexpected events.',
                'annual_allocation' => 12,
                'status' => 'active',
            ],
            [
                'name' => 'Sick Leave',
                'description' => 'For medical condition or health emergencies.',
                'annual_allocation' => 10,
                'status' => 'active',
            ],
            [
                'name' => 'Annual Leave',
                'description' => 'Earned vacation leave for rest & relaxation.',
                'annual_allocation' => 15,
                'status' => 'active',
            ],
            [
                'name' => 'Emergency Leave',
                'description' => 'Urgent family or medical emergency.',
                'annual_allocation' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Unpaid Leave',
                'description' => 'Leave without pay when paid balance is exhausted.',
                'annual_allocation' => 30,
                'status' => 'active',
            ],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
