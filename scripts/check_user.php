<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Foundation\Console\Kernel::class);
$kernel->bootstrap();
$u = App\Models\User::where('username','org_admin')->first();
if ($u) {
    echo "found: {$u->id}\n";
    echo "pw: {$u->password}\n";
    echo (\Illuminate\Support\Facades\Hash::check('org_admin', $u->password) ? "hash ok\n" : "hash fail\n");
} else {
    echo "not found\n";
}
