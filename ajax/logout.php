<?php
// ajax/logout.php

// Define que a resposta será em JSON
header('Content-Type: application/json');

// Inclui o arquivo com as funções de autenticação e a conexão com o DB
require_once '../includes/auth.php';

// Chama a função de logout
logoutUser();

// Retorna uma resposta de sucesso
echo json_encode(['success' => true, 'message' => 'Sessão encerrada com sucesso!', 'redirect' => 'index.php']);
?>