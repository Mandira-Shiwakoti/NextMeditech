<?php
session_start();

// Check admin login
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Initialize variables
$name = '';
$description = '';
$error = '';

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($description)) {
        $error = "All fields are required.";
    }

    if (!$error) {
        $sql = "
            INSERT INTO services (name, description)
            VALUES (:name, :description)
        ";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);

        if ($result) {
            $_SESSION['message'] = "Service added successfully.";
            header("Location: services.php");
            exit();
        } else {
            $error = "Failed to add service.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .admin-content { flex:1; padding:20px; background:#f8fafc; margin-left:250px; }
        .form-group { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-weight:bold; }
        input[type="text"], textarea {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
        }
        textarea { height:120px; resize: vertical; }
        button {
            padding:10px 15px;
            background:#2563eb;
            color:#fff;
            border:none;
            border-radius:4px;
            cursor:pointer;
        }
        button:hover { background:#1d4ed8; }
        .message { padding:10px; margin-bottom:15px; border-radius:4px; }
        .error { background:#fee2e2; color:#b91c1c; }
    </style>
</head>

<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-content">
        <h2>Add Service</h2>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required><?= htmlspecialchars($description); ?></textarea>
            </div>

            <button type="submit">Add Service</button>
        </form>
    </div>
</div>
</body>
</html>
