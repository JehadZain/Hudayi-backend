<?php

namespace Database\Seeders;

use App\Models\Infos\JobTitle;
use Illuminate\Database\Seeder;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobTitle::factory()->count(30)->create();
    }
}
