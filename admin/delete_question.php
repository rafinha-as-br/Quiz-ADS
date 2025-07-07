<?php
require_once '../includes/db.php';
require_once '../includes/session.php';
require_login();
require_admin();

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(["success" => false, "message" => "ID inválido."]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM options WHERE question_id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["success" => true, "message" => "Pergunta deletada com sucesso!"]);
