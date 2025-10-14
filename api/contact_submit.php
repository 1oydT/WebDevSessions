<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$name = trim($_POST['contact-name'] ?? '');
$email = trim($_POST['contact-email'] ?? '');
$message = trim($_POST['contact-message'] ?? '');

// additional validation for backend to db to not fk up the table :DDD
if ($name === '' || $email === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// insert it in my db stepbro
try {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message
    ]);

    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
