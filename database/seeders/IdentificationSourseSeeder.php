<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\IdentificationSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IdentificationSourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $identificationTypes = [
            ['slug' => 'nin', 'name' => 'National ID (NIDA)'],
            ['slug' => 'voter_id', 'name' => 'Voter ID'],
            ['slug' => 'passport', 'name' => 'Passport'],
            ['slug' => 'driver_license', 'name' => 'Driver’s License'],
            ['slug' => 'birth_certificate', 'name' => 'Birth Certificate'],
            ['slug' => 'zanid', 'name' => 'Zanzibar ID'],
        ];

        foreach ($identificationTypes as $identification) {
            IdentificationSource::create(
                $identification
            );
        }
    }
}
