<?php
header('Content-Type: application/json');
require_once '../../conexao/conexao.php'; // Ajuste o caminho conforme sua estrutura

$resposta = ['success' => false, 'message' => 'Ocorreu um erro desconhecido.'];

// Validação dos dados recebidos
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tabela'], $_POST['id'], $_POST['coluna_pk'])) {
    $resposta['message'] = 'Requisição inválida.';
    echo json_encode($resposta);
    exit;
}

$tabela = $_POST['tabela'];
$id = $_POST['id'];
$coluna_pk = $_POST['coluna_pk'];

try {
    // Monta a query de UPDATE dinamicamente
    $campos = [];
    $valores = [];
    
    foreach ($_POST as $chave => $valor) {
        // Ignora os campos de controle do formulário
        if ($chave === 'tabela' || $chave === 'id' || $chave === 'coluna_pk') {
            continue;
        }
        $campos[] = "`$chave` = ?";
        $valores[] = $valor;
    }

    if (empty($campos)) {
        $resposta['message'] = 'Nenhum campo para atualizar.';
        echo json_encode($resposta);
        exit;
    }

    // Adiciona o ID ao final do array de valores para a cláusula WHERE
    $valores[] = $id;

    $sql = "UPDATE `$tabela` SET " . implode(', ', $campos) . " WHERE `$coluna_pk` = ?";
    
    $stmt = $conexao->prepare($sql);

    if ($stmt->execute($valores)) {
        $resposta['success'] = true;
        $resposta['message'] = 'Registro atualizado com sucesso!';
    } else {
        $resposta['message'] = 'Erro ao executar a atualização no banco de dados.';
    }

} catch (PDOException $e) {
    $resposta['message'] = 'Erro de Conexão: ' . $e->getMessage();
}

echo json_encode($resposta);