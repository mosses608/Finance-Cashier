<?php

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        InvoiceStatus::create([
            'name' => 'Pending',
        ]);

        InvoiceStatus::create([
            'name' => 'Cancelled',
        ]);

        InvoiceStatus::create([
            'name' => 'Paid',
        ]);
    }
}
