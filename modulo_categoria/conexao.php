<?php
try {
    // Caminho para o banco de dados SQLite
    $conexao = new PDO('sqlite:DB_PI2.db');
    // Configura o modo de erro do PDO para exceções
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>