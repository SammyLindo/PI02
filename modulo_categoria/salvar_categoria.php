<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];

    try {
        if (!empty($id)) {
            $stmt = $conexao->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
            $stmt->execute([$nome, $id]);
        } else {
            $stmt = $conexao->prepare("INSERT INTO categorias (nome) VALUES (?)");
            $stmt->execute([$nome]);
        }

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar categoria: " . $e->getMessage());
    }
}
?>