<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

$message = '';
$error = '';

/* ---------------- DELETE CATEGORY ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];

    // Get image name to delete file
    $stmt = $pdo->prepare("SELECT image FROM equipmentcat WHERE id = ?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete DB record
    $stmt = $pdo->prepare("DELETE FROM equipmentcat WHERE id = ?");
    $stmt->execute([$id]);

    // Remove image file
    if ($cat && !empty($cat['image'])) {
        $path = '../uploads/' . $cat['image'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $message = "Equipment category deleted successfully.";
}

/* ---------------- FETCH ALL CATEGORIES ---------------- */
$stmt = $pdo->prepare("SELECT id, cat_name, sort_order, detail, image FROM equipmentcat ORDER BY sort_order ASC, id DESC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Equipment Categories</title>

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
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
        }

        .message {
            background: #d4edda;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid green;
        }

        .error {
            background: #f8d7da;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid red;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
            margin: 3px; /* margin for all buttons */
        }

        .btn-primary { background: #007bff; color: white; }
        .btn-danger { background: #dc3545; color: white; }

        .add-btn-container {
            margin-bottom: 15px; /* margin below + Add New */
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            text-align: left;
        }

        .category-image {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
        }

        /* ----------- Description scroll box ------------ */
        .desc-box {
            max-width: 450px;      /* keep column size normal */
            max-height: 120px;     /* show only part of the text */
            overflow-y: auto;      /* scrollbar */
            padding: 8px;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 6px;
            white-space: normal;
        }
    </style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <h1>Equipment Categories</h1>

    <!-- message -->
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add button with margin -->
   

    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Sort</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['id'] ?></td>

            <td>
                <?php if ($cat['image']): ?>
                    <img src="../uploads/<?= $cat['image'] ?>" class="category-image">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($cat['cat_name']) ?></td>
            <td><?= $cat['sort_order'] ?></td>

            <!-- Scrollable truncated description -->
            

            <td>
                <a href="/admin/equipment-category-form.php?action=edit&id=<?= $cat['id'] ?>" 
                   class="btn btn-primary">Edit</a>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= $cat['id'] ?>">
                    <button class="btn btn-danger" onclick="return confirm('Delete this category?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
