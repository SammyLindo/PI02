<?php
require_once '../conexao/conexao.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $rgb = trim($_POST['rgb'] ?? '');

    if ($nome && $rgb) {
        $stmt = $conexao->prepare("INSERT INTO Cores (Nome, Rgb) VALUES (?, ?)");
        if ($stmt->execute([$nome, $rgb])) {
            $msg = "Cor cadastrada com sucesso!";
        } else {
            $msg = "Erro ao cadastrar cor!";
        }
    } else {
        $msg = "Preencha todos os campos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cor</title>
    <link rel="stylesheet" href="../assets/css/adicionarProduto.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Nova Cor</h2>
        <?php if ($msg): ?>
            <p><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome da cor" required>
            <input type="text" name="rgb" placeholder="RGB (ex: #FF0000)" required>
            <button type="submit">Cadastrar</button>
        </form>
        <a href="adcionarProdutos.php">Voltar</a>
    </div>
</body>
</html>