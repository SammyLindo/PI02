<?php
require_once '../conexao/conexao.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(404);
    exit('Imagem não encontrada');
}

$stmt = $conexao->prepare("SELECT Arquivo FROM Produto_Img WHERE ID_Img = ?");
$stmt->execute([$id]);
$imagem = $stmt->fetchColumn();

if ($imagem) {
    header("Content-Type: image/jpeg"); // ou image/png, se necessário
    echo $imagem;
} else {
    http_response_code(404);
    exit('Imagem não encontrada');
}