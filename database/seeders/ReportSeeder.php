<?php

namespace Database\Seeders;

use App\Models\Morphs\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Report::factory()->count(30)->create();
    }
}
