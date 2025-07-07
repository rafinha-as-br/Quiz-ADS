<?php
require_once '../includes/auth.php';
require_once '../includes/session.php';
require_login();
require_admin(); // Apenas administradores podem acessar
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Painel Administrativo – Perguntas do Quiz</h2>

        <form id="add-question-form">
            <input type="text" name="question" placeholder="Digite a pergunta" required><br><br>
            
            <input type="text" name="option1" placeholder="Opção 1" required><br>
            <input type="text" name="option2" placeholder="Opção 2" required><br>
            <input type="text" name="option3" placeholder="Opção 3" required><br>
            <input type="text" name="option4" placeholder="Opção 4" required><br>

            <label for="correct">Número da opção correta (1-4):</label><br>
            <input type="number" nam
