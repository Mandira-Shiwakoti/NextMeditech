<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Check if id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $partnerId = (int) $_GET['id'];

    // Check if partner exists
    $stmt = $pdo->prepare("SELECT id FROM partners WHERE id = :id");
    $stmt->execute([':id' => $partnerId]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($partner) {

        // Delete partner from database
        $stmt = $pdo->prepare("DELETE FROM partners WHERE id = :id");
        $stmt->execute([':id' => $partnerId]);

        $_SESSION['message'] = "Partner deleted successfully!";
    } else {
        $_SESSION['message'] = "Partner not found!";
    }
}

// Redirect back to partners list
header('Location: partner.php');
exit();
