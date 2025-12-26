<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Get category ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: product_categories.php');
    exit();
}

$categoryId = (int)$_GET['id'];

// Fetch existing category
$stmt = $pdo->prepare("SELECT * FROM product_categories WHERE id = :id");
$stmt->execute([':id' => $categoryId]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    // Category not found
    $_SESSION['success_message'] = "Category not found!";
    header('Location: product_categories.php');
    exit();
}

// Initialize variables with existing data
$title = $category['title'];
$slug = $category['slug'];
$intro = $category['intro'];
$meta_description = $category['meta_description'];
$currentImage = $category['image'];

$error = '';
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $intro = trim($_POST['intro']);
    $meta_description = trim($_POST['meta_description']);

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmp, $uploadFile)) {
            // Delete old image if exists
            if (!empty($currentImage) && file_exists($uploadDir . $currentImage)) {
                unlink($uploadDir . $currentImage);
            }
            $currentImage = $imageName;
        } else {
            $error = "Failed to upload new image.";
        }
    }

    if (!$error) {
        // Update category in database
        $sql = "UPDATE product_categories 
                SET title = :title, slug = :slug, intro = :intro, meta_description = :meta_description, image = :image
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':intro' => $intro,
            ':meta_description' => $meta_description,
            ':image' => $currentImage,
            ':id' => $categoryId
        ]);

        if ($result) {
            $_SESSION['success_message'] = "Category updated successfully!";
            header('Location: product_categories.php');
            exit();
        } else {
            $error = "Failed to update category.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Category</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .sidebar { width:220px; background:#1e293b; color:#fff; padding:20px; margin-right:20px; }
        .admin-content { flex:1; padding:20px; background:#f8fafc; margin-left: 250px; }
        .form-group { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-weight:bold; }
        input[type="text"], textarea, input[type="file"] {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
        }
        button { padding:10px 15px; background:#2563eb; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        button:hover { background:#1d4ed8; }
        .message { padding:10px; margin-bottom:15px; border-radius:4px; }
        .success { background:#d1fae5; color:#065f46; }
        .error { background:#fee2e2; color:#b91c1c; }
        img { margin-top:10px; border-radius:4px; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h2>Edit Product Category</h2>

        <?php if ($message): ?>
            <div class="message success"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($slug) ?>" required>
            </div>
            <div class="form-group">
                <label>Intro</label>
                <textarea name="intro"><?= htmlspecialchars($intro) ?></textarea>
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description"><?= htmlspecialchars($meta_description) ?></textarea>
            </div>
            <div class="form-group">
                <label>Current Image</label>
                <?php if ($currentImage): ?>
                    <img src="../uploads/<?= htmlspecialchars($currentImage) ?>" alt="Category Image" width="120">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Change Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit">Update Category</button>
        </form>
    </div>
</div>
</body>
</html>
