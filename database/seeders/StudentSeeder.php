<?php

namespace Database\Seeders;

use App\Models\Properties\ClassRoom;
use App\Models\Users\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إضافة الطلاب المحددين
        $students = [
            ['user_id' => 6, 'parent_work' => 'موظف', 'family_members_count' => '5', 'is_orphan' => 'false', 'parent_phone' => '0911111111', 'who_is_parent' => 'الأب'], // خالد مصطفى
            ['user_id' => 10, 'parent_work' => 'معلم', 'family_members_count' => '4', 'is_orphan' => 'false', 'parent_phone' => '0922222222', 'who_is_parent' => 'الأب'], // علي الطالب
            ['user_id' => 11, 'parent_work' => 'طبيب', 'family_members_count' => '6', 'is_orphan' => 'false', 'parent_phone' => '0933333333', 'who_is_parent' => 'الأب'], // زينب الطالبة
            ['user_id' => 12, 'parent_work' => 'مهندس', 'family_members_count' => '3', 'is_orphan' => 'false', 'parent_phone' => '0944444444', 'who_is_parent' => 'الأب'], // حسن الطالب
        ];

        foreach ($students as $student) {
            DB::table('students')->insert(array_merge($student, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إضافة طلاب إضافيين يدوياً
        $additionalStudents = [
            ['user_id' => 15, 'parent_work' => 'ممرض', 'family_members_count' => '4', 'is_orphan' => 'false', 'parent_phone' => '0955555555', 'who_is_parent' => 'الأب'], // مريم
            ['user_id' => 16, 'parent_work' => 'مهندس', 'family_members_count' => '3', 'is_orphan' => 'false', 'parent_phone' => '0966666666', 'who_is_parent' => 'الأب'], // عمر
        ];

        foreach ($additionalStudents as $student) {
            DB::table('students')->insert(array_merge($student, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
