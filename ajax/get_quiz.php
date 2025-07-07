<?php
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT q.id, q.question_text, o.id AS option_id, o.option_text 
                    FROM questions q
                    JOIN options o ON q.id = o.question_id
                    ORDER BY q.id");

$questions = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $q_id = $row['id'];
    if (!isset($questions[$q_id])) {
        $questions[$q_id] = [
            'question_id' => $q_id,
            'question_text' => $row['question_text'],
            'options' => []
        ];
    }
    $questions[$q_id]['options'][] = [
        'id' => $row['option_id'],
        'text' => $row['option_text']
    ];
}

echo json_encode(array_values($questions)); // Reindexa para array numérico
