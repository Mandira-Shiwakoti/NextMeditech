<?php
$host = 'localhost';
$dbname = 'nextmeditech';
$username = 'root';
$password = '';

try {
    // Create PDO instance
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
