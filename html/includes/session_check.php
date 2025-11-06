<?php
// Session initialization and helpers for access control and flash messages

// Load config for base URL and timeout settings
require_once __DIR__ . '/../../config.php';

// Configure session cookie params (best-effort; must be called before session_start)
if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Default timeout 30 minutes if not set from config.php
if (!isset($SESSION_TIMEOUT) || !is_int($SESSION_TIMEOUT)) {
    $SESSION_TIMEOUT = 1800; // 30 minutes
}

// Enforce session timeout and update last activity
function enforce_session_timeout(int $timeoutSeconds) {
    if (!empty($_SESSION['user_id'])) {
        $now = time();
        $last = isset($_SESSION['last_activity']) ? (int)$_SESSION['last_activity'] : 0;
        if ($last > 0 && ($now - $last) > $timeoutSeconds) {
            // Timeout: clear session and set flash message
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();
            session_start(); // start fresh to carry flash
            $_SESSION['flash_error'] = 'Your session has timed out. Please log in again.';
        } else {
            // Update activity timestamp
            $_SESSION['last_activity'] = $now;
        }
    }
}

enforce_session_timeout($SESSION_TIMEOUT);

// Flash helpers
function set_flash(string $key, string $message): void { $_SESSION[$key] = $message; }
function get_flash(string $key): ?string {
    if (!isset($_SESSION[$key])) return null;
    $msg = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $msg;
}

// Require login guard
function require_login(string $redirectUrl): void {
    if (empty($_SESSION['user_id'])) {
        set_flash('flash_error', 'Please log in to continue.');
        header('Location: ' . $redirectUrl);
        exit();
    }
}
