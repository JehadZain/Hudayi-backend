<?php

namespace Database\Seeders;

use App\Models\Users\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إضافة المدراء المحددين
        $admins = [
            ['user_id' => 1, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '2'], // MHD WAIL
            ['user_id' => 2, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '3'], // محمد بشار
            ['user_id' => 3, 'marital_status' => 'أعزب', 'wives_count' => '0', 'children_count' => '0'], // احمد بشار
            ['user_id' => 4, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '1'], // فراس بشار
            ['user_id' => 13, 'marital_status' => 'متزوجة', 'wives_count' => '0', 'children_count' => '2'], // سارة المديرة
            ['user_id' => 14, 'marital_status' => 'متزوج', 'wives_count' => '1', 'children_count' => '3'], // يوسف المدير
        ];

        foreach ($admins as $admin) {
            DB::table('admins')->insert(array_merge($admin, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إضافة مدراء إضافيين يدوياً
        $additionalAdmins = [
            ['user_id' => 15, 'marital_status' => 'متزوجة', 'wives_count' => '0', 'children_count' => '1'], // مريم
            ['user_id' => 16, 'marital_status' => 'أعزب', 'wives_count' => '0', 'children_count' => '0'], // عمر
        ];

        foreach ($additionalAdmins as $admin) {
            DB::table('admins')->insert(array_merge($admin, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
