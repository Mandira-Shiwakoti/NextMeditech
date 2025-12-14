<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

$id = $_GET['id'] ?? 0;

// Fetch existing image
$stmt = $pdo->prepare("SELECT * FROM product_images WHERE id=?");
$stmt->execute([$id]);
$imageData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$imageData) {
    die("Invalid ID.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = trim($_POST['cat_id']);

    if (empty($cat_id)) {
        $error = "Category ID required.";
    } else {
        $newImagePath = $imageData['image'];

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = "../uploads/product_images/";
            $fileName = uniqid() . "_" . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $newImagePath = "uploads/product_images/" . $fileName;

                // delete old file
                if (file_exists("../" . $imageData['image'])) {
                    unlink("../" . $imageData['image']);
                }
            } else {
                $error = "Image upload failed.";
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("UPDATE product_images SET cat_id=?, image=? WHERE id=?");
            $stmt->execute([$cat_id, $newImagePath, $id]);

            header("Location: products.php?msg=updated");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product Image</title>
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

        /* NEW LAYOUT TO PLACE FORM BESIDE SIDEBAR */
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
    <h2>Edit Product Image</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Category ID:</label><br>
        <input type="number" name="cat_id" value="<?= htmlspecialchars($imageData['cat_id']) ?>" required><br><br>

        <label>Current Image:</label><br>
        <img src="../<?= $imageData['image'] ?>" width="120"><br><br>

        <label>Change Image (optional):</label><br>
        <input type="file" name="image"><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="/admin/products.php">⬅ Back to Product List</a>
</div>

</body>
</html>
