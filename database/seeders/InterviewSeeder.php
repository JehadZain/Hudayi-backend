<?php

namespace Database\Seeders;

use App\Models\Properties\Property;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $students = Student::pluck('id');

        foreach ($students as $student) {
            $teacher = Teacher::pluck('id')->toArray();
            $randomTeacher = array_rand($teacher);

            $properties = Property::pluck('name')->toArray();
            $randomProp = array_rand($properties);
            DB::table('interviews')->insert([
                'name' => ' مقابلة مع الطالب ',
                'event_place' => $properties[$randomProp],
                'date' => now(),
                'goal' => 'توجيهي',
                'comment' => 'هنا تكتب الملاحظات ونتيجة المقابلة',
                'student_id' => $student,
                'teacher_id' => $teacher[$randomTeacher],
                'created_at' => now(),
            ]);

        }
    }
}
