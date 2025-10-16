<?php

namespace Database\Seeders;

use App\Models\Report\ReportContent;
use Illuminate\Database\Seeder;

class ReportContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportContent::factory()->count(20)->create();
    }
}
