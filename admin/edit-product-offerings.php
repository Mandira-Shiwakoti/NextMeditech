<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Get offering ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view-product-offerings.php');
    exit();
}

$offeringId = (int)$_GET['id'];

// Fetch existing product offering
$stmt = $pdo->prepare("SELECT * FROM product_offerings WHERE id = :id");
$stmt->execute([':id' => $offeringId]);
$offering = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offering) {
    $_SESSION['success_message'] = "Product offering not found!";
    header('Location: view-product-offerings.php');
    exit();
}

// Initialize variables
$name = $offering['name'];
$display_order = $offering['display_order'];
$category_id = $offering['category_id'];
$currentImage = $offering['image'];

$error = '';
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $display_order = trim($_POST['display_order']);

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
        // Update offering in database
        $sql = "UPDATE product_offerings 
                SET name = :name, display_order = :display_order, image = :image
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':name' => $name,
            ':display_order' => $display_order,
            ':image' => $currentImage,
            ':id' => $offeringId
        ]);

        if ($result) {
            $_SESSION['success_message'] = "Product offering updated successfully!";
            header("Location: view-product-offerings.php?category_id=$category_id");
            exit();
        } else {
            $error = "Failed to update product offering.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product Offering</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<style>
body { margin:0; font-family: Arial, sans-serif; }
.admin-wrapper { display:flex; min-height:100vh; }
.admin-content { flex:1; padding:20px; margin-left:250px; background:#f8fafc; }
.form-group { margin-bottom:15px; }
label { display:block; margin-bottom:5px; font-weight:bold; }
input[type="text"], input[type="number"], input[type="file"] {
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
        <h2>Edit Product Offering</h2>

        <?php if ($message): ?>
            <div class="message success"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
            </div>
            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" value="<?= htmlspecialchars($display_order) ?>" required>
            </div>
            <div class="form-group">
                <label>Current Image</label>
                <?php if ($currentImage): ?>
                    <img src="../uploads/<?= htmlspecialchars($currentImage) ?>" alt="Offering Image" width="120">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Change Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit">Update Offering</button>
        </form>
    </div>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
