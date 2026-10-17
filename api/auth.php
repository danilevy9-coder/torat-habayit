<?php
/**
 * Server-side admin authentication for the Torat Habayit blog dashboard.
 *
 * POST {action: "login", password: "..."}  -> sets a session cookie
 * POST {action: "logout"}                  -> destroys the session
 * GET  ?action=check                       -> {authenticated: true|false}
 */

require_once __DIR__ . '/session.php';
th_session_start();

header('Content-Type: application/json');

$config = require __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && ($_GET['action'] ?? '') === 'check') {
    echo json_encode(['authenticated' => !empty($_SESSION['th_admin'])]);
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'logout') {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'login') {
    // Simple per-IP throttle: max 10 failed attempts per 15 minutes.
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $throttleFile = sys_get_temp_dir() . '/th_login_' . md5($ip);
    $attempts = [];
    if (is_file($throttleFile)) {
        $attempts = array_filter(
            (array) json_decode((string) file_get_contents($throttleFile), true),
            function ($t) { return $t > time() - 900; }
        );
    }
    if (count($attempts) >= 10) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many attempts. Try again in 15 minutes.']);
        exit;
    }

    $password = (string) ($input['password'] ?? '');
    if ($password !== '' && password_verify($password, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['th_admin'] = true;
        if (is_file($throttleFile)) unlink($throttleFile);
        echo json_encode(['success' => true]);
    } else {
        $attempts[] = time();
        file_put_contents($throttleFile, json_encode(array_values($attempts)));
        sleep(1); // slow down brute force
        http_response_code(401);
        echo json_encode(['error' => 'Incorrect password']);
    }
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
