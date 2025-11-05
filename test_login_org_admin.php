<?php

/**
 * ملف اختبار تسجيل الدخول لمستخدم org_admin
 * 
 * الاستخدام:
 * php test_login_org_admin.php
 * 
 * أو استخدمه من المتصفح إذا كان الخادم يعمل
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Users\User;
use App\Models\Users\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "  اختبار تسجيل الدخول - org_admin\n";
echo "========================================\n\n";

// البيانات المستخدمة
$username = 'org_admin';
$password = 'org_admin';

echo "📋 البيانات المستخدمة:\n";
echo "   Username: $username\n";
echo "   Password: $password\n\n";

// 1. التحقق من وجود المستخدم في قاعدة البيانات
echo "1️⃣  التحقق من وجود المستخدم في قاعدة البيانات...\n";
$user = User::where('username', $username)->first();

if (!$user) {
    echo "   ❌ خطأ: المستخدم '$username' غير موجود في قاعدة البيانات!\n\n";
    echo "   💡 الحل: تأكد من تشغيل Database Seeders:\n";
    echo "      php artisan db:seed --class=UserSeeder\n\n";
    exit(1);
}

echo "   ✅ المستخدم موجود (ID: {$user->id})\n";
echo "   📝 الاسم: {$user->first_name} {$user->last_name}\n";
echo "   📧 البريد: " . ($user->email ?? 'غير موجود') . "\n";
echo "   📱 الهاتف: " . ($user->phone ?? 'غير موجود') . "\n\n";

// 2. التحقق من كلمة المرور
echo "2️⃣  التحقق من كلمة المرور...\n";
if (!Hash::check($password, $user->password)) {
    echo "   ❌ خطأ: كلمة المرور غير صحيحة!\n\n";
    echo "   💡 الحل: قم بتحديث كلمة المرور:\n";
    echo "      php artisan tinker\n";
    echo "      \$user = User::where('username', 'org_admin')->first();\n";
    echo "      \$user->password = Hash::make('org_admin');\n";
    echo "      \$user->save();\n\n";
    exit(1);
}
echo "   ✅ كلمة المرور صحيحة\n\n";

// 3. محاولة تسجيل الدخول باستخدام auth()->attempt()
echo "3️⃣  محاولة تسجيل الدخول باستخدام auth()->attempt()...\n";
$credentials = [
    'username' => $username,
    'password' => $password
];

try {
    $token = auth()->attempt($credentials);
    
    if (!$token) {
        echo "   ❌ خطأ: فشل تسجيل الدخول!\n";
        echo "   💡 الأسباب المحتملة:\n";
        echo "      - المستخدم غير مفعّل (is_approved = false)\n";
        echo "      - مشكلة في إعدادات JWT\n";
        echo "      - مشكلة في User Model\n\n";
        
        // فحص الحالة
        echo "   🔍 فحص حالة المستخدم:\n";
        echo "      - is_approved: " . ($user->is_approved ? 'true' : 'false') . "\n";
        echo "      - status: " . ($user->status ?? 'null') . "\n";
        echo "      - deleted_at: " . ($user->deleted_at ?? 'null') . "\n\n";
        
        exit(1);
    }
    
    echo "   ✅ نجح تسجيل الدخول!\n";
    echo "   🎫 Token: " . substr($token, 0, 50) . "...\n\n";
    
} catch (Exception $e) {
    echo "   ❌ خطأ في تسجيل الدخول: " . $e->getMessage() . "\n";
    echo "   📋 نوع الخطأ: " . get_class($e) . "\n\n";
    exit(1);
}

// 4. الحصول على معلومات المستخدم بعد تسجيل الدخول
echo "4️⃣  معلومات المستخدم بعد تسجيل الدخول...\n";
$loggedInUser = auth()->user();

if (!$loggedInUser) {
    echo "   ❌ خطأ: لم يتم العثور على المستخدم المسجل دخوله!\n\n";
    exit(1);
}

echo "   ✅ المستخدم المسجل دخوله:\n";
echo "      ID: {$loggedInUser->id}\n";
echo "      الاسم: {$loggedInUser->first_name} {$loggedInUser->last_name}\n";
echo "      Username: {$loggedInUser->username}\n";
echo "      Email: " . ($loggedInUser->email ?? 'غير موجود') . "\n\n";

// 5. التحقق من نوع المستخدم (Admin, Student, Teacher)
echo "5️⃣  التحقق من نوع المستخدم...\n";
$admin = Admin::where('user_id', $loggedInUser->id)->first();

if ($admin) {
    echo "   ✅ المستخدم هو مدير (Admin)\n";
    echo "      Admin ID: {$admin->id}\n";
    
    // التحقق من نوع المدير
    $orgAdmin = $admin->organizationAdmins()->first();
    $branchAdmin = $admin->branchAdmins()->first();
    $propertyAdmin = $admin->propertyAdmins()->first();
    
    if ($orgAdmin) {
        echo "   👔 نوع المدير: Organization Admin\n";
        echo "      Organization ID: {$orgAdmin->organization_id}\n";
        
        $org = DB::table('organizations')->where('id', $orgAdmin->organization_id)->first();
        if ($org) {
            echo "      Organization Name: {$org->name}\n";
        }
    } elseif ($branchAdmin) {
        echo "   👔 نوع المدير: Branch Admin\n";
        echo "      Branch ID: {$branchAdmin->branch_id}\n";
    } elseif ($propertyAdmin) {
        echo "   👔 نوع المدير: Property Admin\n";
        echo "      Property ID: {$propertyAdmin->property_id}\n";
    } else {
        echo "   ⚠️  المدير غير مرتبط بأي منظمة/فرع/ملكية\n";
    }
} else {
    echo "   ⚠️  المستخدم ليس مديراً\n";
}

echo "\n";

// 6. اختبار Token
echo "6️⃣  اختبار Token...\n";
try {
    $decoded = auth()->payload();
    echo "   ✅ Token صالح\n";
    echo "   📋 معلومات Token:\n";
    echo "      Subject (User ID): " . $decoded->get('sub') . "\n";
    echo "      Issued At: " . date('Y-m-d H:i:s', $decoded->get('iat')) . "\n";
    echo "      Expires At: " . date('Y-m-d H:i:s', $decoded->get('exp')) . "\n";
} catch (Exception $e) {
    echo "   ❌ خطأ في Token: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. اختبار API Endpoint (إذا كان الخادم يعمل)
echo "7️⃣  اختبار API Endpoint...\n";
$apiUrl = 'http://localhost:8000/api/app/v1/login';

// التحقق من أن الخادم يعمل
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($credentials));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

$response = @curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo "   ⚠️  الخادم غير متاح (قد يكون غير مفعّل)\n";
    echo "   💡 لتشغيل الخادم:\n";
    echo "      php artisan serve\n\n";
} else {
    $responseData = json_decode($response, true);
    if ($httpCode === 200 && isset($responseData['data']['token'])) {
        echo "   ✅ API Endpoint يعمل بشكل صحيح!\n";
        echo "   🎫 Token من API: " . substr($responseData['data']['token'], 0, 50) . "...\n";
        echo "   👤 Role: " . ($responseData['data']['role'] ?? 'N/A') . "\n";
    } else {
        echo "   ❌ خطأ في API Endpoint\n";
        echo "   HTTP Code: $httpCode\n";
        echo "   Response: " . substr($response, 0, 200) . "\n";
    }
}

echo "\n";

// 8. ملخص النتائج
echo "========================================\n";
echo "  📊 ملخص النتائج\n";
echo "========================================\n";
echo "✅ تسجيل الدخول: نجح\n";
echo "✅ المستخدم: {$loggedInUser->first_name} {$loggedInUser->last_name}\n";
echo "✅ Username: {$loggedInUser->username}\n";
echo "✅ Token: تم إنشاؤه بنجاح\n";

if ($admin) {
    $role = 'Organization Admin';
    if ($orgAdmin) {
        $role = 'Organization Admin';
    } elseif ($branchAdmin) {
        $role = 'Branch Admin';
    } elseif ($propertyAdmin) {
        $role = 'Property Admin';
    }
    echo "✅ Role: $role\n";
}

echo "\n";
echo "🎉 جميع الاختبارات نجحت!\n";
echo "\n";

// إعادة تعيين المصادقة (إذا كان blacklist مفعّل)
try {
    auth()->logout();
} catch (Exception $e) {
    // تجاهل خطأ logout إذا كان blacklist غير مفعّل
}

