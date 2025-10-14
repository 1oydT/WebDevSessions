<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT name FROM genres ORDER BY name ASC");
    $genres = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'success' => true,
        'genres' => $genres
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
