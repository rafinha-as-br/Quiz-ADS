<?php
require_once '../includes/db.php';
require_once '../includes/session.php';
require_login();
require_admin();

$question = $_POST['question'];
$options = [$_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4']];
$correct = intval($_POST['correct']) - 1;

if (!$question || count($options) != 4 || $correct < 0 || $correct > 3) {
    echo json_encode(["success" => false, "message" => "Dados inválidos."]);
    exit;
}

$pdo->beginTransaction();

$stmt = $pdo->prepare("INSERT INTO questions (question_text) VALUES (?)");
$stmt->execute([$question]);
$question_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");

foreach ($options as $i => $opt) {
    $stmt->execute([$question_id, $opt, $i === $correct ? 1 : 0]);
}

$pdo->commit();

echo json_encode(["success" => true, "message" => "Pergunta adicionada com sucesso!"]);
