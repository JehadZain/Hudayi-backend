<?php

namespace Database\Seeders;

use App\Models\Infos\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Patient::factory()->count(30)->create();
    }
}
