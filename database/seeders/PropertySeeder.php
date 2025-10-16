<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Properties\Property;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // ممتلكات ثابتة لكل فرع
        $properties = [
            // مدارس
            ['name' => 'مدرسة الأمل الابتدائية', 'capacity' => 200, 'property_type' => 'school', 'description' => 'مدرسة ابتدائية تعنى بالتعليم الأساسي'],
            ['name' => 'مدرسة النور الثانوية', 'capacity' => 300, 'property_type' => 'school', 'description' => 'مدرسة ثانوية متخصصة في التعليم الأكاديمي'],
            ['name' => 'مدرسة الفجر الابتدائية', 'capacity' => 150, 'property_type' => 'school', 'description' => 'مدرسة ابتدائية حديثة'],
            ['name' => 'مدرسة السلام الثانوية', 'capacity' => 250, 'property_type' => 'school', 'description' => 'مدرسة ثانوية شاملة'],
            ['name' => 'مدرسة المستقبل الابتدائية', 'capacity' => 180, 'property_type' => 'school', 'description' => 'مدرسة ابتدائية متطورة'],
            
            // مساجد
            ['name' => 'مسجد النور', 'capacity' => 500, 'property_type' => 'mosque', 'description' => 'مسجد كبير يتسع لعدد كبير من المصلين'],
            ['name' => 'مسجد الفجر', 'capacity' => 300, 'property_type' => 'mosque', 'description' => 'مسجد متوسط الحجم'],
            ['name' => 'مسجد السلام', 'capacity' => 400, 'property_type' => 'mosque', 'description' => 'مسجد حديث التصميم'],
            ['name' => 'مسجد الرحمة', 'capacity' => 250, 'property_type' => 'mosque', 'description' => 'مسجد صغير ومريح'],
            ['name' => 'مسجد الهدى', 'capacity' => 350, 'property_type' => 'mosque', 'description' => 'مسجد يتوسط الحي'],
        ];

        $branches = Branch::pluck('id');
        $propertyIndex = 0;

        foreach ($branches as $branch) {
            // إضافة 2-3 ممتلكات لكل فرع
            $propertiesPerBranch = $faker->numberBetween(2, 3);
            
            for ($i = 0; $i < $propertiesPerBranch; $i++) {
                if ($propertyIndex < count($properties)) {
                    $property = $properties[$propertyIndex];
                    DB::table('properties')->insert([
                        'name' => $property['name'] . ' - فرع ' . $branch,
                        'capacity' => $property['capacity'],
                        'property_type' => $property['property_type'],
                        'description' => $property['description'] . ' في هذا الفرع',
                        'branch_id' => $branch,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $propertyIndex++;
                } else {
                    // إذا نفدت الممتلكات الثابتة، استخدم Factory
                    DB::table('properties')->insert([
                        'name' => $faker->sentence(2),
                        'capacity' => $faker->numberBetween(30, 500),
                        'property_type' => $faker->randomElement(['school', 'mosque']),
                        'description' => $faker->sentence(8),
                        'branch_id' => $branch,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // إنشاء ممتلكات إضافية يدوياً
        $additionalProperties = [
            ['name' => 'مدرسة إضافية 1', 'capacity' => 200, 'property_type' => 'school', 'description' => 'مدرسة إضافية للاختبار', 'branch_id' => 1],
            ['name' => 'مسجد إضافي 1', 'capacity' => 300, 'property_type' => 'mosque', 'description' => 'مسجد إضافي للاختبار', 'branch_id' => 1],
            ['name' => 'مدرسة إضافية 2', 'capacity' => 250, 'property_type' => 'school', 'description' => 'مدرسة إضافية للاختبار', 'branch_id' => 2],
            ['name' => 'مسجد إضافي 2', 'capacity' => 400, 'property_type' => 'mosque', 'description' => 'مسجد إضافي للاختبار', 'branch_id' => 2],
            ['name' => 'مدرسة إضافية 3', 'capacity' => 180, 'property_type' => 'school', 'description' => 'مدرسة إضافية للاختبار', 'branch_id' => 3],
            ['name' => 'مسجد إضافي 3', 'capacity' => 350, 'property_type' => 'mosque', 'description' => 'مسجد إضافي للاختبار', 'branch_id' => 3],
        ];

        foreach ($additionalProperties as $property) {
            DB::table('properties')->insert(array_merge($property, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
