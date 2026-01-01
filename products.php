<?php
session_start();
require_once 'classes/connect-db.php';

// Validate slug
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die("Product not found.");
}

$slug = trim($_GET['slug']);

/* Fetch product category */
$stmt = $pdo->prepare("
    SELECT * 
    FROM product_categories 
    WHERE slug = :slug 
    LIMIT 1
");
$stmt->execute(['slug' => $slug]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

/* Fetch product offerings for this category */
$offerStmt = $pdo->prepare("
    SELECT * 
    FROM product_offerings 
    WHERE category_id = :category_id
    ORDER BY id DESC
");
$offerStmt->execute(['category_id' => $product['id']]);
$offerings = $offerStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['title']); ?> - Product Details</title>
    <?php include 'head.php'; ?>
</head>
<body>

<?php include 'header.php'; ?>

<div class="product-details-area pt-90 pb-70">
    <div class="container">

        <!-- ================= CATEGORY DETAILS ================= -->
        <div class="row align-items-center mb-5">
            <!-- Image -->
            <div class="col-lg-6 text-center">
                <img 
                    src="uploads/<?php echo htmlspecialchars($product['image']); ?>?v=<?php echo time(); ?>" 
                    class="img-fluid rounded shadow"
                    alt="<?php echo htmlspecialchars($product['title']); ?>"
                >
            </div>

            <!-- Details -->
            <div class="col-lg-6">
                <h2 class="mb-3"><?php echo htmlspecialchars($product['title']); ?></h2>

                <p class="mb-4">
                    <?php echo nl2br(htmlspecialchars($product['intro'])); ?>
                </p>

                <a href="our-product.php" class="btn btn-primary">
                    ← Back to Products
                </a>
            </div>
        </div>

        <!-- ================= PRODUCT OFFERINGS ================= -->
        <hr class="mb-5">

        <h3 class="text-center mb-4">
            <?php echo htmlspecialchars($product['title']); ?> Offerings
        </h3>

        <?php if (!empty($offerings)): ?>
            <div class="row">
                <?php foreach ($offerings as $offer): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">

                            <?php if (!empty($offer['image'])): ?>
                                <img 
                                    src="uploads/<?php echo htmlspecialchars($offer['image']); ?>" 
                                    class="card-img-top"
                                    alt="<?php echo htmlspecialchars($offer['name']); ?>"
                                >
                            <?php endif; ?>

                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($offer['name']); ?>
                                </h5>

                                <?php if (!empty($offer['description'])): ?>
                                    <p class="card-text">
                                        <?php echo nl2br(htmlspecialchars($offer['description'])); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">
                No product offerings available for this category.
            </p>
        <?php endif; ?>

    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
