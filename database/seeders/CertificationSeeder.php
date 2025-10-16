<?php

namespace Database\Seeders;

use App\Models\Infos\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Certification::factory()->count(30)->create();
    }
}
