<?php

namespace Database\Seeders;

use App\Models\Infos\CertificateType;
use Illuminate\Database\Seeder;

class CertificateDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificateType::factory()->count(30)->create();
    }
}
