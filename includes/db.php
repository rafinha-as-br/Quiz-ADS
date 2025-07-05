<?php
// Definições de conexão com o banco de dados
$host = 'localhost'; 
$db   = 'quiz_ifsc';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Data Source Name (DSN) - String de conexão
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções de configuração para a conexão PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,// Define o modo de retorno padrão como array associativo
    PDO::ATTR_EMULATE_PREPARES=> false,// Desabilita a emulação de prepared statements (para segurança e performance)
];

// Tentativa de conexão com o banco de dados
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Se a conexão for bem-sucedida, a variável $pdo estará disponível para uso
} catch (\PDOException $e) {
    // Se houver um erro na conexão, interrompe a execução e exibe a mensagem de erro
    // Em um ambiente de produção, você registraria o erro e mostraria uma mensagem mais amigável ao usuário
    echo "Erro de conexão com o banco de dados: " . $e->getMessage();
    exit(); // Encerra o script
}

echo "Conexão com o banco de dados estabelecida com sucesso!";

?>