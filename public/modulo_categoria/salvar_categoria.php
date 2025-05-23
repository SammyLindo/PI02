<?php
require_once '../conexao/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];

    try {
        if (!empty($id)) {
            // Atualizar categoria existente
            $stmt = $conexao->prepare("UPDATE Categorias SET Nome = ? WHERE ID_Categorias = ?");
            $stmt->execute([$nome, $id]);
        } else {
            // Inserir nova categoria
            $stmt = $conexao->prepare("INSERT INTO Categorias (Nome) VALUES (?)");
            $stmt->execute([$nome]);
        }

        header("Location: categoria.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar categoria: " . $e->getMessage());
    }
}
?>