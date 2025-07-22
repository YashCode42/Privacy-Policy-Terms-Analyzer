<?php
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

$host = 'localhost';
$db   = 'test';
$user = 'root';
$pass = '';  // default password for root in XAMPP is usually empty
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die(json_encode(['error' => 'Database connection failed.']));
}
?>
