<?php

namespace Database\Seeders;

use App\Models\Infos\Grade;
use App\Models\Properties\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إنشاء صفوف متنوعة للصفوف المختلفة
        $classRooms = [
            // الصف الأول
            ['name' => 'الأبطال', 'capacity' => 25, 'grade_id' => 1, 'is_approved' => true],
            ['name' => 'الشجعان', 'capacity' => 30, 'grade_id' => 1, 'is_approved' => true],
            ['name' => 'الفرسان', 'capacity' => 28, 'grade_id' => 1, 'is_approved' => false],
            
            // الصف الثاني
            ['name' => 'النجوم', 'capacity' => 27, 'grade_id' => 2, 'is_approved' => true],
            ['name' => 'القمر', 'capacity' => 26, 'grade_id' => 2, 'is_approved' => true],
            ['name' => 'الشمس', 'capacity' => 29, 'grade_id' => 2, 'is_approved' => true],
            
            // الصف الثالث
            ['name' => 'الزهرة', 'capacity' => 24, 'grade_id' => 3, 'is_approved' => true],
            ['name' => 'الوردة', 'capacity' => 25, 'grade_id' => 3, 'is_approved' => true],
            ['name' => 'الغصن', 'capacity' => 23, 'grade_id' => 3, 'is_approved' => false],
            
            // الصف الرابع
            ['name' => 'البراعم', 'capacity' => 30, 'grade_id' => 4, 'is_approved' => true],
            ['name' => 'الأزهار', 'capacity' => 28, 'grade_id' => 4, 'is_approved' => true],
            ['name' => 'الورود', 'capacity' => 26, 'grade_id' => 4, 'is_approved' => true],
            
            // الصف الخامس
            ['name' => 'الطموح', 'capacity' => 32, 'grade_id' => 5, 'is_approved' => true],
            ['name' => 'الأمل', 'capacity' => 31, 'grade_id' => 5, 'is_approved' => true],
            ['name' => 'المستقبل', 'capacity' => 29, 'grade_id' => 5, 'is_approved' => true],
            
            // الصف السادس
            ['name' => 'التميز', 'capacity' => 27, 'grade_id' => 6, 'is_approved' => true],
            ['name' => 'الإبداع', 'capacity' => 28, 'grade_id' => 6, 'is_approved' => true],
            ['name' => 'الابتكار', 'capacity' => 25, 'grade_id' => 6, 'is_approved' => false],
        ];

        foreach ($classRooms as $classRoom) {
            DB::table('class_rooms')->insert(array_merge($classRoom, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إضافة صفوف إضافية يدوياً
        $additionalClassRooms = [
            ['name' => 'الصف الإضافي 1', 'capacity' => 25, 'grade_id' => 1, 'is_approved' => true],
            ['name' => 'الصف الإضافي 2', 'capacity' => 30, 'grade_id' => 2, 'is_approved' => true],
            ['name' => 'الصف الإضافي 3', 'capacity' => 28, 'grade_id' => 3, 'is_approved' => true],
            ['name' => 'الصف الإضافي 4', 'capacity' => 26, 'grade_id' => 4, 'is_approved' => false],
            ['name' => 'الصف الإضافي 5', 'capacity' => 29, 'grade_id' => 5, 'is_approved' => true],
        ];

        foreach ($additionalClassRooms as $classRoom) {
            DB::table('class_rooms')->insert(array_merge($classRoom, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
