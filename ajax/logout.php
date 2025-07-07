<?php
// ajax/logout.php

// (apenas para desenvolvimento - remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/auth.php';

// Realiza logout
logoutUser();

// Detecta se é uma requisição AJAX (fetch, jQuery, etc.)
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    // Resposta para chamada AJAX
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Sessão encerrada com sucesso!',
        'redirect' => 'index.php'
    ]);
} else {
    // Acesso direto via navegador → redireciona
    header('Location: ../index.php');
    exit;
}
