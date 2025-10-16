<?php
require __DIR__ . '/../vendor/autoload.php';
$client = new \GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
try {
    $res = $client->post('/api/app/v1/login', [
        'json' => ['username' => 'org_admin', 'password' => 'org_admin'],
        'http_errors' => false,
        'timeout' => 10,
    ]);
    echo "Status: " . $res->getStatusCode() . "\n";
    echo "Body: " . $res->getBody() . "\n";
    foreach ($res->getHeaders() as $name => $values) {
        echo $name . ": " . implode(', ', $values) . "\n";
    }
} catch (Exception $e) {
    echo "Exception: " . get_class($e) . ": " . $e->getMessage() . "\n";
}
