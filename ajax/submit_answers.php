<?php
require_once '../includes/db.php';
require_once '../includes/session.php';
require_login();

$user_id = $_SESSION['user_id'];
$answers = $_POST['answers'] ?? [];

$score = 0;
$total = count($answers);

if ($total > 0) {
    $correct = 0;
    foreach ($answers as $questionIndex => $optionId) {
        $stmt = $pdo->prepare("SELECT is_correct FROM options WHERE id = ?");
        $stmt->execute([$optionId]);
        if ($stmt->fetchColumn()) {
            $correct++;
        }
    }
    $score = round(($correct / $total) * 100, 2);

    $stmt = $pdo->prepare("UPDATE users SET score = ? WHERE id = ?");
    $stmt->execute([$score, $user_id]);
}

echo json_encode(["score" => $score]);
