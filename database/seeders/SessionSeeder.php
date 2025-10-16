<?php

namespace Database\Seeders;

use App\Models\Infos\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::factory()->count(50)->create();
    }
}
