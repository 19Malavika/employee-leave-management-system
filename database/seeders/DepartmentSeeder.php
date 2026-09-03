<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Software Development', 'code' => 'DEV', 'description' => 'Engineering and Software Design Team'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Talent Management & Operations'],
            ['name' => 'Finance & Accounting', 'code' => 'FIN', 'description' => 'Financial Planning & Accounting'],
            ['name' => 'Marketing & Growth', 'code' => 'MKT', 'description' => 'Digital Marketing & Content Strategy'],
            ['name' => 'Sales & Business', 'code' => 'SLS', 'description' => 'Client Acquisition & Account Management'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
