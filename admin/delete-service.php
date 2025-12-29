<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once '../classes/connect-db.php';

// Check if id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $serviceId = (int) $_GET['id'];

    // Check if service exists
    $stmt = $pdo->prepare("SELECT id FROM services WHERE id = :id");
    $stmt->execute([':id' => $serviceId]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {

        // Delete service from database
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
        $stmt->execute([':id' => $serviceId]);

        $_SESSION['message'] = "Service deleted successfully!";
    } else {
        $_SESSION['message'] = "Service not found!";
    }
}

// Redirect back to services list
header('Location: services.php');
exit();
