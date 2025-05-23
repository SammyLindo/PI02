<?php
require_once '../conexao/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        $stmt = $conexao->prepare("DELETE FROM Categorias WHERE ID_Categorias = ?");
        $stmt->execute([$id]);
        header("Location: categoria.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao excluir categoria: " . $e->getMessage());
    }
}
?>