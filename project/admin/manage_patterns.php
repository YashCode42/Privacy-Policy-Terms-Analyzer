<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access Denied');
}
require_once '../api/db.php';

$action = $_REQUEST['action'] ?? '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
        // Add a new pattern
        $pattern = $_POST['pattern'];
        $risk_level = $_POST['risk_level'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare('INSERT INTO risk_patterns (pattern, risk_level, description) VALUES (?, ?, ?)');
        $stmt->execute([$pattern, $risk_level, $description]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete') {
        // Delete a pattern
        $id = $_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM risk_patterns WHERE id = ?');
        $stmt->execute([$id]);
    }

    header('Location: dashboard.php');
    exit;

} catch (PDOException $e) {
    // In a real app, log this error instead of displaying it
    die('Database error: ' . $e->getMessage());
}
?> 