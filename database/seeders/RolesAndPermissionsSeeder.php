<?php

namespace Database\Seeders;

use App\Models\UserRoles;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'Inventory Manager',
            'Storekeeper',
            'Procurement Officer',
            'Accounts Officer',
            'HR Officer',
            'Sales Officer',
            'Logistics Officer',
            'Auditor',
            'Department Head',
            'Project Coordinator',
            'IT Support',
        ];

        foreach ($roles as $role) {
            UserRoles::firstOrCreate(['name' => $role]);
        }
    }
}
