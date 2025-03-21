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
        Department::create([
            'name' => 'IT',
        ]);

        Department::create([
            'name' => 'Finance',
        ]);

        Department::create([
            'name' => 'Storage',
        ]);

        Department::create([
            'name' => 'Service',
        ]);
    }
}
