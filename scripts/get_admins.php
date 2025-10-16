<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Foundation\Console\Kernel::class);
$kernel->bootstrap();

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2FwcC92MS9sb2dpbiIsImlhdCI6MTc2MDE3OTI5NiwiZXhwIjoxNzg0MjA5Mjk2LCJuYmYiOjE3NjAxNzkyOTYsImp0aSI6Ikg0Sm9IRUJ2cEZDVFdTUksiLCJzdWIiOiIyIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.Sb1xoxG5t4foPpnG0wewamxYrfV9alUfyS2byPeDkMw';
$client = new \GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
$res = $client->get('/api/app/v1/admins', ['headers' => ['Authorization' => 'Bearer ' . $token], 'http_errors' => false]);
echo "Status: " . $res->getStatusCode() . "\n";
echo "Body: " . $res->getBody() . "\n";
foreach ($res->getHeaders() as $name => $values) {
    echo $name . ': ' . implode(', ', $values) . "\n";
}
