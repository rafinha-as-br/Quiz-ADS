<?php
// ajax/register.php

// Define que a resposta será em JSON
header('Content-Type: application/json');

// Inclui o arquivo com as funções de autenticação e a conexão com o DB
require_once '../includes/auth.php';

// Garante que a requisição é um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do corpo da requisição POST
    // filter_input_array é mais seguro para pegar dados POST
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW); // Senha em texto puro

    // Verifica se email e senha foram fornecidos
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email e senha são obrigatórios.']);
        exit;
    }

    // Chama a função de registro
    $result = registerUser($email, $password);

    // Retorna a resposta em JSON
    echo json_encode($result);

} else {
    // Se não for uma requisição POST, retorna um erro
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>