<?php

namespace Database\Seeders;

use App\Models\ExpensesType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpensesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $types = ['Purchase', 'Transport', 'Repair', 'Fuel', 'Supplies'];
        foreach ($types as $type) {
            ExpensesType::create(['name' => $type]);
        }
    }
}
