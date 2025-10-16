<?php

namespace Database\Seeders;

use App\Models\Users\Student;
use App\Models\Users\Teacher;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuranQuizSeeder extends Seeder
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

            DB::table('quran_quizzes')->insert([
                'name' => 'قرآن',
                'juz' => '1',
                'page' => $faker->numberBetween(1, 30),
                'date' => now(),
                'exam_type' => $faker->randomElement(['تسميع', 'رشيدي', 'تجويد']),
                'score' => $faker->randomElement(['25', '75', '100']),
                'student_id' => $student,
                'teacher_id' => $teacher[$randomTeacher],
                'created_at' => now(),
            ]);
        }
    }
}
