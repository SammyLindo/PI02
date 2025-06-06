<?php
require_once '../conexao/conexao.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$tamanho = $_POST['tamanho'] ?? '';
$cor = $_POST['cor'] ?? '';
$qtd = $_POST['qtd'] ?? 1;

if (!$id) {
    echo json_encode(['success' => false, 'erro' => 'ID não informado']);
    exit;
}

// Buscar IDs
$stmt = $conexao->prepare("SELECT ID_Categorias FROM Categorias WHERE Nome = ?");
$stmt->execute([$categoria]);
$idCategoria = $stmt->fetchColumn();

$stmt = $conexao->prepare("SELECT ID_Tamanhos FROM Tamanhos WHERE Nome = ?");
$stmt->execute([$tamanho]);
$idTamanho = $stmt->fetchColumn();

$stmt = $conexao->prepare("SELECT ID_Cores FROM Cores WHERE Nome = ?");
$stmt->execute([$cor]);
$idCor = $stmt->fetchColumn();

// Atualizar produto
$stmt = $conexao->prepare("UPDATE Produtos SET Nome = ?, Preco = ?, ID_Categorias = ?, ID_Cores = ?, ID_Tamanhos = ?, Qtd = ? WHERE ID_Produtos = ?");
$ok = $stmt->execute([$nome, $preco, $idCategoria, $idCor, $idTamanho, $qtd, $id]);

echo json_encode(['success' => $ok]);