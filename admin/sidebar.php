<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/'; ?>">

    <link rel="stylesheet" type="text/css" href="/sidebar.css">
    
</head>
<body>
     <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><a href="/admin/index.php" style="color:white;text-decoration:none;"># ADMIN</a></h2>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-section">Dashboard</li>
            <li><a href="/admin/users.php">Users</a></li>
            <li><a href="/admin/equipment.php">Equipment</a></li>
           <li><a href="/admin/equipment-category-form.php?action=add" >Add New Category</a></li>
            <li class="menu-section">Pages</li>
            <li><a href="/admin/pages.php">Pages</a></li>
            <li class="menu-section">Product</li>
            <li><a href="/admin/products.php">Product Images</a></li>
            <li class="menu-section">SubCategory</li>
            <li><a href="/admin/subcategory.php">Equipment SubCategory</a></li>
        </ul>
    </div>

    
</body>
</html>