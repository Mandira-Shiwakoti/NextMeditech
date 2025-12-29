<?php
session_start();

// Check admin login
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Initialize variables
$partner_type = 'Inclusive';
$manufacturer = '';
$origin = '';
$message = '';
$error = '';

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partner_type = $_POST['partner_type'];
    $manufacturer = trim($_POST['manufacturer']);
    $origin = trim($_POST['origin']);

    if (empty($manufacturer) || empty($origin)) {
        $error = "All fields are required.";
    }

    if (!$error) {
        $sql = "
            INSERT INTO partners
            (partner_type, manufacturer, origin)
            VALUES
            ( :partner_type, :manufacturer, :origin )
        ";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([

            ':partner_type' => $partner_type,
            ':manufacturer' => $manufacturer,
            ':origin' => $origin,
            
        ]);

        if ($result) {
            $_SESSION['message'] = "Partner added successfully.";
            header("Location: partner.php");
            exit();
        } else {
            $error = "Failed to add partner.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Partner</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .admin-content { flex:1; padding:20px; background:#f8fafc; margin-left:250px; }
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
        <h2>Add Partner</h2>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error); ?></div>
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
                <input type="text" name="manufacturer" value="<?= htmlspecialchars($manufacturer); ?>" required>
            </div>

            <div class="form-group">
                <label>Origin</label>
                <input type="text" name="origin" value="<?= htmlspecialchars($origin); ?>" required>
            </div>

           

            <button type="submit">Add Partner</button>
        </form>
    </div>
</div>
</body>
</html>
