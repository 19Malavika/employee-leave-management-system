<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $devDept = Department::where('code', 'DEV')->first();
        $hrDept = Department::where('code', 'HR')->first();
        $finDept = Department::where('code', 'FIN')->first();

        // 1. Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@company.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'phone' => '+1 800-555-0100',
                'employee_id' => 'EMP-001',
                'role' => 'admin',
                'department_id' => $hrDept?->id,
                'designation' => 'HR Operations Director',
                'joining_date' => '2022-01-15',
                'status' => 'active',
            ]
        );

        // 2. Manager User (Dev Team Manager)
        $devManager = User::updateOrCreate(
            ['email' => 'manager@company.com'],
            [
                'name' => 'Sarah Connor',
                'password' => Hash::make('password'),
                'phone' => '+1 800-555-0101',
                'employee_id' => 'EMP-002',
                'role' => 'manager',
                'department_id' => $devDept?->id,
                'designation' => 'Engineering Manager',
                'joining_date' => '2022-06-01',
                'status' => 'active',
            ]
        );

        // 3. Employee User (Dev Team)
        $employee1 = User::updateOrCreate(
            ['email' => 'employee@company.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'phone' => '+1 800-555-0102',
                'employee_id' => 'EMP-003',
                'role' => 'employee',
                'department_id' => $devDept?->id,
                'designation' => 'Senior Full-Stack Developer',
                'joining_date' => '2023-02-10',
                'manager_id' => $devManager->id,
                'status' => 'active',
            ]
        );

        // 4. Employee User 2 (Dev Team)
        User::updateOrCreate(
            ['email' => 'alex@company.com'],
            [
                'name' => 'Alex Rivera',
                'password' => Hash::make('password'),
                'phone' => '+1 800-555-0103',
                'employee_id' => 'EMP-004',
                'role' => 'employee',
                'department_id' => $devDept?->id,
                'designation' => 'Backend Engineer',
                'joining_date' => '2023-08-15',
                'manager_id' => $devManager->id,
                'status' => 'active',
            ]
        );

        // 5. Employee User 3 (Finance)
        User::updateOrCreate(
            ['email' => 'emily@company.com'],
            [
                'name' => 'Emily Watson',
                'password' => Hash::make('password'),
                'phone' => '+1 800-555-0104',
                'employee_id' => 'EMP-005',
                'role' => 'employee',
                'department_id' => $finDept?->id,
                'designation' => 'Financial Analyst',
                'joining_date' => '2024-01-05',
                'status' => 'active',
            ]
        );
    }
}
