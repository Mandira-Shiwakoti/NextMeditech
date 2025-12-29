<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Get service ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: services.php');
    exit();
}

$serviceId = (int) $_GET['id'];

// Fetch service data
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = :id");
$stmt->execute([':id' => $serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    $_SESSION['message'] = "Service not found!";
    header('Location: services.php');
    exit();
}

// Initialize variables
$name = $service['name'];
$description = $service['description'];

$error = '';
$message = '';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($description)) {
        $error = "All fields are required.";
    }

    if (!$error) {
        $sql = "UPDATE services SET name = :name, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':id' => $serviceId
        ]);

        if ($result) {
            $_SESSION['message'] = "Service updated successfully!";
            header("Location: services.php");
            exit();
        } else {
            $error = "Failed to update service.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Service</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<style>
body { margin:0; font-family: Arial, sans-serif; }
.admin-wrapper { display:flex; min-height:100vh; }
.admin-content { flex:1; padding:20px; margin-left:250px; background:#f8fafc; }
.form-group { margin-bottom:15px; }
label { display:block; margin-bottom:5px; font-weight:bold; }
input[type="text"], textarea {
    width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
}
textarea { height:100px; resize: vertical; }
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
.success { background:#d1fae5; color:#065f46; }
.error { background:#fee2e2; color:#b91c1c; }
</style>
</head>

<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-content">
        <h2>Edit Service</h2>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required><?= htmlspecialchars($description) ?></textarea>
            </div>

            <button type="submit">Update Service</button>
        </form>
    </div>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
