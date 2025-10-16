<?php

namespace Database\Seeders;

use App\Models\Infos\Session;
use App\Models\SessionAttendance;
use Illuminate\Database\Seeder;

class SessionAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sessions = Session::pluck('id');

        foreach ($sessions as $session) {
            SessionAttendance::factory()->count(4)->create();
        }
    }
}
