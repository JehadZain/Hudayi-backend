<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // منظمات تعليمية شاملة
        $organizations = [
            [
                'name' => 'منظمة بركة التعليمية',
                'location' => 'الشمال السوري',
                'capacity' => '5000',
                'website' => 'https://baraka-org.com',
                'description' => 'مؤسسة تعليمية رائدة في الداخل السوري تعنى بالتعليم الأكاديمي والتربية الإسلامية',
            ],
            [
                'name' => 'جمعية نور العلم',
                'location' => 'إدلب',
                'capacity' => '3500',
                'website' => 'https://noor-alelm.org',
                'description' => 'جمعية تعليمية تهدف إلى نشر العلم والمعرفة في المنطقة',
            ],
            [
                'name' => 'مؤسسة الفجر التعليمية',
                'location' => 'حلب',
                'capacity' => '4000',
                'website' => 'https://alfajr-edu.sy',
                'description' => 'مؤسسة تعليمية متخصصة في التعليم الأساسي والثانوي',
            ],
            [
                'name' => 'مركز التربية الإسلامية',
                'location' => 'اللاذقية',
                'capacity' => '2500',
                'website' => 'https://islamic-center.org',
                'description' => 'مركز متخصص في تعليم القرآن الكريم والعلوم الإسلامية',
            ],
            [
                'name' => 'جمعية التنمية التعليمية',
                'location' => 'حمص',
                'capacity' => '3000',
                'website' => 'https://education-dev.org',
                'description' => 'جمعية تعمل على تطوير التعليم وتقديم خدمات تعليمية متميزة',
            ]
        ];

        foreach ($organizations as $org) {
            DB::table('organizations')->insert(array_merge($org, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إنشاء منظمات إضافية باستخدام Factory
        Organization::factory()->count(3)->create();
    }
}
