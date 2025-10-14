<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$email = $_POST['login-email'] ?? '';
$password = $_POST['login-password'] ?? '';

// additional validation for backend to db to not fk up the table :DDD
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Missing email or password']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, full_name, password_hash FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Optionally start a session here
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        echo json_encode([
            'success' => true,
            'name' => $user['full_name'],
            'id' => $user['id']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
