<?php

namespace Database\Seeders;

use App\Models\Infos\CertificateType;
use Illuminate\Database\Seeder;

class CertificateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificateType::factory()->count(3)->create();
    }
}
