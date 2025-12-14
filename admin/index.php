<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classes/connect-db.php';

// ✅ Check if session exists, otherwise redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location:/admin/login.php"); // 🔥 Use relative path
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <base href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/'; ?>">
    <link rel="stylesheet" type="text/css" href="/sidebar.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
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
         

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-message {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .content-area {
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            min-height: 400px;
        }

        .content-area h2 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .pseudo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            padding: 20px;
            border-top: 1px solid #dee2e6;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
            float: right;
        }

        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
     <?php include 'sidebar.php'; ?>
   
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <button class="logout-btn" onclick="logout()">Logout</button>
            <h1>Dashboard</h1>
            <div class="welcome-message">
                Welcome, Admin (<?php echo htmlspecialchars($_SESSION['user_email']); ?>)
            </div>
        </div>

        <div class="content-area">
            <h2>Dashboard Overview</h2>
            <div class="pseudo-gallery">
                <div class="gallery-item">
                    <div>📊</div>
                    <h3>Equipment</h3>
                    <p><a href="admin/equipment.php" style="color:#007bff; text-decoration:none;">Manage Equipment</a></p>
                </div>
                <div class="gallery-item">
                    <div>👥</div>
                    <h3>Users</h3>
                    <p><a href="admin/users.php" style="color:#007bff; text-decoration:none;">Manage Users</a></p>
                </div>
                <div class="gallery-item">
                    <div>📝</div>
                    <h3>Pages</h3>
                    <p><a href="admin/pages.php" style="color:#007bff; text-decoration:none;">Create/edit Pages</a></p>
                </div>
                <div class="gallery-item">
                    <div>🖼️</div>
                    <h3>Product Images</h3>
                    <p><a href="admin/products.php" style="color:#007bff; text-decoration:none;">Manage Product Images</a></p>
                </div>
                <div class="gallery-item">
                    <div>⚙️</div>
                    <h3>Subcategory</h3>
                    <p><a href="admin/subcategory.php" style="color:#007bff; text-decoration:none;">Manage Equipment Subcategory</a></p>
                </div>
               
            </div>
        </div>

        <div class="footer">
            Copyright © Your Website 2025
        </div>
    </div>

    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/admin/logout.php';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.sidebar-menu a');
            
            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPage) {
                    item.style.background = '#495057';
                    item.style.color = 'white';
                    item.style.borderLeft = '3px solid #007bff';
                }
            });
        });
    </script>
</body>
</html>
