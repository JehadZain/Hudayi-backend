<?php

namespace Database\Seeders;

use App\Models\Infos\Activity;
use App\Models\Users\Student;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $activities = Activity::pluck('id');
        foreach ($activities as $activity) {
            for ($i = 0; $i < 2; $i++) {
                DB::table('participants')->insert([
                    'activity_id' => $activity,
                    'student_id' => Student::inRandomOrder()->firstOrFail()->id,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
