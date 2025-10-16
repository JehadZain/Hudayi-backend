<?php

namespace Database\Seeders;

use App\Models\Properties\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $classRooms = ClassRoom::pluck('id');
        foreach ($classRooms as $classRoom) {
            $subjects = ['حديث', 'تفسير', 'سيرة', 'فقه', 'تجويد', 'ثقافي'];
            $randomKey = array_rand($subjects);
            $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

            DB::table('calendars')->insert([
                'class_room_id' => $classRoom,
                'day_name' => $days[$randomKey],
                'subject_name' => $subjects[$randomKey],
                'start_at' => '05:10 م',
                'end_at' => '06:10 م',
                'created_at' => now(),
            ]);
        }
    }
}
