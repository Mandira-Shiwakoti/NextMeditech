<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

require_once '../classes/connect-db.php';

$sql="SELECT * FROM `product_categories` ORDER BY `id` DESC";
$stmt=$pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <base href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/'; ?>">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   
    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
}

/* Main layout */
.admin-wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar (assumes sidebar.php has a wrapper) */

/* Content area */
.admin-content {
    flex: 1;
    padding: 20px;
    margin-left: 250px; /* Adjust based on sidebar width */
    background: #f8fafc;
}

/* Table styling */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: #fff;
}

.admin-table th,
.admin-table td {
    border: 1px solid #0b0b0bff;
    padding: 10px;
    text-align: left;
}

.admin-table th {
    background-color: #cde1f4ff;
    color: #4a5568;
}

/* Buttons */
.admin-content button {
    padding: 4px 8px;
    margin: 1px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-add {
    display: inline-block;
    margin-bottom: 10px;
    margin-top: 10px;
    padding: 8px 12px;
    background: #3371f6ff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.btn-add:hover {
    background: #1d4ed8;
}

img {
    border-radius: 4px;
}
.admin-content a{
    text-decoration: none;
    color: white;
}
    </style>
    
    

</head>
<body>
    
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <h2>Product Categories</h2>
        <a href="admin/add-product-category.php" class="btn-add">➕ Add Category</a>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Intro</th>
                    <th>Meta Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['id']); ?></td>
                            <td><?= htmlspecialchars($category['title']); ?></td>
                            <td><?= htmlspecialchars($category['slug']); ?></td>
                            <td><?= htmlspecialchars($category['intro']); ?></td>
                            <td><?= htmlspecialchars($category['meta_description']); ?></td>
                            <td>
                                <?php if ($category['image']): ?>
                                    <img src="../uploads/<?= htmlspecialchars($category['image']); ?>" alt="<?= htmlspecialchars($category['title']); ?>" width="100">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                               <button class="btn btn-primary"> <a href="admin/edit-product-category.php?id=<?= $category['id']; ?>"> Edit</a></button>
                               <button class="btn btn-danger"> <a href="admin/delete-product-category.php?id=<?= $category['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a></button><br>
                               <button class="btn btn-secondary"><a href="admin/view-product-offerings.php?category_id=<?= $category['id']; ?>">View Offerings</a></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    
</body>
</html>