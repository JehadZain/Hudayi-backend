<?php

namespace Database\Seeders;

use App\Models\Morphs\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rate::factory()->count(30)->create();
    }
}
