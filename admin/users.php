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

    // ADD NEW USER
    if ($action === 'add') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = trim($_POST['role']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $check = $pdo->prepare("SELECT Id FROM users WHERE Email = ?");
            $check->execute([$email]);
            if ($check->fetch()) {
                $error = 'This email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (Email, Password, Role) VALUES (?, ?, ?)");
                $stmt->execute([$email, $hash, $role]);
                $message = 'User added successfully.';
            }
        }
    }

    // EDIT EXISTING USER
    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $password = trim($_POST['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        } else {
            if ($password !== '') {
                if (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET Email=?, Password=?, Role=? WHERE Id=?");
                    $stmt->execute([$email, $hash, $role, $id]);
                }
            } else {
                $stmt = $pdo->prepare("UPDATE users SET Email=?, Role=? WHERE Id=?");
                $stmt->execute([$email, $role, $id]);
            }
            $message = 'User updated successfully.';
        }
    }

    // DELETE USER
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if ($id == $_SESSION['user_id']) {
            $error = "You cannot delete your own account.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE Id = ?");
            $stmt->execute([$id]);
            $message = "User deleted successfully.";
        }
    }
}

// Fetch all users
$stmt = $pdo->prepare("SELECT Id, Email, Role FROM users ORDER BY Id DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin Panel</title>
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

        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d9e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
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

        .password-input {
            min-width: 180px;
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
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Users</h1>
        </div>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Add User Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Add New User</h2>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="form-control">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-control">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-control">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-control" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Existing Users</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <form method="post">
                                        <td><?= htmlspecialchars($u['Id']) ?></td>
                                        <td>
                                            <input type="email" name="email" value="<?= htmlspecialchars($u['Email']) ?>" required>
                                        </td>
                                        <td>
                                            <select name="role">
                                                <option value="user" <?= $u['Role']=='user'?'selected':'' ?>>User</option>
                                                <option value="admin" <?= $u['Role']=='admin'?'selected':'' ?>>Admin</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="inline-form">
                                                <input type="password" name="password" placeholder="New Password" class="password-input">
                                                <input type="hidden" name="id" value="<?= $u['Id'] ?>">
                                                <div class="action-buttons">
                                                    <button type="submit" name="action" value="edit" class="btn btn-success btn-sm">Save</button>
                                                    <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i>👥</i>
                                        <h3>No Users Found</h3>
                                        <p>Add your first user using the form above.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Simple form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const passwordInput = form.querySelector('input[type="password"][name="password"]');
                    if (passwordInput && passwordInput.value !== '' && passwordInput.value.length < 6) {
                        alert('Password must be at least 6 characters long.');
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>