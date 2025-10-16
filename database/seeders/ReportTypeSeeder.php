<?php

namespace Database\Seeders;

use App\Models\Report\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportType::factory()->count('5')->create();
    }
}
