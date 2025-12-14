<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

$message = $error = '';

// Fetch unique category IDs from tbl_esubcat
$catIds = $pdo->query("SELECT DISTINCT cat_id FROM tbl_esubcat ORDER BY cat_id ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = trim($_POST['cat_id']);

    if (empty($cat_id)) {
        $error = "Category ID is required.";
    } else if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Please select an image.";
    } else {
        $uploadDir = "../uploads/product_images/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $savePath = "uploads/product_images/" . $fileName;

            $stmt = $pdo->prepare("INSERT INTO product_images (cat_id, image) VALUES (?, ?)");
            $stmt->execute([$cat_id, $savePath]);

            header("Location: products.php?msg=added");
            exit();
        } else {
            $error = "Image upload failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product Image</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        .content-wrapper {
            margin-left: 270px;
            padding: 30px;
        }

        form {
            background: #f8f9fa;
            padding: 20px;
            width: 450px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0069d9;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <h2>Add New Product Image</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Category ID:</label><br>
        <select name="cat_id" required>
            <option value="">-- Select Category ID --</option>
            <?php foreach ($catIds as $c): ?>
                <option value="<?= $c['cat_id'] ?>">
                    <?= $c['cat_id'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Select Image:</label><br>
        <input type="file" name="image" required><br><br>

        <button type="submit">Add Image</button>
    </form>

    <br>
    <a href="/admin/products.php">⬅ Back to Product List</a>
</div>

</body>
</html>
