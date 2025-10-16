<?php

namespace Database\Seeders;

use App\Models\Report\ReportReviewer;
use Illuminate\Database\Seeder;

class ReportReviewerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportReviewer::factory()->count(20)->create();
    }
}
