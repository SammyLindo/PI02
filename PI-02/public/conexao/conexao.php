<?php
// Caminho absoluto para o banco de dados na pasta 'database'
$banco = realpath(__DIR__ . '/../../database/DB_PI2.db');

if (!$banco) {
    die("Arquivo do banco de dados não encontrado.");
}

try {
    $conexao = new PDO("sqlite:" . $banco);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

?>