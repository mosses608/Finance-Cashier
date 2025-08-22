<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = DB::table('admins')->insertGetId([
            'first_name' => 'Mohammed',
            'last_name' => 'Abdallah',
            'phone' => '0716087028',
            'email' => 'mozzeh608@gmail.com',
            'gender' => 'M',
        ]);

        $adminData = DB::table('admins')->where('id', $adminId)->first();

        DB::table('auth')->insert([
            'user_id' => $adminId,
            'username' => $adminData->phone,
            'password' => Hash::make('password'),
            'role_id' => 1,
            'status' => 1,
        ]);
    }
}
