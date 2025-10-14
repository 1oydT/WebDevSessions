<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../config.php';  // For $base if needed

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = trim($_POST['slug']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $genresInput = trim($_POST['genres']);

    // Directory to store images
    $uploadDir = __DIR__ . '/../../public/assets/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // creates the directory if it doesn't exist
    }
    $uploadedImages = [];

    try {
        // Basic validation
        if (!$slug || !$name || !$price || !$description || !$genresInput) {
            throw new Exception("Please fill out all required fields.");
        }

        // ✅ Insert product
        $stmt = $pdo->prepare("INSERT INTO products (slug, name, price, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$slug, $name, $price, $description]);
        $productId = $pdo->lastInsertId();

        // ✅ Handle genres (comma separated)
        $genresArray = array_map('trim', explode(',', $genresInput));
        $genresArray = array_filter($genresArray, fn($g) => $g !== '');

        foreach ($genresArray as $genreName) {
            // Check if genre exists
            $checkStmt = $pdo->prepare("SELECT id FROM genres WHERE name = ?");
            $checkStmt->execute([$genreName]);
            $genreId = $checkStmt->fetchColumn();

            // If not, insert new genre
            if (!$genreId) {
                $insertGenre = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
                $insertGenre->execute([$genreName]);
                $genreId = $pdo->lastInsertId();
            }

            // Link product and genre
            $linkStmt = $pdo->prepare("INSERT INTO product_genres (product_id, genre_id) VALUES (?, ?)");
            $linkStmt->execute([$productId, $genreId]);
        }

        // Handle multiple file uploads
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $fileName) {
                $tmpName = $_FILES['images']['tmp_name'][$key];

                // Sanitize original filename
                $baseName = pathinfo($fileName, PATHINFO_FILENAME);
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Replace spaces and special characters with underscores
                $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);

                // Final filename
                $finalName = $safeBaseName . '.' . $ext;
                $destination = $uploadDir . $finalName;

                // If file already exists, append a number
                $counter = 1;
                while (file_exists($destination)) {
                    $finalName = $safeBaseName . '_' . $counter . '.' . $ext;
                    $destination = $uploadDir . $finalName;
                    $counter++;
                }

                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = '/public/assets/' . $finalName;
                    $imgStmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $imgStmt->execute([$productId, $imagePath]);
                    $uploadedImages[] = $imagePath;
                }
            }
        }

        $message = "✅ Product successfully added!";
    } catch (Exception $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="<?= $base ?>/public/dist/styles.css">
    <link rel="stylesheet" href="<?= $base ?>/public/dist/bootstrap-icons/bootstrap-icons.css">
</head>

<body class="bg-dark text-white py-4">

    <div class="container">
        <h1 class="mb-4">🕹 Add New Product</h1>

        <?php if ($message): ?>
            <div class="alert <?= str_starts_with($message, '✅') ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="bg-secondary p-4 rounded">
            <div class="mb-3">
                <label for="slug" class="form-label">Slug (unique ID)</label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="e.g. gta" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Grand Theft Auto"
                    required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" placeholder="e.g. 350"
                    required>
            </div>

            <div class="mb-3">
                <label for="genres" class="form-label">Genre</label>
                <input type="text" name="genres" id="genres" class="form-control"
                    placeholder="e.g. Action, RPG, Open World" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Upload Images</label>
                <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                <small class="text-light">You can select multiple files.</small>
            </div>

            <button type="submit" class="btn btn-success">Add Product</button>
        </form>
    </div>

    <script src="<?= $base ?>/public/dist/jquery/jquery.min.js"></script>
    <script src="<?= $base ?>/public/dist/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>