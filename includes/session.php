<?php
// includes/session.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Garante que o usuário esteja logado.
 * Redireciona para a página de login se não estiver.
 */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit;
    }
}

/**
 * Garante que o usuário seja administrador.
 * Redireciona se não for.
 */
function require_admin() {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        header('Location: ../quiz_page.php'); // ou index.php, se preferir
        exit;
    }
}
