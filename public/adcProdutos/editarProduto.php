<?php
require_once '../conexao/conexao.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? 0;
$idCategoria = $_POST['id_categoria'] ?? null;
$idTamanho = $_POST['id_tamanho'] ?? null;
$idCor = $_POST['id_cor'] ?? null;
$qtd = $_POST['qtd'] ?? 0;
$imagem = $_FILES['imagemProduto'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID do produto não informado.']);
    exit;
}

try {
    $conexao->beginTransaction();

    // 1. Atualizar dados do produto
    $stmt = $conexao->prepare(
        "UPDATE Produtos SET Nome = ?, Preco = ?, ID_Categorias = ?, ID_Cores = ?, ID_Tamanhos = ?, Qtd = ? WHERE ID_Produtos = ?"
    );
    $stmt->execute([$nome, $preco, $idCategoria, $idCor, $idTamanho, $qtd, $id]);

    // 2. Atualizar imagem, se uma nova foi enviada
    if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
        $dadosImg = file_get_contents($imagem['tmp_name']);
        
        // Verifica se já existe imagem principal para decidir entre UPDATE e INSERT
        $stmtCheck = $conexao->prepare("SELECT ID_Img FROM Produto_Img WHERE ID_Produtos = ? AND Img_Principal = 1");
        $stmtCheck->execute([$id]);
        $idImg = $stmtCheck->fetchColumn();

        if ($idImg) {
            $stmtImg = $conexao->prepare("UPDATE Produto_Img SET Arquivo = ? WHERE ID_Img = ?");
            $stmtImg->execute([$dadosImg, $idImg]);
        } else {
            $stmtImg = $conexao->prepare("INSERT INTO Produto_Img (ID_Produtos, Img_Principal, Arquivo) VALUES (?, 1, ?)");
            $stmtImg->execute([$id, $dadosImg]);
        }
    }

    $conexao->commit();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    $conexao->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}