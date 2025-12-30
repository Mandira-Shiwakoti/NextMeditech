<?php
session_start();
require_once 'classes/connect-db.php'; // Your DB connection file

// Check if slug is provided
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
} else {
    die("Product not found.");
}

$slug = $_GET['slug'];

// Fetch product category from the database
$stmt = $pdo->prepare("SELECT * FROM product_categories WHERE slug = :slug LIMIT 1");
$stmt->execute(['slug' => $slug]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['title']); ?> - Product Details</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Your CSS -->
</head>
<body>

<div class="product-details-area pt-90 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-image">
                    <img src="uploads/<?php echo $product['image']; ?>?v=<?php echo date('ymdhis'); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-info">
                    <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($product['intro'])); ?></p>
                    <!-- Add more fields if you have -->
                    <p><strong>Slug:</strong> <?php echo htmlspecialchars($product['slug']); ?></p>
                    <!-- Example: Add a back button -->
                    <a href="our-products.php" class="btn btn-primary">Back to Products</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
