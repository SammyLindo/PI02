<?php
require_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atribuindo os dados enviados
    $itemNome = $_POST['itemNome'] ?? '';
    $tamanoSelecionado = $_POST['tamanho'] ?? '';
    $corSelecionada = $_POST['cor'] ?? '';
    $quantidade = $_POST['quantidadeProduto'] ?? 0;

    // Validações
    if (empty($itemNome)) {
        echo "<p style='color: red;'>Erro: Preencha o nome do item que deseja cadastrar!</p>";
    } elseif (empty($tamanoSelecionado)) {
        echo "<p style='color: red;'>Erro: Selecione o tamanho do item.</p>";
    } elseif (empty($corSelecionada)) {
        echo "<p style='color: red;'>Erro: Selecione a cor do item.</p>";
    } elseif (empty($quantidade) || $quantidade <= 0) {
        echo "<p style='color: red;'>Erro: Digite uma quantidade válida.</p>";
    } else {
        // Tudo certo, inserir no banco
        try {
            $sql = $conexao->prepare("INSERT INTO Produtos (Nome, ID_Tamanhos, ID_Cores, Qtd) VALUES (:Nome, :ID_Tamanhos, :ID_Cores, :Qtd)");

            $sql->execute([
                ':Nome' => $itemNome,
                ':ID_Tamanhos' => $tamanoSelecionado,
                ':ID_Cores' => $corSelecionada,
                ':Qtd' => $quantidade
            ]);

            if ($sql->rowCount() >= 1) {
                echo '<p style="color: green;">Dados cadastrados com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Algo deu errado ao cadastrar.</p>';
            }
        } catch (PDOException $ex) {
            echo "<p style='color: red;'>Erro na inserção: " . $ex->getMessage() . "</p>";
        }
    }
}
?>