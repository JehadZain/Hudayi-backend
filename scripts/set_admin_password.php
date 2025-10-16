<?php
// Usage: php scripts/set_admin_password.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$targetId = 2;
$targetEmail = 'mhd@.com';

echo "Looking for user id=$targetId or email=$targetEmail\n";
$user = User::find($targetId);
if (!$user) {
    $user = User::where('email', $targetEmail)->first();
}

if (!$user) {
    echo "User not found by id or email.\n";
    exit(1);
}

echo "Found user: id={$user->id}, email={$user->email}, name={$user->name}\n";
$user->password = bcrypt('org_admin');
// set status if column exists
try {
    $user->status = 1;
} catch (Throwable $e) {
    // ignore if status not fillable or missing
}
$user->save();
echo "Password updated to 'org_admin' and status set to 1 (if applicable).\n";
echo "Done.\n";
