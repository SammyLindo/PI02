<?php
require_once '../conexao/conexao.php';

header('Content-Type: application/json');
$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID não informado.']);
    exit;
}

try {
    $conexao->beginTransaction();

    // 1. Remover imagens associadas para não deixar lixo no banco
    $stmtImg = $conexao->prepare("DELETE FROM Produto_Img WHERE ID_Produtos = ?");
    $stmtImg->execute([$id]);
    
    // 2. Remover o produto principal
    $stmt = $conexao->prepare("DELETE FROM Produtos WHERE ID_Produtos = ?");
    $stmt->execute([$id]);

    $conexao->commit();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    $conexao->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}