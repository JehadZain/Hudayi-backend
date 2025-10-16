<?php

namespace Database\Seeders;

use App\Models\Properties\ClassRoomTeacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassRoomTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        ClassRoomTeacher::factory()->count(100)->create();

        DB::table('class_room_teachers')->insert([
            'class_room_id' => 1,
            'teacher_id' => 1,
            'joined_at' => now(),
            'left_at' => null,
        ]);
    }
}
