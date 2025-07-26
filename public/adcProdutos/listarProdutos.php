<?php
require_once '../conexao/conexao.php';

$query = $conexao->query("
    SELECT 
        p.ID_Produtos as id,
        p.Nome as produto,
        p.Preco as preco,
        c.Nome as categoria,
        t.Nome as tamanho,
        co.Nome as cor,
        p.Qtd as quantidade,
        (SELECT ID_Img FROM Produto_Img WHERE ID_Produtos = p.ID_Produtos AND Img_Principal = 1 LIMIT 1) as id_img
    FROM Produtos p
    LEFT JOIN Categorias c ON p.ID_Categorias = c.ID_Categorias
    LEFT JOIN Tamanhos t ON p.ID_Tamanhos = t.ID_Tamanhos
    LEFT JOIN Cores co ON p.ID_Cores = co.ID_Cores
    ORDER BY p.ID_Produtos DESC
");
$produtos = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($produtos);