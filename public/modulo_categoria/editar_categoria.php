<?php
require_once '../conexao/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];

    try {
        $stmt = $conexao->prepare("UPDATE categorias SET nome = ? WHERE id = ?");
        $stmt->execute([$nome, $id]);

        // Redireciona para a página inicial após editar
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        die("Erro ao editar categoria: " . $e->getMessage());
    }
}
?>