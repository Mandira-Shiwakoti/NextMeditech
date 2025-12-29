<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/'; ?>">

    <link rel="stylesheet" type="text/css" href="/sidebar.css">
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

        .menu-section {
            color: #6c757d;
            font-size: 0.8rem;
            padding: 15px 20px 5px 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
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
            <li><a href="/admin/product_categories.php">product categories</a></li>
           <li><a href="/admin/equipment-category-form.php?action=add" >Add New Category</a></li>
            <li class="menu-section">Pages</li>
            <li><a href="/admin/pages.php">Pages</a></li>
            <li class="menu-section">Partners</li>
            <li><a href="/admin/partner.php">Partners</a></li>
            <li><a href="/admin/add-partner.php">Add new Partner</a></li>
        </ul>
    </div>

    
</body>
</html>