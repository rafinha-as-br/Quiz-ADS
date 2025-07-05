<?php
// includes/auth.php

// Inicia a sessão se ainda não tiver sido iniciada.
// Essencial para armazenar informações do usuário logado.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php'; // Inclui a conexão com o banco de dados

/**
 * Registra um novo usuário no sistema.
 *
 * @param string $email O email do usuário.
 * @param string $password A senha do usuário (em texto puro).
 * @param bool $is_admin Define se o usuário é um administrador. Padrão: false.
 * @return array Um array associativo com 'success' (boolean) e 'message' (string).
 */
function registerUser($email, $password, $is_admin = false) {
    global $pdo; // Acessa o objeto PDO globalmente

    // 1. Validar o formato do email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Formato de email inválido.'];
    }

    // 2. Verificar se o email já existe
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Este email já está cadastrado.'];
        }
    } catch (PDOException $e) {
        // Em produção, registre o erro; para desenvolvimento, pode exibir
        error_log("Erro ao verificar email existente: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno ao verificar o email.'];
    }

    // 3. Hashear a senha
    // PASSWORD_BCRYPT é o algoritmo recomendado para senhas.
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    if ($hashed_password === false) {
        return ['success' => false, 'message' => 'Erro ao processar a senha.'];
    }

    // 4. Inserir o novo usuário no banco de dados
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, is_admin) VALUES (?, ?, ?)");
        $stmt->execute([$email, $hashed_password, $is_admin]);
        return ['success' => true, 'message' => 'Cadastro realizado com sucesso!'];
    } catch (PDOException $e) {
        error_log("Erro ao cadastrar usuário: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno ao cadastrar o usuário.'];
    }
}

/**
 * Autentica um usuário (faz login).
 *
 * @param string $email O email do usuário.
 * @param string $password A senha do usuário (em texto puro).
 * @return array Um array associativo com 'success' (boolean) e 'message' (string).
 */
function authenticateUser($email, $password) {
    global $pdo;

    // 1. Buscar o usuário pelo email
    try {
        $stmt = $pdo->prepare("SELECT id, email, password, is_admin FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'Email ou senha incorretos.'];
        }
    } catch (PDOException $e) {
        error_log("Erro ao buscar usuário para autenticação: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno ao autenticar.'];
    }

    // 2. Verificar a senha
    // password_verify compara a senha em texto puro com o hash armazenado
    if (password_verify($password, $user['password'])) {
        // Senha correta: inicia a sessão e armazena dados do usuário
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['is_admin'] = (bool)$user['is_admin']; // Garante que seja um booleano

        return ['success' => true, 'message' => 'Login realizado com sucesso!'];
    } else {
        // Senha incorreta
        return ['success' => false, 'message' => 'Email ou senha incorretos.'];
    }
}

/**
 * Verifica se o usuário está logado.
 *
 * @return bool True se o usuário estiver logado, false caso contrário.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se o usuário logado é um administrador.
 *
 * @return bool True se for admin, false caso contrário (ou se não estiver logado).
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['is_admin'] === true;
}

/**
 * Realiza o logout do usuário.
 */
function logoutUser(): void {
    session_unset();   // Remove todas as variáveis de sessão
    session_destroy(); // Destroi a sessão
    // Redireciona para a página inicial ou de login, se necessário
    // header('Location: index.php'); // Exemplo
    // exit();
}

?>