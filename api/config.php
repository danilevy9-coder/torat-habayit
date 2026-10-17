<?php
// Loads the private configuration (api/secrets.php, gitignored).
// If it is missing, fail with a clear message instead of a blank 500.

$secretsFile = __DIR__ . '/secrets.php';
if (!is_file($secretsFile)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Server not configured: api/secrets.php is missing. Copy api/secrets.sample.php, fill it in, and upload it.']);
    exit;
}
return require $secretsFile;
