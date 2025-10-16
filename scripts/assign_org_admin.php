<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Foundation\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// create an organization and assign admin id 2 as organization_admin
$orgId = DB::table('organizations')->insertGetId([
    'name' => 'Test Org',
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Created org id: $orgId\n";

$adminId = DB::table('admins')->where('user_id', 2)->value('id');
if (! $adminId) {
    echo "Admin not found for user_id=2\n";
    exit(1);
}

DB::table('organization_admins')->insert([
    'organization_id' => $orgId,
    'admin_id' => $adminId,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Assigned admin $adminId as organization_admin for org $orgId\n";
