-- ============================================================
-- Sistema de Controle de Processos Trabalhistas
-- Script de criação do banco de dados (MySQL / XAMPP)
-- Pode ser executado várias vezes: sempre recomeça do zero.
-- ============================================================

-- Garante que a conexão do cliente envie/receba texto em UTF-8 completo,
-- para que os nomes com acento sejam gravados corretamente.
SET NAMES utf8mb4;

-- Cria o banco de dados, caso ainda não exista, usando o charset utf8mb4
-- (suporta todos os caracteres acentuados e especiais).
CREATE DATABASE IF NOT EXISTS processostrabalhistas
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco recém-criado para que os comandos seguintes atuem nele.
USE processostrabalhistas;

-- Apaga a tabela de processos, se ela já existir,
-- para que o script sempre comece com a tabela vazia.
DROP TABLE IF EXISTS processos;

-- Cria a tabela de processos:
--   registro    -> número do processo, gerado automaticamente pelo banco (chave primária)
--   advogado    -> nome do advogado responsável
--   trabalhador -> nome do trabalhador (reclamante)
--   empresa     -> nome da empresa (reclamada)
CREATE TABLE processos (
    registro    INT          NOT NULL AUTO_INCREMENT,
    advogado    VARCHAR(100) NOT NULL,
    trabalhador VARCHAR(100) NOT NULL,
    empresa     VARCHAR(100) NOT NULL,
    PRIMARY KEY (registro)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Insere 3 processos de exemplo. O campo "registro" não é informado:
-- o próprio banco gera os números (1, 2, 3) via AUTO_INCREMENT.
-- Os nomes têm acentos para testar se o charset está funcionando.
INSERT INTO processos (advogado, trabalhador, empresa) VALUES
    ('João Antônio Brandão', 'Maria da Conceição Araújo', 'Construções Paraná Ltda.'),
    ('Fátima Gonçalves',     'José Ângelo Simões',        'Indústria Têxtil São João S.A.'),
    ('Sérgio Lúcio Magalhães','Ana Cecília Guimarães',    'Açúcar & Álcool Ribeirão Preto Ltda.');

-- Lista todos os processos cadastrados para conferir o resultado.
SELECT * FROM processos;
