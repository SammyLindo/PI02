<?php
require_once '../conexao/conexao.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(['erro' => 'ID não informado']);
    exit;
}

$stmt = $conexao->prepare("
    SELECT 
        p.ID_Produtos as id,
        p.Nome as produto,
        p.Preco as preco,
        c.Nome as categoria,
        t.Nome as tamanho,
        co.Nome as cor,
        p.Qtd as quantidade
    FROM Produtos p
    LEFT JOIN Categorias c ON p.ID_Categorias = c.ID_Categorias
    LEFT JOIN Tamanhos t ON p.ID_Tamanhos = t.ID_Tamanhos
    LEFT JOIN Cores co ON p.ID_Cores = co.ID_Cores
    WHERE p.ID_Produtos = ?
");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($produto);