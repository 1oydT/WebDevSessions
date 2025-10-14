<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$fullName = trim($_POST['signup-name'] ?? '');
$email = trim($_POST['signup-email'] ?? '');
$address = trim($_POST['signup-address'] ?? '');
$contact = trim($_POST['signup-contact'] ?? '');
$password = trim($_POST['signup-password'] ?? '');
$confirm = trim($_POST['signup-confirm'] ?? '');

// additional validation for backend to db to not fk up the table :DDD
if (empty($fullName) || empty($email) || empty($address) || empty($contact) || empty($password) || empty($confirm)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format."]);
    exit;
}

if ($password !== $confirm) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}


// hash the password NOOOO wAG MUNA 
//devnote:need pala, for password_verify SADDDDDDD
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "The given email already registered."]);
        exit;
    }

    // insert user ( ͡° ͜ʖ ͡°) in db
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
