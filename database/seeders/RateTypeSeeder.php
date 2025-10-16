<?php

namespace Database\Seeders;

use App\Models\Infos\RateType;
use Illuminate\Database\Seeder;

class RateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RateType::factory()->count(4)->create();
    }
}
