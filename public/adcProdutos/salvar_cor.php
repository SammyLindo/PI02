<?php
require_once '../conexao/conexao.php';

header('Content-Type: application/json');

$nome = trim($_POST['nome'] ?? '');
$rgb = trim($_POST['rgb'] ?? '');

if (empty($nome) || empty($rgb)) {
    echo json_encode(['success' => false, 'message' => 'Nome e código da cor são obrigatórios.']);
    exit;
}

try {
    $stmt = $conexao->prepare("INSERT INTO Cores (Nome, Rgb) VALUES (?, ?)");
    $stmt->execute([$nome, $rgb]);
    $idNovaCor = $conexao->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'cor' => [
            'id' => $idNovaCor,
            'nome' => $nome
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: Esta cor provavelmente já existe.']);
}