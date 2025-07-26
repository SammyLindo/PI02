<?php
require_once '../conexao/conexao.php';

$id = $_GET['id'] ?? null;
header('Content-Type: application/json');

if (!$id) {
    echo json_encode(['error' => 'ID não informado']);
    exit;
}

$stmt = $conexao->prepare("
    SELECT 
        p.ID_Produtos as id,
        p.Nome as produto,
        p.Preco as preco,
        p.ID_Categorias as id_categoria,
        p.ID_Tamanhos as id_tamanho,
        p.ID_Cores as id_cor,
        p.Qtd as quantidade
    FROM Produtos p
    WHERE p.ID_Produtos = ?
");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($produto);