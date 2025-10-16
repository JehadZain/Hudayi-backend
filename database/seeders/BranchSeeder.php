<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // فروع لمنظمة بركة التعليمية
        $barakaBranches = [
            ['name' => 'فرع عفرين', 'organization_id' => 1],
            ['name' => 'فرع إدلب', 'organization_id' => 1],
            ['name' => 'فرع إعزاز', 'organization_id' => 1],
            ['name' => 'فرع الباب', 'organization_id' => 1],
            ['name' => 'فرع جنديرس', 'organization_id' => 1],
        ];

        // فروع لجمعية نور العلم
        $noorBranches = [
            ['name' => 'المركز الرئيسي - إدلب', 'organization_id' => 2],
            ['name' => 'فرع معرة النعمان', 'organization_id' => 2],
            ['name' => 'فرع كفر نبل', 'organization_id' => 2],
        ];

        // فروع لمؤسسة الفجر التعليمية
        $alfajrBranches = [
            ['name' => 'المركز الرئيسي - حلب', 'organization_id' => 3],
            ['name' => 'فرع حلب الجديدة', 'organization_id' => 3],
            ['name' => 'فرع الشيخ مقصود', 'organization_id' => 3],
            ['name' => 'فرع الباب', 'organization_id' => 3],
        ];

        // فروع لمركز التربية الإسلامية
        $islamicBranches = [
            ['name' => 'المركز الرئيسي - اللاذقية', 'organization_id' => 4],
            ['name' => 'فرع جبلة', 'organization_id' => 4],
            ['name' => 'فرع بانياس', 'organization_id' => 4],
        ];

        // فروع لجمعية التنمية التعليمية
        $devBranches = [
            ['name' => 'المركز الرئيسي - حمص', 'organization_id' => 5],
            ['name' => 'فرع تدمر', 'organization_id' => 5],
            ['name' => 'فرع القصير', 'organization_id' => 5],
        ];

        $allBranches = array_merge(
            $barakaBranches,
            $noorBranches,
            $alfajrBranches,
            $islamicBranches,
            $devBranches
        );

        foreach ($allBranches as $branch) {
            DB::table('branches')->insert(array_merge($branch, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إنشاء فروع إضافية يدوياً
        $additionalBranches = [
            ['name' => 'فرع إضافي 1', 'organization_id' => 1],
            ['name' => 'فرع إضافي 2', 'organization_id' => 1],
            ['name' => 'فرع إضافي 3', 'organization_id' => 2],
            ['name' => 'فرع إضافي 4', 'organization_id' => 2],
            ['name' => 'فرع إضافي 5', 'organization_id' => 3],
            ['name' => 'فرع إضافي 6', 'organization_id' => 3],
            ['name' => 'فرع إضافي 7', 'organization_id' => 4],
            ['name' => 'فرع إضافي 8', 'organization_id' => 5],
        ];

        foreach ($additionalBranches as $branch) {
            DB::table('branches')->insert(array_merge($branch, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
