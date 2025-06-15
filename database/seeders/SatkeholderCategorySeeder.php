<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StakeholderCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SatkeholderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = ['Customer','Supplier','Regulator'];

        foreach($categories as $category){
            StakeholderCategory::create([
                'name' => $category,
            ]);
        }
    }
}
