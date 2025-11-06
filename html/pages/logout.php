<?php
// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Unset all session variables to remove user state
$_SESSION = [];

// Delete the session cookie to prevent reuse of old session ID
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}

// Destroy the session on the server
session_destroy();

// Redirect user to the home page (or login page if you prefer)
header('Location: home.php');
exit();
