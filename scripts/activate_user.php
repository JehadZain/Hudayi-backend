<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Foundation\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('username','org_admin')->first();
if (! $u) { echo "not found\n"; exit(1); }
$u->status = 1;
$u->save();
echo "status set for user {$u->id}\n";
