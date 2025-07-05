<?php
// ajax/login.php

// Define que a resposta será em JSON
header('Content-Type: application/json');

// Inclui o arquivo com as funções de autenticação e a conexão com o DB
require_once '../includes/auth.php';

// Garante que a requisição é um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do corpo da requisição POST
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW); // Senha em texto puro

    // Verifica se email e senha foram fornecidos
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email e senha são obrigatórios.']);
        exit;
    }

    // Chama a função de autenticação
    $result = authenticateUser($email, $password);

    // Se o login foi bem-sucedido e é um admin, pode adicionar uma flag para redirecionamento
    if ($result['success'] && $_SESSION['is_admin']) {
        $result['redirect'] = 'admin_dashboard.php'; // Ou para onde o admin deve ir
    } elseif ($result['success']) {
        $result['redirect'] = 'quiz_page.php'; // Ou para onde o usuário comum deve ir
    }

    // Retorna a resposta em JSON
    echo json_encode($result);

} else {
    // Se não for uma requisição POST, retorna um erro
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>