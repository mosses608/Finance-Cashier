<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create([
            'name' => 'Admin',
            'slug' => 'Admin',
        ]);

        Role::create([
            'name' => 'HoD',
            'slug' => 'HoD',
        ]);

        Role::create([
            'name' => 'Staff',
            'slug' => 'Staff',
        ]);
    }
}
