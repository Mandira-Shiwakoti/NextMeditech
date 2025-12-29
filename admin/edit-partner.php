<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Get partner ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: partner.php');
    exit();
}

$partnerId = (int) $_GET['id'];

// Fetch partner data
$stmt = $pdo->prepare("SELECT * FROM partners WHERE id = :id");
$stmt->execute([':id' => $partnerId]);
$partner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$partner) {
    $_SESSION['message'] = "Partner not found!";
    header('Location: partners.php');
    exit();
}

// Initialize variables

$partner_type = $partner['partner_type'];
$manufacturer = $partner['manufacturer'];
$origin = $partner['origin'];


$error = '';
$message = '';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partner_type = $_POST['partner_type'];
    $manufacturer = trim($_POST['manufacturer']);
    $origin = trim($_POST['origin']);

    if (empty($manufacturer) || empty($origin)) {
        $error = "All fields are required.";
    }

    if (!$error) {
        $sql = "
            UPDATE partners SET
                partner_type = :partner_type,
                manufacturer = :manufacturer,
                origin = :origin
            WHERE id = :id
        ";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':partner_type' => $partner_type,
            ':manufacturer' => $manufacturer,
            ':origin' => $origin,
            ':id' => $partnerId
        ]);

        if ($result) {
            $_SESSION['message'] = "Partner updated successfully!";
            header("Location: partner.php");
            exit();
        } else {
            $error = "Failed to update partner.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Partner</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<style>
body { margin:0; font-family: Arial, sans-serif; }
.admin-wrapper { display:flex; min-height:100vh; }
.admin-content { flex:1; padding:20px; margin-left:250px; background:#f8fafc; }
.form-group { margin-bottom:15px; }
label { display:block; margin-bottom:5px; font-weight:bold; }
input[type="text"], select {
    width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;
}
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
        <h2>Edit Partner</h2>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            

            <div class="form-group">
                <label>Partner Type</label>
                <select name="partner_type">
                    <option value="Inclusive" <?= $partner_type=='Inclusive'?'selected':'' ?>>Inclusive</option>
                    <option value="Exclusive" <?= $partner_type=='Exclusive'?'selected':'' ?>>Exclusive</option>
                </select>
            </div>

            <div class="form-group">
                <label>Manufacturer</label>
                <input type="text" name="manufacturer" value="<?= htmlspecialchars($manufacturer) ?>" required>
            </div>

            <div class="form-group">
                <label>Origin</label>
                <input type="text" name="origin" value="<?= htmlspecialchars($origin) ?>" required>
            </div>


            <button type="submit">Update Partner</button>
        </form>
    </div>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
