<?php
require_once __DIR__ . '/../db.php';

// This endpoint handles user registration using secure practices
header('Content-Type: application/json');

// Retrieve and trim incoming fields
$fullName = trim($_POST['signup-name'] ?? '');
$email = trim($_POST['signup-email'] ?? '');
$address = trim($_POST['signup-address'] ?? '');
$contact = trim($_POST['signup-contact'] ?? '');
$password = trim($_POST['signup-password'] ?? '');
$confirm = trim($_POST['signup-confirm'] ?? '');

// Backend validations: required fields
if (empty($fullName) || empty($email) || empty($address) || empty($contact) || empty($password) || empty($confirm)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit;
}

// Enforce minimum password length
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters."]);
    exit;
}

// Ensure passwords match
if ($password !== $confirm) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}

// Hash the password using PASSWORD_DEFAULT (argon2/bcrypt depending on PHP)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


try {
    // Check if email already exists (prepared statement prevents SQL injection)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "The given email already registered."]);
        exit;
    }

    // Insert the new user row securely using named placeholders
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, address, contact_number, password_hash) VALUES (:full_name, :email, :address, :contact_number, :password_hash)");
    $stmt->execute([
        ':full_name' => $fullName,
        ':email' => $email,
        ':address' => $address,
        ':contact_number' => $contact,
        ':password_hash' => $hashedPassword
    ]); 

    echo json_encode(["success" => true, "message" => "Account created successfully."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
