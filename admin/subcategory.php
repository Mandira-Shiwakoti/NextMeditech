<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Initialize messages
$message = '';
$error = '';

// Handle Add / Edit / Delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD NEW SUBCATEGORY
    if ($action === 'add') {
        $cat_id = trim($_POST['cat_id']);
        $subcat_name = trim($_POST['subcat_name']);
        $detail = trim($_POST['detail']);
        $image = null;

        // Image upload handling
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = '../uploads/' . $image_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image = $image_name;
            } else {
                $error = 'Failed to upload image.';
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO tbl_esubcat (cat_id, subcat_name, detail, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$cat_id, $subcat_name, $detail, $image]);
            $message = 'Subcategory added successfully.';
        }
    }

    // EDIT EXISTING SUBCATEGORY
    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        $cat_id = trim($_POST['cat_id']);
        $subcat_name = trim($_POST['subcat_name']);
        $detail = trim($_POST['detail']);
        $image = $_POST['existing_image'] ?? null;

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = '../uploads/' . $image_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image = $image_name;
            } else {
                $error = 'Failed to upload image.';
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("UPDATE tbl_esubcat SET cat_id=?, subcat_name=?, detail=?, image=? WHERE id=?");
            $stmt->execute([$cat_id, $subcat_name, $detail, $image, $id]);
            $message = 'Subcategory updated successfully.';
        }
    }

    // DELETE SUBCATEGORY
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tbl_esubcat WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Subcategory deleted successfully.';
    }
}

// Fetch all subcategories
$stmt = $pdo->prepare("SELECT * FROM tbl_esubcat ORDER BY id DESC");
$stmt->execute();
$subcats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subcategories | Admin Panel</title>
    <link rel="stylesheet" href="sidebar.css">
    <style>
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
        /* Keep the same styles as your user.php for consistency */
        * {margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
        .sidebar {width:250px;background:#343a40;color:white;height:100vh;position:fixed;padding:20px 0;}
        .main-content {flex:1;padding:30px;margin-left:250px;background:#f5f7fb;min-height:100vh;}
        .card {background:white;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.05);padding:25px;margin-bottom:25px;}
        .card-header {margin-bottom:15px;border-bottom:1px solid #eef2f7;}
        .card-title {font-size:18px;font-weight:600;color:#2c3e50;}
        input,select,textarea {width:100%;padding:10px;border:1px solid #d1d9e6;border-radius:6px;font-size:14px;}
        input:focus,select:focus,textarea:focus {outline:none;border-color:#4a90e2;box-shadow:0 0 0 3px rgba(74,144,226,0.1);}
        .btn {padding:10px 16px;border:none;border-radius:6px;cursor:pointer;font-weight:500;}
        .btn-primary {background:#4a90e2;color:white;}
        .btn-danger {background:#e74c3c;color:white;}
        .btn-success {background:#2ecc71;color:white;}
        .table-container {overflow-x:auto;}
        table {width:100%;border-collapse:collapse;margin-top:10px;}
        th,td {padding:12px 15px;border-bottom:1px solid #eef2f7;}
        th {background:#f8fafc;}
        .message {background:#d4edda;color:#155724;padding:12px;margin-bottom:20px;border-radius:6px;}
        .error {background:#f8d7da;color:#721c24;padding:12px;margin-bottom:20px;border-radius:6px;}
        img.thumb {width:60px;height:60px;object-fit:cover;border-radius:5px;}
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Subcategories</h1>
        </div>

        <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <!-- Add Subcategory Form -->
        <div class="card">
            <div class="card-header"><h2 class="card-title">Add New Subcategory</h2></div>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="form-row" style="display:flex;gap:10px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:180px;">
                        <label>Category ID</label>
                        <input type="number" name="cat_id" required>
                    </div>
                    <div style="flex:1;min-width:180px;">
                        <label>Subcategory Name</label>
                        <input type="text" name="subcat_name" required>
                    </div>
                    <div style="flex:2;min-width:200px;">
                        <label>Details</label>
                        <textarea name="detail" rows="2" required></textarea>
                    </div>
                    <div style="flex:1;min-width:180px;">
                        <label>Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    <div style="display:flex;align-items:flex-end;">
                        <button type="submit" class="btn btn-primary">Add Subcategory</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Subcategory Table -->
        <div class="card">
            <div class="card-header"><h2 class="card-title">Existing Subcategories</h2></div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category ID</th>
                            <th>Subcategory Name</th>
                            <th>Detail</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($subcats) > 0): ?>
                            <?php foreach ($subcats as $row): ?>
                                <tr>
                                    <form method="post" enctype="multipart/form-data">
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><input type="number" name="cat_id" value="<?= htmlspecialchars($row['cat_id']) ?>" required></td>
                                        <td><input type="text" name="subcat_name" value="<?= htmlspecialchars($row['subcat_name']) ?>" required></td>
                                        <td><textarea name="detail" rows="2" required><?= htmlspecialchars($row['detail']) ?></textarea></td>
                                        <td>
                                            <?php if ($row['image']): ?>
                                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="thumb">
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                            <input type="file" name="image" accept="image/*">
                                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($row['image']) ?>">
                                        </td>
                                        <td>
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="action" value="edit" class="btn btn-success btn-sm">Save</button>
                                            <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this subcategory?')">Delete</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;color:#999;">No subcategories found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
