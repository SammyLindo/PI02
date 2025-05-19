<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        $stmt = $conexao->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$id]);

        // Redireciona para a página inicial após excluir
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir categoria: " . $e->getMessage());
    }
}
?>