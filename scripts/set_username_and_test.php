<?php
// Set username for user id=2 if empty, then run login test
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$id = 2;
$user = User::find($id);
if (!$user) {
    echo "User id=$id not found\n";
    exit(1);
}

echo "Before: id={$user->id}, email={$user->email}, username={$user->username}, status={$user->status}\n";
if (empty($user->username)) {
    // set username to email
    $user->username = $user->email ?: 'local-admin';
    $user->save();
    echo "Updated username to '{$user->username}'\n";
} else {
    echo "Username already set ('{$user->username}'), no change\n";
}

// Ensure password is known
$user->password = bcrypt('org_admin');
$user->status = 1;
$user->save();
echo "Ensured password='org_admin' and status=1\n";

// Run login test using username
$url = 'http://192.168.17.124:8000/api/app/v1/login';
$data = json_encode(['username' => $user->username, 'password' => 'org_admin']);
$opts = [ 'http' => [ 'method' => 'POST', 'header' => "Content-Type: application/json\r\n", 'content' => $data, 'ignore_errors' => true, ], ];
$context = stream_context_create($opts);
$res = @file_get_contents($url, false, $context);
if ($res === false) {
    echo "REQUEST FAILED\n";
    var_dump($http_response_header ?? null);
    exit(1);
}
echo "HTTP RESPONSE HEADERS:\n";
if (isset($http_response_header)) { foreach ($http_response_header as $h) echo $h . "\n"; }
echo "\nBODY:\n";
echo $res . "\n";
