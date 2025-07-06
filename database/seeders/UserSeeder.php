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
            'user_id' => 100,
            'username' => '255694235858',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'login_attempts' => 0,
            'blocked_at' => null,
            'is_online' => false,
        ]);
    }
}
