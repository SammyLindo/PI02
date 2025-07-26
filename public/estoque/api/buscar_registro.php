<?php
header('Content-Type: application/json');
require_once '../../conexao/conexao.php'; // Ajuste o caminho conforme sua estrutura

$resposta = [];

// Validação básica dos parâmetros
if (!isset($_GET['tabela']) || !isset($_GET['id']) || !isset($_GET['coluna_pk'])) {
    $resposta['erro'] = 'Parâmetros insuficientes.';
    echo json_encode($resposta);
    exit;
}

$tabela = $_GET['tabela'];
$id = $_GET['id'];
$coluna_pk = $_GET['coluna_pk'];

try {
    // Usar prepared statements para segurança
    $stmt = $conexao->prepare("SELECT * FROM `$tabela` WHERE `$coluna_pk` = ?");
    $stmt->execute([$id]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        echo json_encode($registro);
    } else {
        $resposta['erro'] = 'Registro não encontrado.';
        echo json_encode($resposta);
    }

} catch (PDOException $e) {
    $resposta['erro'] = 'Erro no banco de dados: ' . $e->getMessage();
    echo json_encode($resposta);
}