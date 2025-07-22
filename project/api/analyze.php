<?php
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Get input text
$data = json_decode(file_get_contents('php://input'), true);
$text = isset($data['text']) ? $data['text'] : '';
if (!$text) {
    echo json_encode(['error' => 'No text received']);
    exit;
}

// Fetch risk patterns from DB
$stmt = $pdo->query('SELECT * FROM risk_patterns');
$patterns = $stmt->fetchAll();

$highlighted = $text;
$found = [];

// Search for each pattern in the text
foreach ($patterns as $pat) {
    $pattern = preg_quote($pat['pattern'], '/');
    $risk = htmlspecialchars($pat['risk_level']);
    $desc = htmlspecialchars($pat['description']);
    $class = 'risk-' . $risk;
    $regex = "/($pattern)/i";
    if (preg_match_all($regex, $highlighted, $matches)) {
        $found[] = [
            'pattern' => $pat['pattern'],
            'risk_level' => $pat['risk_level'],
            'description' => $pat['description']
        ];
        // Highlight in text
        $highlighted = preg_replace($regex, '<span class="' . $class . '" title="' . $desc . '">$1</span>', $highlighted);
    }
}

// Generate summary
$summary = '';
if (count($found) > 0) {
    $summary = 'The document contains ' . count($found) . ' risky clauses:';
    foreach ($found as $f) {
        $summary .= "\n- [{$f['risk_level']}] {$f['pattern']} ({$f['description']})";
    }
} else {
    $summary = 'No risky clauses found.';
}

// Return result as JSON
echo json_encode([
    'highlighted' => $highlighted,
    'summary' => nl2br($summary),
    'found' => $found
]);
?> 