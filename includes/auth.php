<?php
// includes/auth.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

/**
 * Registra um novo usuário.
 */
function registerUser($email, $password, $is_admin = false, $name = 'Usuário') {
    global $pdo;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Formato de email inválido.'];
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Este email já está cadastrado.'];
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        if (!$hashed_password) {
            return ['success' => false, 'message' => 'Erro ao processar a senha.'];
        }

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $is_admin]);

        return ['success' => true, 'message' => 'Cadastro realizado com sucesso!'];
    } catch (PDOException $e) {
        error_log("Erro no registerUser: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno ao cadastrar o usuário.'];
    }
}

/**
 * Autentica um usuário.
 */
function authenticateUser($email, $password) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Email ou senha incorretos.'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Email ou senha incorretos.'];
        }

        // Autenticação bem-sucedida
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['is_admin'] = (bool) $user['is_admin'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'is_admin' => (bool) $user['is_admin']
        ];

        return ['success' => true, 'message' => 'Login realizado com sucesso!'];
    } catch (PDOException $e) {
        error_log("Erro no authenticateUser: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno ao autenticar.'];
    }
}

/**
 * Verifica se o usuário está logado.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se o usuário é administrador.
 */
function isAdmin() {
    return isLoggedIn() && ($_SESSION['is_admin'] ?? false) === true;
}

/**
 * Faz logout do usuário.
 */
function logoutUser(): void {
    session_unset();
    session_destroy();
}
