-- Estrutura mínima para rodar o projeto.
-- A coluna senha tem 255 caracteres porque o hash do bcrypt ocupa 60 hoje,
-- e o PHP pode trocar o algoritmo padrão em versões futuras.

CREATE DATABASE IF NOT EXISTS cadastro
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE cadastro;

CREATE TABLE IF NOT EXISTS usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(120) NOT NULL,
    email           VARCHAR(180) NOT NULL UNIQUE,
    senha           VARCHAR(255) NOT NULL,
    data_nascimento DATE         NULL,
    criado_em       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
