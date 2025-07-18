<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $this->call(UserRoleSeeder::class);
        // $this->call(DepartmentSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(InvoiceStatusSeeder::class);
        // $this->call(SatkeholderCategorySeeder::class);
        // $this->call(IdentificationSourseSeeder::class);
        // $this->call(CostTypeSeeder::class);
        // $this->call(ExpensesTypeSeeder::class);
        // $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CitySeeder::class);
    }
}
