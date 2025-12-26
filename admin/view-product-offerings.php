<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    die('Category ID not provided.');
}

$category_id = (int)$_GET['category_id'];

// Get the category details
$catStmt = $pdo->prepare("SELECT `title` FROM `product_categories` WHERE `id` = ?");
$catStmt->execute([$category_id]);
$category = $catStmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die('Category not found.');
}

// Fetch product offerings for the given category
$offerStmt = $pdo->prepare("SELECT * FROM `product_offerings` WHERE `category_id` = ? ORDER BY `id` DESC");
$offerStmt->execute([$category_id]);
$offerings = $offerStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Offerings</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<style>
    body { margin: 0; font-family: Arial, sans-serif; }
    .admin-wrapper { display: flex; min-height: 100vh; }
    .admin-content { flex: 1; padding: 20px; margin-left: 250px; background: #f8fafc; }
    .admin-table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 10px; }
    .admin-table th, .admin-table td { border: 1px solid #0b0b0b; padding: 10px; text-align: left; }
    .admin-table th { background: #cde1f4; }
    .btn-add { display: inline-block; margin: 10px 0; padding: 8px 12px; background: #3371f6; color: #fff; text-decoration: none; border-radius: 4px; }
    .btn-add:hover { background: #1d4ed8; color: #fff; }
    img { border-radius: 4px; }
    .admin-content a { text-decoration: none; }

    .admin-content button {
        margin-right: 5px;
        padding: 5px 10px;
        background: #aabfebff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h2>Product Offerings in "<?php echo htmlspecialchars($category['title']); ?>"</h2>
        <a href="admin/add-product-offering.php?category_id=<?php echo $category_id; ?>" class="btn-add">➕ Add New Offering</a>

        <table class="admin-table table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Display Order</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($offerings) > 0): ?>
                    <?php foreach ($offerings as $offering): ?>
                        <tr>
                            <td><?php echo $offering['id']; ?></td>
                            <td><?php echo $offering['category_id']; ?></td>
                            <td><?php echo htmlspecialchars($offering['name']); ?></td>
                            <td><?php echo $offering['display_order']; ?></td>
                            <td>
                                <?php if ($offering['image']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($offering['image']); ?>" alt="<?php echo htmlspecialchars($offering['name']); ?>" width="100">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <button><a href="admin/edit-product-offerings.php?id=<?php echo $offering['id']; ?>">Edit</a></button>
                                <button><a href="admin/delete-product-offerings.php?id=<?php echo $offering['id']; ?>&category_id=<?php echo $category_id; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this offering?')">Delete</a></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No product offerings found in this category.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
