<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $departments = [
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Procurement', 'code' => 'PROC'],
            ['name' => 'Inventory', 'code' => 'INV'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'IT Support', 'code' => 'IT'],
            ['name' => 'Logistics', 'code' => 'LOG'],
            ['name' => 'Sales and Marketing', 'code' => 'SALES'],
            ['name' => 'Audit & Compliance', 'code' => 'AUD'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate($dept);
        }
    }
}
