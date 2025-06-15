<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Category::create([
        //     'name' => 'Solid',
        //     'slug' => 'Solid',
        // ]);

        // Category::create([
        //     'name' => 'Liquid',
        //     'slug' => 'Liquid',
        // ]);

        // Category::create([
        //     'name' => 'Gas',
        //     'slug' => 'Gas',
        // ]);

        Category::create([
            'name' => 'Service',
            'slug' => 'Service',
        ]);
    }
}
