<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php'; // for $base
global $base;

header('Content-Type: application/json');

try {
    // ✅ Fetch products
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($products as $product) {
        $productId = $product['id'];

        // Fetch genres for each product
        $genreStmt = $pdo->prepare("
            SELECT g.name 
            FROM genres g
            INNER JOIN product_genres pg ON g.id = pg.genre_id
            WHERE pg.product_id = ?
        ");
        $genreStmt->execute([$productId]);
        $genres = $genreStmt->fetchAll(PDO::FETCH_COLUMN); // returns array of genre names

        // Fetch images for each product
        $imageStmt = $pdo->prepare("
            SELECT image_path 
            FROM product_images 
            WHERE product_id = ?
        ");
        $imageStmt->execute([$productId]);
        $images = $imageStmt->fetchAll(PDO::FETCH_COLUMN);

        // Prepend base URL to image paths
        $fullImagePaths = array_map(function($img) use ($base) {
            return $base . $img;
        }, $images);

        // Assemble final product structure
        $result[] = [
            'id' => $product['id'],
            'slug' => $product['slug'],
            'name' => $product['name'],
            'price' => $product['price'],
            'description' => $product['description'],
            'genres' => $genres,
            'images' => $fullImagePaths
        ];
    }

    echo json_encode([
        'success' => true,
        'products' => $result
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
