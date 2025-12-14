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

// // First, let's check the actual structure of your pages table
// try {
//     $stmt = $pdo->query("DESCRIBE pages");
//     $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
//     // Uncomment the line below to see what columns you actually have
//     // echo "<pre>Columns in pages table: " . print_r($columns, true) . "</pre>";
// } catch (Exception $e) {
//     $error = "Error checking table structure: " . $e->getMessage();
// }

// Handle Add / Edit / Delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD NEW PAGE
    if ($action === 'add') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        if (empty($title)) {
            $error = 'Page title is required.';
        } else {
            // Try different possible column names for content
            $stmt = $pdo->prepare("INSERT INTO pages (title, detail) VALUES (?, ?)");
            $stmt->execute([$title, $content]);
            $message = 'Page added successfully.';
        }
    }

    // EDIT EXISTING PAGE
    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        if (empty($title)) {
            $error = 'Page title is required.';
        } else {
            // Try different possible column names for content
            $stmt = $pdo->prepare("UPDATE pages SET title=?, detail=? WHERE id=?");
            $stmt->execute([$title, $content, $id]);
            $message = 'Page updated successfully.';
        }
    }

    // DELETE PAGE
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Page deleted successfully.";
    }
}

// Fetch all pages - try different possible column names
try {
    $stmt = $pdo->prepare("SELECT id, title, detail FROM pages ORDER BY id DESC");
    $stmt->execute();
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // If that fails, try with different column names
    try {
        $stmt = $pdo->prepare("SELECT * FROM pages ORDER BY id DESC");
        $stmt->execute();
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Debug: show what we got
        // echo "<pre>Pages data: " . print_r($pages, true) . "</pre>";
    } catch (Exception $e2) {
        $error = "Error fetching pages: " . $e2->getMessage();
        $pages = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pages | Admin Panel</title>
    <link rel="stylesheet" href="sidebar.css">
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

        body {
            background-color: #f5f7fb;
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e6ed;
        }

        .page-title {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 25px;
        }

        .card-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f7;
        }

        .card-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-control {
            flex: 1;
            min-width: 200px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d9e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a7bc8;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-success {
            background-color: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        .btn-info {
            background-color: #3498db;
            color: white;
        }

        .btn-info:hover {
            background-color: #2980b9;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eef2f7;
            color: #4a5568;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .message {
            padding: 12px 15px;
            background: #d4edda;
            color: #155724;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .error {
            padding: 12px 15px;
            background: #f8d7da;
            color: #721c24;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        .inline-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .table-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .content-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }
            
            .form-control {
                min-width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .table-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Pages</h1>
        </div>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Debug info - remove this after you confirm the structure -->
        <?php if (isset($columns)): ?>
            <div class="card" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                <h3>Debug Info - Table Structure</h3>
                <p>Columns found in 'pages' table: <?= implode(', ', $columns) ?></p>
                <p><small>Remove this debug section after confirming the table structure</small></p>
            </div>
        <?php endif; ?>

        <!-- Add Page Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Add New Page</h2>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="form-control">
                        <label for="title">Page Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Page</button>
                </div>
            </form>
        </div>

        <!-- Pages Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Existing Pages</h2>
            </div>
            
            <div class="table-options">
                <div class="filter-controls">
                    <select id="rows-per-page">
                        <option value="25">Show 25</option>
                        <option value="50">Show 50</option>
                        <option value="100">Show 100</option>
                        <option value="all">Show all</option>
                    </select>
                    
                    <input type="text" id="table-filter" placeholder="Search this table">
                    
                    <select id="sort-by">
                        <option value="">Sort by key: None</option>
                        <option value="id">ID</option>
                        <option value="title">Title</option>
                    </select>
                </div>
            </div>
            
            <div class="table-container">
                <table id="pages-table">
                    <thead>
                        <tr>
                            <th width="50px"></th>
                            <th width="80px">ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th width="180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($pages) > 0): ?>
                            <?php foreach ($pages as $page): ?>
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td><?= htmlspecialchars($page['id'] ?? '') ?></td>
                                    <td>
                                        <input type="text" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" form="form-<?= $page['id'] ?? '' ?>" required>
                                    </td>
                                    <td>
                                        <?php 
                                        // Try different possible content column names
                                        $content = $page['detail'] ?? $page['content'] ?? $page['body'] ?? $page['description'] ?? 'No content';
                                        ?>
                                        <div class="content-preview" title="<?= htmlspecialchars($content) ?>">
                                            <?= htmlspecialchars(substr($content, 0, 50)) ?>...
                                        </div>
                                    </td>
                                    <td>
                                        <form id="form-<?= $page['id'] ?? '' ?>" method="post" class="inline-form">
                                            <input type="hidden" name="id" value="<?= $page['id'] ?? '' ?>">
                                            <input type="hidden" name="content" value="<?= htmlspecialchars($content) ?>">
                                            <div class="action-buttons">
                                                <button type="submit" name="action" value="edit" class="btn btn-success btn-sm">Save</button>
                                                <button type="button" class="btn btn-info btn-sm" onclick="editContent(<?= $page['id'] ?? '' ?>)">Edit Content</button>
                                                <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page?')">Delete</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i>📄</i>
                                        <h3>No Pages Found</h3>
                                        <p>Add your first page using the form above.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Content Editor Modal -->
    <div id="contentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; width: 80%; max-width: 800px; border-radius: 10px; padding: 20px;">
            <h3>Edit Page Content</h3>
            <textarea id="modalContent" style="width: 100%; height: 300px; margin: 15px 0;"></textarea>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-success" onclick="saveContent()">Save Content</button>
            </div>
        </div>
    </div>

    <script>
        let editingId = null;
        
        function editContent(id) {
            editingId = id;
            const form = document.getElementById('form-' + id);
            const contentInput = form.querySelector('input[name="content"]');
            document.getElementById('modalContent').value = contentInput.value;
            document.getElementById('contentModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('contentModal').style.display = 'none';
            editingId = null;
        }
        
        function saveContent() {
            if (editingId) {
                const form = document.getElementById('form-' + editingId);
                const contentInput = form.querySelector('input[name="content"]');
                contentInput.value = document.getElementById('modalContent').value;
                closeModal();
            }
        }
        
        // Table filtering and sorting functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tableFilter = document.getElementById('table-filter');
            const table = document.getElementById('pages-table');
            
            tableFilter.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let showRow = false;
                    
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            showRow = true;
                            break;
                        }
                    }
                    
                    rows[i].style.display = showRow ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>