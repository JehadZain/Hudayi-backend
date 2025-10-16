<?php
// Usage: php scripts/get_token.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Credentials (ensure they exist)
$username = 'org_admin';
$password = 'org_admin';

$url = 'http://192.168.17.124:8000/api/app/v1/login';
$payload = json_encode(['username' => $username, 'password' => $password]);
$opts = [ 'http' => [ 'method' => 'POST', 'header' => "Content-Type: application/json\r\n", 'content' => $payload, 'ignore_errors' => true, ], ];
$context = stream_context_create($opts);
$res = @file_get_contents($url, false, $context);
if ($res === false) {
    echo "ERROR: request failed\n";
    if (isset($http_response_header)) foreach ($http_response_header as $h) echo $h . "\n";
    exit(1);
}
$json = json_decode($res, true);
if (!is_array($json) || !isset($json['data']['token'])) {
    echo "ERROR: token not found in response\n";
    echo $res . "\n";
    exit(1);
}
$token = $json['data']['token'];
$user = $json['data']['user'] ?? null;
echo "TOKEN:" . $token . "\n";
if ($user) {
    echo "USER_JSON:" . json_encode($user) . "\n";
}
