<?php
// Shared session bootstrap with hardened cookie settings.

function th_session_start() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? null) == 443;
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('TH_SESSION');
    session_start();
}

function th_is_admin() {
    return !empty($_SESSION['th_admin']);
}
