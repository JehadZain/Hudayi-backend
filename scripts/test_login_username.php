<?php
// Simple test: POST login with username/password JSON and print response
$url = 'http://192.168.17.124:8000/api/app/v1/login';
$data = json_encode(['username' => 'mhd@.com', 'password' => 'org_admin']);
$opts = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $data,
        'ignore_errors' => true,
    ],
];
$context = stream_context_create($opts);
$res = @file_get_contents($url, false, $context);
if ($res === false) {
    echo "REQUEST FAILED\n";
    var_dump($http_response_header ?? null);
    exit(1);
}
echo "HTTP RESPONSE HEADERS:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $h) echo $h . "\n";
}
echo "\nBODY:\n";
echo $res . "\n";
