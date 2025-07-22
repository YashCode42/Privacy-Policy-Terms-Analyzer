<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../api/db.php';

// Fetch all patterns
$stmt = $pdo->query('SELECT * FROM risk_patterns ORDER BY id DESC');
$patterns = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-links a { margin-right: 10px; }
        .logout-btn { float: right; }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout-btn">Logout</a>
        <h1>Manage Risk Patterns</h1>
        
        <!-- Add New Pattern Form -->
        <h2>Add New Pattern</h2>
        <form action="manage_patterns.php" method="post">
            <input type="hidden" name="action" value="add">
            <div style="margin-bottom: 10px;">
                <label>Pattern (Regex):</label>
                <input type="text" name="pattern" required style="width:100%;">
            </div>
            <div style="margin-bottom: 10px;">
                <label>Risk Level:</label>
                <select name="risk_level" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div style="margin-bottom: 10px;">
                <label>Description:</label>
                <textarea name="description" required style="width:100%;"></textarea>
            </div>
            <button type="submit">Add Pattern</button>
        </form>

        <!-- Existing Patterns Table -->
        <h2>Existing Patterns</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pattern</th>
                    <th>Risk Level</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patterns as $pattern): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pattern['id']); ?></td>
                    <td><?php echo htmlspecialchars($pattern['pattern']); ?></td>
                    <td><?php echo htmlspecialchars($pattern['risk_level']); ?></td>
                    <td><?php echo htmlspecialchars($pattern['description']); ?></td>
                    <td class="action-links">
                        <a href="edit_pattern.php?id=<?php echo $pattern['id']; ?>">Edit</a>
                        <a href="manage_patterns.php?action=delete&id=<?php echo $pattern['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 