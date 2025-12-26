<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Check if id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = (int)$_GET['id'];

    // Optional: first fetch the product to delete its image file
    $stmt = $pdo->prepare("SELECT image,category_id FROM product_offerings WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $categoryId = $product['category_id']; 
        // Delete the image file if it exists
        if (!empty($product['image']) && file_exists('../uploads/' . $product['image'])) {
            unlink('../uploads/' . $product['image']);
        }

        // Delete the product from database
        $stmt = $pdo->prepare("DELETE FROM product_offerings WHERE id = :id");
        $stmt->execute([':id' => $productId]);

        // Set a success message in session
        $_SESSION['message'] = "Product offering deleted successfully!";
    } else {
        $_SESSION['message'] = "Product offering not found!";
    }
}

// Redirect back to the product offerings page
header('Location: view-product-offerings.php?category_id=' . $categoryId);
exit();
