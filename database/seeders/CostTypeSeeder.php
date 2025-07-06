<?php

namespace Database\Seeders;

use App\Models\CostType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $costTypes = [
            'Human Resources',
            'Office & Operations',
            'Travel & Logistics',
            'Equipment & Assets',
            'Project Implementation',
            'Other & Emergency Costs'
        ];

        foreach ($costTypes as $cost) {
            CostType::create([
                'name' => $cost,
            ]);
        }
    }
}
