<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Foundation\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing auth()->attempt with username...\n";
$creds = ['username' => 'org_admin', 'password' => 'org_admin'];
try {
    $res = auth()->attempt($creds);
    var_export($res);
    echo "\n";
} catch (Exception $e) {
    echo "Exception: " . get_class($e) . ": " . $e->getMessage() . "\n";
}

echo "Testing auth()->attempt with email...\n";
$creds2 = ['email' => 'mhd@.com', 'password' => 'org_admin'];
try {
    $res2 = auth()->attempt($creds2);
    var_export($res2);
    echo "\n";
} catch (Exception $e) {
    echo "Exception: " . get_class($e) . ": " . $e->getMessage() . "\n";
}

echo "Testing guard('api')->attempt with username...\n";
try {
    $res3 = auth('api')->attempt($creds);
    var_export($res3);
    echo "\n";
} catch (Exception $e) {
    echo "Exception: " . get_class($e) . ": " . $e->getMessage() . "\n";
}

echo "Current default guard: " . config('auth.defaults.guard') . "\n";
echo "User provider driver: " . config('auth.providers.users.driver') . "\n";
