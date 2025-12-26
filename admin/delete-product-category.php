<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Check if id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $categoryId = (int)$_GET['id'];

    // Optional: first fetch the category to delete its image file
    $stmt = $pdo->prepare("SELECT image FROM product_categories WHERE id = :id");
    $stmt->execute([':id' => $categoryId]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        // Delete the image file if it exists
        if (!empty($category['image']) && file_exists('../uploads/' . $category['image'])) {
            unlink('../uploads/' . $category['image']);
        }

        // Delete the category from database
        $stmt = $pdo->prepare("DELETE FROM product_categories WHERE id = :id");
        $stmt->execute([':id' => $categoryId]);

        // Set a success message in session
        $_SESSION['message'] = "Category deleted successfully!";
    } else {
        $_SESSION['message'] = "Category not found!";
    }
}

// Redirect back to the categories page
header('Location: product_categories.php');
exit();
