<?php

namespace Database\Seeders;

use App\Models\Infos\Grade;
use App\Models\Properties\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إنشاء صفوف تعليمية للصفوف من 1 إلى 6
        $grades = [
            ['name' => 'الصف الأول', 'property_id' => 1],
            ['name' => 'الصف الثاني', 'property_id' => 1],
            ['name' => 'الصف الثالث', 'property_id' => 1],
            ['name' => 'الصف الرابع', 'property_id' => 1],
            ['name' => 'الصف الخامس', 'property_id' => 1],
            ['name' => 'الصف السادس', 'property_id' => 1],
            ['name' => 'الصف السابع', 'property_id' => 2],
            ['name' => 'الصف الثامن', 'property_id' => 2],
            ['name' => 'الصف التاسع', 'property_id' => 2],
            ['name' => 'الصف العاشر', 'property_id' => 3],
            ['name' => 'الصف الحادي عشر', 'property_id' => 3],
            ['name' => 'الصف الثاني عشر', 'property_id' => 3],
        ];

        foreach ($grades as $grade) {
            DB::table('grades')->insert(array_merge($grade, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
