<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../conexao/conexao.php';

header('Content-Type: application/json');

// Recebe IDs diretamente do formulário
$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? 0;
$idCategoria = $_POST['id_categoria'] ?? null;
$idTamanho = $_POST['id_tamanho'] ?? null;
$idCor = $_POST['id_cor'] ?? null;
$qtd = $_POST['qtd'] ?? 0;
$imagem = $_FILES['imagemProduto'] ?? null;

if (empty($nome) || empty($preco) || !$idCategoria || !$idTamanho || !$idCor) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit;
}

try {
    $conexao->beginTransaction();

    // 1. Inserir produto (muito mais simples agora)
    $stmt = $conexao->prepare(
        "INSERT INTO Produtos (Nome, Preco, ID_Categorias, ID_Cores, ID_Tamanhos, Qtd) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$nome, $preco, $idCategoria, $idCor, $idTamanho, $qtd]);
    $idProduto = $conexao->lastInsertId();

    // 2. Inserir imagem, se houver
    if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
        $dadosImg = file_get_contents($imagem['tmp_name']);
        $stmtImg = $conexao->prepare("INSERT INTO Produto_Img (ID_Produtos, Img_Principal, Arquivo) VALUES (?, 1, ?)");
        $stmtImg->execute([$idProduto, $dadosImg]);
    }
    
    // Se tudo deu certo, confirma a transação
    $conexao->commit();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Se algo deu errado, desfaz tudo
    $conexao->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}