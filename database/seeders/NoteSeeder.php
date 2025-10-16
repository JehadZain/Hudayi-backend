<?php

namespace Database\Seeders;

use App\Models\Users\Student;
use App\Models\Users\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
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
            $randomKey = array_rand($teacher);
            DB::table('notes')->insert([
                'student_id' => $student,
                'teacher_id' => $teacher[$randomKey],
                'date' => now(),
                'content' => 'هنا توضع ملاحظة عن الطالب في حال وجودها',
                'created_at' => now(),
            ]);

        }
    }
}
