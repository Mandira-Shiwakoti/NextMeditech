<?php
session_start();

// Check admin login
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

/* Validate category_id */
if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    die('Category ID not provided.');
}

$category_id = (int) $_GET['category_id'];

// Initialize variables
$name = '';
$display_order = 0;
$message = '';
$error = '';

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $display_order = (int) $_POST['display_order'];

    /* Image upload */
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . $imageName;

        if (!move_uploaded_file($imageTmp, $uploadFile)) {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        /* Insert offering into database */
        $sql = "
            INSERT INTO product_offerings 
            (category_id, name, display_order, image)
            VALUES 
            (:category_id, :name, :display_order, :image)
        ";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':category_id' => $category_id,
            ':name' => $name,
            ':display_order' => $display_order,
            ':image' => $imageName
        ]);

        if ($result) {
            $_SESSION['message'] = "Product offering added successfully.";
            header("Location: view-product-offerings.php?category_id=$category_id");
            exit();
        } else {
            $error = "Failed to add product offering.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product Offering</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .admin-content { flex:1; padding:20px; background:#f8fafc; margin-left:250px; }
        .form-group { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-weight:bold; }
        input[type="text"], input[type="number"], input[type="file"] {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
        }
        button {
            padding:10px 15px;
            background:#2563eb;
            color:#fff;
            border:none;
            border-radius:4px;
            cursor:pointer;
        }
        button:hover { background:#1d4ed8; }
        .message { padding:10px; margin-bottom:15px; border-radius:4px; }
        .success { background:#d1fae5; color:#065f46; }
        .error { background:#fee2e2; color:#b91c1c; }
    </style>
</head>

<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-content">
        <h2>Add Product Offering</h2>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <!-- hidden parent category -->
            <input type="hidden" name="category_id" value="<?= $category_id; ?>">

            <div class="form-group">
                <label>Offering Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" value="<?= htmlspecialchars($display_order); ?>" required>
            </div>

            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <button type="submit">Add Offering</button>
        </form>
    </div>
</div>
</body>
</html>
