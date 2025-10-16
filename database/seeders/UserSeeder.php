<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'MHD',
            'last_name' => 'WAIL',
            'email' => 'wail@gmail.com',
            'username' => 'wail',
            'password' => Hash::make('password'),
            'phone' => '05538370883',
            'email_verified_at' => now(),
            'identity_number' => '9994564333',
            'gender' => 'male',
            'birth_place' => 'Syria',
            'blood_type' => 'BP',
            'remember_token' => Str()->random(20),
            'is_approved' => true,
        ]);

        DB::table('users')->insert([
            'first_name' => 'محمد',
            'last_name' => 'بشار',
            'email' => 'mhd@.com',
            'username' => 'org_admin',
            'password' => Hash::make('org_admin'),
            'phone' => '057890883',
            'email_verified_at' => now(),
            'identity_number' => '9455545233',
            'gender' => 'male',
            'birth_place' => 'Syria',
            'blood_type' => 'BP',
            'remember_token' => Str()->random(20),
            'is_approved' => true,

        ]);

        DB::table('users')->insert([
            'first_name' => 'احمد',
            'last_name' => 'بشار',
            'email' => null,
            'username' => 'branch_admin',
            'password' => Hash::make('branch_admin'),
            'email_verified_at' => now(),
            'identity_number' => null,
            'gender' => 'male',
            'birth_place' => 'Syria',
            'blood_type' => 'BP',
            'remember_token' => Str()->random(20),
            'is_approved' => true,

        ]);

        DB::table('users')->insert([
            'first_name' => 'فراس',
            'last_name' => 'بشار',
            'email' => null,
            'username' => 'property_admin',
            'password' => Hash::make('property_admin'),
            'email_verified_at' => now(),
            'identity_number' => null,
            'gender' => 'male',
            'birth_place' => 'Syria',
            'blood_type' => 'BP',
            'remember_token' => Str()->random(20),
            'is_approved' => true,

        ]);

        //        DB::table('users')->insert([
        //            'first_name' => 'محمد',
        //            'last_name' => 'بشار',
        //            'email' => 'mhd@gmail.com',
        //            'username'=>'admin',
        //            'password' => Hash::make('admin'),
        //            'phone' => '057890883',
        //            'email_verified_at' => now(),
        //            'identity_number' => '945695545233',
        //            'gender' => 'male',
        //            'birth_place' => 'Syria',
        //            'blood_type' => 'BP',
        //            'remember_token' => Str()->random(20),
        //        ]);

        DB::table('users')->insert([
            'first_name' => 'عامر',
            'last_name' => 'الخطيب',
            'email' => 'amer@gmail.com',
            'username' => 'teacher',
            'password' => Hash::make('teacher'),
            'email_verified_at' => now(),
            'identity_number' => null,
            'gender' => 'male',
            'birth_place' => 'Syria',
            'blood_type' => 'BP',
            'remember_token' => Str()->random(20),
            'is_approved' => true,

        ]);

        DB::table('users')->insert([
            'first_name' => 'خالد',
            'last_name' => 'مصطفى',
            'email' => null,
            'username' => 'student',
            'password' => Hash::make('student'),
            'email_verified_at' => now(),
            'identity_number' => null,
            'gender' => 'male',
            'birth_place' => 'Syria',
            'blood_type' => 'BP',
            'remember_token' => Str()->random(20),
            'is_approved' => true,

        ]);
        // إضافة مستخدمين متنوعين للاختبار
        $additionalUsers = [
            // أساتذة إضافيون
            [
                'first_name' => 'أحمد',
                'last_name' => 'المعلم',
                'email' => 'ahmed.teacher@gmail.com',
                'username' => 'ahmed_teacher',
                'password' => Hash::make('password'),
                'phone' => '0912345678',
                'email_verified_at' => now(),
                'identity_number' => '1234567890',
                'gender' => 'male',
                'birth_place' => 'دمشق',
                'blood_type' => 'A+',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
            [
                'first_name' => 'فاطمة',
                'last_name' => 'المعلمة',
                'email' => 'fatima.teacher@gmail.com',
                'username' => 'fatima_teacher',
                'password' => Hash::make('password'),
                'phone' => '0923456789',
                'email_verified_at' => now(),
                'identity_number' => '2345678901',
                'gender' => 'female',
                'birth_place' => 'حلب',
                'blood_type' => 'B+',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'الشيخ',
                'email' => 'mohammed.sheikh@gmail.com',
                'username' => 'mohammed_sheikh',
                'password' => Hash::make('password'),
                'phone' => '0934567890',
                'email_verified_at' => now(),
                'identity_number' => '3456789012',
                'gender' => 'male',
                'birth_place' => 'إدلب',
                'blood_type' => 'O+',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],

            // طلاب إضافيون
            [
                'first_name' => 'علي',
                'last_name' => 'الطالب',
                'email' => 'ali.student@gmail.com',
                'username' => 'ali_student',
                'password' => Hash::make('password'),
                'phone' => '0945678901',
                'email_verified_at' => now(),
                'identity_number' => '4567890123',
                'gender' => 'male',
                'birth_place' => 'عفرين',
                'blood_type' => 'A-',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
            [
                'first_name' => 'زينب',
                'last_name' => 'الطالبة',
                'email' => 'zeinab.student@gmail.com',
                'username' => 'zeinab_student',
                'password' => Hash::make('password'),
                'phone' => '0956789012',
                'email_verified_at' => now(),
                'identity_number' => '5678901234',
                'gender' => 'female',
                'birth_place' => 'الباب',
                'blood_type' => 'B-',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
            [
                'first_name' => 'حسن',
                'last_name' => 'الطالب',
                'email' => 'hassan.student@gmail.com',
                'username' => 'hassan_student',
                'password' => Hash::make('password'),
                'phone' => '0967890123',
                'email_verified_at' => now(),
                'identity_number' => '6789012345',
                'gender' => 'male',
                'birth_place' => 'إعزاز',
                'blood_type' => 'AB+',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],

            // مدراء إضافيون
            [
                'first_name' => 'سارة',
                'last_name' => 'المديرة',
                'email' => 'sara.admin@gmail.com',
                'username' => 'sara_admin',
                'password' => Hash::make('password'),
                'phone' => '0978901234',
                'email_verified_at' => now(),
                'identity_number' => '7890123456',
                'gender' => 'female',
                'birth_place' => 'اللاذقية',
                'blood_type' => 'O-',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
            [
                'first_name' => 'يوسف',
                'last_name' => 'المدير',
                'email' => 'youssef.admin@gmail.com',
                'username' => 'youssef_admin',
                'password' => Hash::make('password'),
                'phone' => '0989012345',
                'email_verified_at' => now(),
                'identity_number' => '8901234567',
                'gender' => 'male',
                'birth_place' => 'حمص',
                'blood_type' => 'AB-',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
        ];

        foreach ($additionalUsers as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // إنشاء مستخدمين إضافيين يدوياً
        $moreUsers = [
            // طلاب إضافيون
            [
                'first_name' => 'مريم',
                'last_name' => 'الطالبة',
                'email' => 'mariam.student@gmail.com',
                'username' => 'mariam_student',
                'password' => Hash::make('password'),
                'phone' => '0991234567',
                'email_verified_at' => now(),
                'identity_number' => '1234567890123',
                'gender' => 'female',
                'birth_place' => 'دمشق',
                'blood_type' => 'O+',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
            [
                'first_name' => 'عمر',
                'last_name' => 'الطالب',
                'email' => 'omar.student@gmail.com',
                'username' => 'omar_student',
                'password' => Hash::make('password'),
                'phone' => '0992345678',
                'email_verified_at' => now(),
                'identity_number' => '2345678901234',
                'gender' => 'male',
                'birth_place' => 'حلب',
                'blood_type' => 'A+',
                'remember_token' => Str()->random(20),
                'is_approved' => true,
                'status' => '1',
            ],
        ];

        foreach ($moreUsers as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
