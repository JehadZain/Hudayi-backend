<?php
// Simple endpoint tester for the local environment
function curl_request($url, $method = 'GET', $body = null, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $finalHeaders = [];
    foreach ($headers as $h) {
        $finalHeaders[] = $h;
    }
    if (!empty($finalHeaders)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $finalHeaders);
    }
    // include response headers in output
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $res = curl_exec($ch);
    if ($res === false) {
        $err = curl_error($ch);
        curl_close($ch);
        return ['error' => $err];
    }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($res, 0, $header_size);
    $body = substr($res, $header_size);
    curl_close($ch);
    return ['code' => $code, 'header' => $header, 'body' => $body];
}

$base = 'http://192.168.17.124:8000';
$loginUrl = $base . '/api/app/v1/login';
$loginPayload = json_encode(['email' => 'org_admin', 'password' => 'org_admin']);

echo "POST $loginUrl\n";
$loginRes = curl_request($loginUrl, 'POST', $loginPayload, ['Content-Type: application/json']);
if (isset($loginRes['error'])) {
    echo "LOGIN ERROR: " . $loginRes['error'] . PHP_EOL;
    exit(1);
}
echo "LOGIN HTTP CODE: " . $loginRes['code'] . PHP_EOL;
echo "LOGIN BODY (first 2000 chars):\n" . substr($loginRes['body'], 0, 2000) . PHP_EOL . PHP_EOL;

$token = null;
$decoded = json_decode($loginRes['body'], true);
if (is_array($decoded) && isset($decoded['data']['token'])) {
    $token = $decoded['data']['token'];
    echo "EXTRACTED TOKEN LENGTH: " . strlen($token) . PHP_EOL . PHP_EOL;
} else {
    echo "No token found in login response\n\n";
}

if ($token) {
    $adminsUrl = $base . '/api/app/v1/admins';
    echo "GET $adminsUrl (with Bearer token)\n";
    $adminsRes = curl_request($adminsUrl, 'GET', null, ['Authorization: Bearer ' . $token]);
    if (isset($adminsRes['error'])) {
        echo "ADMINS ERROR: " . $adminsRes['error'] . PHP_EOL;
    } else {
        echo "ADMINS HTTP CODE: " . $adminsRes['code'] . PHP_EOL;
        echo "ADMINS BODY (first 2000 chars):\n" . substr($adminsRes['body'], 0, 2000) . PHP_EOL . PHP_EOL;
    }
}

$dashboardUrls = [
    'http://localhost:3034/dashboard/analytics/',
    'http://127.0.0.1:3034/dashboard/analytics/',
    'http://192.168.17.124:3034/dashboard/analytics/',
];

foreach ($dashboardUrls as $u) {
    echo "FETCH $u\n";
    $r = curl_request($u, 'GET', null, []);
    if (isset($r['error'])) {
        echo "FETCH ERROR: " . $r['error'] . PHP_EOL . PHP_EOL;
        continue;
    }
    echo "HTTP CODE: " . $r['code'] . PHP_EOL;
    echo "RESPONSE HEADERS:\n" . $r['header'] . PHP_EOL;
    $len = strlen($r['body']);
    echo "BODY LENGTH: $len\n";
    echo "BODY (first 1000 chars):\n" . substr($r['body'], 0, 1000) . PHP_EOL . PHP_EOL;
}

echo "Done.\n";
