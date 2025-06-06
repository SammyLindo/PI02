<?php
require_once '../conexao/conexao.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(['success' => false, 'erro' => 'ID não informado']);
    exit;
}

$stmt = $conexao->prepare("DELETE FROM Produtos WHERE ID_Produtos = ?");
$ok = $stmt->execute([$id]);

echo json_encode(['success' => $ok]);