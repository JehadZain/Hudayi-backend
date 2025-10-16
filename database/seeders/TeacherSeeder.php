<?php

namespace Database\Seeders;

use App\Models\Properties\ClassRoom;
use App\Models\Users\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إضافة الأساتذة المحددين
        $teachers = [
            ['user_id' => 5, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '3'], // عامر الخطيب
            ['user_id' => 7, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '2'], // أحمد المعلم
            ['user_id' => 8, 'marital_status' => 'أعزب', 'wives_count' => '0', 'children_count' => '0'], // فاطمة المعلمة
            ['user_id' => 9, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '4'], // محمد الشيخ
        ];

        foreach ($teachers as $teacher) {
            DB::table('teachers')->insert(array_merge($teacher, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إضافة أساتذة إضافيين يدوياً
        $additionalTeachers = [
            ['user_id' => 15, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '2'], // مريم
            ['user_id' => 16, 'marital_status' => 'أعزب', 'wives_count' => '0', 'children_count' => '0'], // عمر
        ];

        foreach ($additionalTeachers as $teacher) {
            DB::table('teachers')->insert(array_merge($teacher, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
