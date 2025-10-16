<?php

namespace Database\Seeders;

use App\Models\Infos\StatusType;
use Illuminate\Database\Seeder;

class StatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusType::factory()->count(3)->create();
    }
}
