<?php

namespace Database\Seeders;

use App\Models\Properties\ClassRoom;
use App\Models\Users\Student;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassRoomStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //        $faker = Faker::create();
        //        $classRooms = ClassRoom::pluck('id');
        //        foreach ($classRooms as $classRoom) {
        //            for ($i = 0; $i < 2; $i++) {
        ////                DB::table('class_room_students')->insert([
        ////                    'class_room_id' => $classRoom,
        ////                    'student_id' => Student::inRandomOrder()->firstOrFail()->id,
        ////                    'joined_at' => $faker->dateTimeBetween('-1 years', 'now'),
        ////                    'left_at' => $faker->dateTimeBetween('-1 week', 'now'),
        ////                ]);
        //            }
        //            DB::table('class_room_students')->insert([
        //                'class_room_id' => $classRoom,
        //                'student_id' => Student::inRandomOrder()->firstOrFail()->id,
        //                'joined_at' => $faker->dateTimeBetween('-1 years', 'now'),
        //                'left_at' => null,
        //            ]);
        //        }

        DB::table('class_room_students')->insert([
            'class_room_id' => 1,
            'student_id' => 1,
            'joined_at' => now(),
            'left_at' => null,
        ]);
    }
}
