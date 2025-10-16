<?php

namespace Database\Seeders;

use App\Models\Infos\PatientTreatment;
use Illuminate\Database\Seeder;

class PatientTreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PatientTreatment::factory()->count(30)->create();
    }
}
