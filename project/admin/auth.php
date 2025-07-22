<?php
session_start();

// --- CONFIGURATION ---
// In a real application, use hashed passwords and store these securely.
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'password123');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        // Successful login
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        // Failed login
        header('Location: index.php?error=1');
        exit;
    }
} else {
    // Redirect if accessed directly
    header('Location: index.php');
    exit;
}
?> 