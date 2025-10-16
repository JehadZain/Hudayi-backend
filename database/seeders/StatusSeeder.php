<?php

namespace Database\Seeders;

use App\Models\Infos\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::factory()->count(50)->create();
    }
}
