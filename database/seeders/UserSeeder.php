<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Aika Mosses',
            'role_id' => 1,
            'email' => 'mohammeddungumalo@gmail.com',
            'phone' => '255694235858',
            'department_id' => 1,
            'username' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}
