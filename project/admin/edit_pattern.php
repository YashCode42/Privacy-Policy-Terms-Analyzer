<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../api/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pattern = $_POST['pattern'];
    $risk_level = $_POST['risk_level'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare('UPDATE risk_patterns SET pattern = ?, risk_level = ?, description = ? WHERE id = ?');
    $stmt->execute([$pattern, $risk_level, $description, $id]);
    
    header('Location: dashboard.php');
    exit;
}

// Fetch the existing pattern
$stmt = $pdo->prepare('SELECT * FROM risk_patterns WHERE id = ?');
$stmt->execute([$id]);
$pattern = $stmt->fetch();

if (!$pattern) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Risk Pattern</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Risk Pattern #<?php echo htmlspecialchars($pattern['id']); ?></h1>
        <form method="post">
            <div style="margin-bottom: 10px;">
                <label>Pattern (Regex):</label>
                <input type="text" name="pattern" value="<?php echo htmlspecialchars($pattern['pattern']); ?>" required style="width:100%;">
            </div>
            <div style="margin-bottom: 10px;">
                <label>Risk Level:</label>
                <select name="risk_level" required>
                    <option value="low" <?php if ($pattern['risk_level'] === 'low') echo 'selected'; ?>>Low</option>
                    <option value="medium" <?php if ($pattern['risk_level'] === 'medium') echo 'selected'; ?>>Medium</option>
                    <option value="high" <?php if ($pattern['risk_level'] === 'high') echo 'selected'; ?>>High</option>
                </select>
            </div>
            <div style="margin-bottom: 10px;">
                <label>Description:</label>
                <textarea name="description" required style="width:100%;"><?php echo htmlspecialchars($pattern['description']); ?></textarea>
            </div>
            <button type="submit">Update Pattern</button>
            <a href="dashboard.php" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html> 