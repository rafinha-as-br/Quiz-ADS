<?php
require_once '../includes/auth.php';
require_once '../includes/session.php';
require_login();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Quiz do Usuário</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo(a), <?= $_SESSION['user']['name'] ?>!</h2>
        <div id="quiz-area"></div>
        <div id="result-area" style="display: none;"></div>
        <button id="logout-btn">Sair</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="../assets/js/quiz.js"></script>
</body>
</html>
