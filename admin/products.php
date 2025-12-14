<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Fetch all product images
try {
    $stmt = $pdo->prepare("SELECT id, cat_id, image FROM product_images ORDER BY id DESC");
    $stmt->execute();
    $product_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $product_images = [];
    $error = "Error fetching product images: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Images</title>
    <link rel="stylesheet" href="sidebar.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .add-image-btn {
    display: inline-block;
    margin: 20px 0; /* top and bottom margin */
    padding: 10px 18px;
    background: #007bff;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}

.add-image-btn:hover {
    background: #0069d9;
}

        body {
            background: #f5f7fb; 
            display: flex;
        }
        .btn-add-image {
   n      margin: 20px 0;   /* Top = 20px, Bottom = 20px */
         display: inline-block;
}

         .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #4b545c;
            text-align: center;
        }

        .sidebar-header h2 {
            color: #fff;
            font-size: 1.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            padding: 0;
        }

        .sidebar-menu a {
            display: block;
            color: #ccc;
            text-decoration: none;
            padding: 12px 20px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background: #495057;
            color: white;
            border-left: 3px solid #007bff;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f0f0f0;
            text-align: left;
        }
        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .image-preview {
            width: 80px; 
            height: 60px; 
            object-fit: cover; 
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h1>Manage Product Images</h1>

    <a href="/admin/add-product-image.php" class="add-image-btn">+ Add New Image</a>
    <br><br>


    <?php if (!empty($error)): ?>
       <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category ID</th>
                <th>Image</th>
                <th>Image Path</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($product_images) > 0): ?>
                <?php foreach ($product_images as $img): ?>
                    <tr>
                        <td><?= htmlspecialchars($img['id']) ?></td>
                        <td><?= htmlspecialchars($img['cat_id']) ?></td>
                        <td>
                            <img src="../<?= htmlspecialchars($img['image']) ?>" class="image-preview">
                        </td>
                        <td><?= htmlspecialchars($img['image']) ?></td>
                        <td>
                            <a href="/admin/edit-product-image.php?id=<?= $img['id'] ?>" class="btn btn-success">Edit</a>
                            <a href="delete-product-image.php?id=<?= $img['id'] ?>" class="btn btn-danger"
                               onclick="return confirm('Delete this image?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding:20px;">No images found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
