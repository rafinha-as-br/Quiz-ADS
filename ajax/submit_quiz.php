<?php
require_once '../includes/db.php';
require_once '../includes/session.php';
require_login();

$answers = $_POST['answers'] ?? [];

$score = 0;

foreach ($answers as $option_id) {
    $stmt = $pdo->prepare("SELECT is_correct FROM options WHERE id = ?");
    $stmt->execute([$option_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['is_correct']) {
        $score++;
    }
}

echo json_encode([
    "success" => true,
    "score" => $score,
    "total" => count($answers)
]);
