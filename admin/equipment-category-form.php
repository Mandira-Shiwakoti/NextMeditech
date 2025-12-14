<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

$action = $_GET['action'] ?? 'add';
$id = $_GET['id'] ?? null;

$cat_name = "";
$sort_order = 0;
$detail = "";
$current_image = "";

/* ---------------- LOAD DATA FOR EDIT ---------------- */
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM equipmentcat WHERE id = ?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cat) {
        $cat_name = $cat['cat_name'];
        $sort_order = $cat['sort_order'];
        $detail = $cat['detail'];
        $current_image = $cat['image'];
    }
}

/* ---------------- HANDLE FORM SUBMIT ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['cat_name']);
    $sort_order = (int)$_POST['sort_order'];
    $detail = trim($_POST['detail']);
    $current_image = $_POST['current_image'];

    $image = $current_image;

    /* Handle image upload */
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid() . "." . $ext;

        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);

        if ($current_image && file_exists("../uploads/" . $current_image)) {
            unlink("../uploads/" . $current_image);
        }
    }

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO equipmentcat (cat_name, sort_order, detail, image) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$cat_name, $sort_order, $detail, $image]);
    }

    if ($action === 'edit') {
        $stmt = $pdo->prepare("UPDATE equipmentcat SET cat_name=?, sort_order=?, detail=?, image=? 
                               WHERE id=?");
        $stmt->execute([$cat_name, $sort_order, $detail, $image, $id]);
    }

    header("Location: /admin/equipment.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= ucfirst($action) ?> Equipment Category</title>

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

        body {
            display: flex;
            background: #f5f7fb;
            margin: 0;
            font-family: Arial;
        }
        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
        }
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 600px;
        }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        input, textarea {
            width: 100%; padding: 10px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }
        .btn-primary { background: #007bff; color: white; }

    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <h1><?= ucfirst($action) ?> Equipment Category</h1>

    <div class="form-container">
        <form method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="cat_name" required 
                       value="<?= htmlspecialchars($cat_name) ?>">
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?= $sort_order ?>">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="detail"><?= htmlspecialchars($detail) ?></textarea>
            </div>

            <div class="form-group">
                <label>Image</label>
                <?php if ($current_image): ?>
                    <img src="../uploads/<?= $current_image ?>" width="120" 
                         style="display:block;margin-bottom:10px;">
                <?php endif; ?>

                <input type="file" name="image">
                <input type="hidden" name="current_image" value="<?= $current_image ?>">
            </div>

            <button class="btn btn-primary">Save</button>
        </form>

        <a href="/admin/equipment.php">Back to Equipment list</a>
    </div>

</div>

</body>
</html>
