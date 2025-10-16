<?php

namespace Database\Seeders;

use App\Models\Infos\CertificateTranscript;
use Illuminate\Database\Seeder;

class CertificateTranscriptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificateTranscript::factory()->count(30)->create();
    }
}
