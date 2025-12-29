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

$sql = "SELECT * FROM partners ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$partners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
            background: #f8fafc;
        }

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

        .admin-content a {
            text-decoration: none;
            color: white;
        }
         .admin-content button {
        margin-right: 5px;
        padding: 5px 10px;
        background: #aabfebff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    </style>
</head>

<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-content">
        <h2>Partners</h2>

        <a href="admin/add-partner.php" class="btn-add">➕ Add Partner</a>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Partner Type</th>
                    <th>Manufacturer</th>
                    <th>Origin</th>
                    <th width="220">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($partners) > 0): ?>
                    <?php foreach ($partners as $partner): ?>
                        <tr>
                            <td><?= htmlspecialchars($partner['id']); ?></td>
                            <td><?= htmlspecialchars($partner['partner_type']); ?></td>
                            <td><?= htmlspecialchars($partner['manufacturer']); ?></td>
                            <td><?= htmlspecialchars($partner['origin']); ?></td>

                            <td>
                                <button class="btn btn-primary btn-sm">
                                    <a href="admin/edit-partner.php?id=<?= $partner['id']; ?>">Edit</a>
                                </button>

                                <button class="btn btn-danger btn-sm">
                                    <a href="admin/delete-partner.php?id=<?= $partner['id']; ?>"
                                       onclick="return confirm('Are you sure you want to delete this partner?');">
                                       Delete
                                    </a>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No partners found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
