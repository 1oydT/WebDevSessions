<?php
require_once __DIR__ . '/../db.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Retrieve email and password from the request body
$email = $_POST['login-email'] ?? '';
$password = $_POST['login-password'] ?? '';

// Validate required inputs (backend-side safeguard)
if (empty($email) || empty($password)) {
    // Return an error response if either email or password is missing
    echo json_encode(['success' => false, 'message' => 'Missing email or password']);
    exit;
}

try {
    // Look up the user by email using a prepared statement to prevent SQL Injection
    $stmt = $pdo->prepare("SELECT id, full_name, password_hash FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // Verify the password using the password_verify function
    if ($user && password_verify($password, $user['password_hash'])) {
        // Start a secure session and establish login state
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['last_activity'] = time();

        // Return success JSON payload consumed by frontend
        echo json_encode([
            'success' => true,
            'name' => $user['full_name'],
            'id' => $user['id']
        ]);
    } else {
        // Invalid credentials
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
    }
} catch (PDOException $e) {
    // Consistent error response on DB failure
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
