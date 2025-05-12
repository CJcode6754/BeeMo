<?php
// Clear session data
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Get current session cookie parameters
    $params = session_get_cookie_params();

    // Expire the session cookie properly
    setcookie(
        'PHPSESSID',
        '',
        time() - 3600,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );

header('location: /login');
exit();