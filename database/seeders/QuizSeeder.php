<?php

namespace Database\Seeders;

use App\Models\Users\Student;
use App\Models\Users\Teacher;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $students = Student::pluck('id');

        foreach ($students as $student) {
            $teacher = Teacher::pluck('id')->toArray();
            $randomTeacher = array_rand($teacher);

            $subject = $faker->randomElement(['حديث', 'تفسير', 'سيرة', 'فقه', 'تجويد', 'ثقافي']);
            DB::table('quizzes')->insert([
                'name' => 'إختبار',
                'quiz_subject' => $subject,
                'date' => now(),
                'quiz_type' => $subject,
                'score' => $faker->numberBetween(25, 100),
                'student_id' => $student,
                'teacher_id' => $teacher[$randomTeacher],
                'created_at' => now(),
            ]);
        }
    }
}
