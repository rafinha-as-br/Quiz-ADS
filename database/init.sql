-- database/init.sql

CREATE DATABASE QUIZ_IFSC;
USE QUIZ_IFSC;


-- Desabilita a verificação de chaves estrangeiras temporariamente para evitar erros
-- durante a criação ou reset do banco de dados, se as tabelas existirem.
SET FOREIGN_KEY_CHECKS = 0;

-- Drop Tables (Opcional, útil para reiniciar o banco de dados durante o desenvolvimento)
-- Se você rodar este script várias vezes, ele vai apagar e recriar as tabelas.
-- Remova ou comente estas linhas em ambiente de produção, após a primeira execução.
DROP TABLE IF EXISTS options;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS users;

-- Reabilita a verificação de chaves estrangeiras
SET FOREIGN_KEY_CHECKS = 1;


-- 1. Tabela de Usuários (users)
-- Armazena informações de administradores e usuários comuns.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,       -- Email do usuário (login), deve ser único
    password VARCHAR(255) NOT NULL,           -- Hash da senha (NUNCA armazene senhas em texto puro!)
    is_admin BOOLEAN DEFAULT FALSE,           -- TRUE para administradores, FALSE para usuários comuns
    score DECIMAL(5, 2) DEFAULT 0.00,         -- Porcentagem de acertos na última tentativa do quiz
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabela de Perguntas (questions)
-- Armazena o texto de cada pergunta do quiz.
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,              -- O texto completo da pergunta
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabela de Opções de Resposta (options)
-- Armazena as 4 opções para cada pergunta e indica qual é a correta.
CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,                 -- Chave estrangeira para a tabela 'questions'
    option_text VARCHAR(255) NOT NULL,        -- O texto da opção de resposta
    is_correct BOOLEAN DEFAULT FALSE,         -- TRUE se esta for a opção correta, FALSE caso contrário
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    -- ON DELETE CASCADE: Se uma pergunta for deletada, suas opções associadas também serão.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Inserir um usuário administrador inicial
INSERT INTO users (email, password, is_admin) VALUES (
    'admin@quizads.com',
    '$2y$12$W5RC.HtrFv58jgMUqpqFIuSUwAEtdBoFPIHs67smepkx9Q0Dsrmbu',
    TRUE
);



