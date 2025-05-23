<?php

require_once '../conexao/conexao.php'; // Usa a conexão centralizada

try {
    // Botão excluir com confirmação
    if (isset($_GET['excluir'], $_GET['tabela'], $_GET['id'], $_GET['col'])) {
        $tabela = $_GET['tabela'];
        $id = $_GET['id'];
        $coluna = $_GET['col'];

        $stmt = $conexao->prepare("DELETE FROM $tabela WHERE $coluna = ?");
        $stmt->execute([$id]);
        header("Location: estoque.php"); 
        exit;
    }

    // Listar tabelas do banco (exceto as internas do SQLite)
    $TabelasQueries = $conexao->query("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'");
    $tabelas = $TabelasQueries->fetchAll(PDO::FETCH_COLUMN);

    // HTML de cabeçalho com links para assets (rollback para como estava antes)
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Banco de Dados</title>
        <link rel='stylesheet' href='styleCategoria.css'>
        <link rel='stylesheet' href='styleIndex.css'>
        <link rel='stylesheet' href='adicionarProduto.css'>
        <script src='adcProdutos.js'></script>
        <style>
            body { font-family: Arial; margin: 20px; }
            h2 { margin-top: 40px; }
            table { border-collapse: collapse; width: 100%; margin-bottom: 40px; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .actions button { margin-right: 5px; }
        </style>
        <script>
            function confirmarExclusao(tabela, id, col) {
                if (confirm('Tem certeza que deseja excluir este registro?')) {
                    window.location = 'estoque.php?excluir=1&tabela=' + tabela + '&id=' + id + '&col=' + col;
                }
            }
        </script>
    </head>
    <body>";

    echo "<h1>Visualização do Banco de Dados</h1>";

    foreach ($tabelas as $tabela) {
        echo "<h2>Tabela: $tabela</h2>";

        $dataQuery = $conexao->query("SELECT * FROM $tabela");
        $dados = $dataQuery->fetchAll(PDO::FETCH_ASSOC);

        if (count($dados) > 0) {
            echo "<table><tr>";

            // Cabeçalhos da tabela
            foreach (array_keys($dados[0]) as $coluna) {
                echo "<th>$coluna</th>";
            }
            echo "<th>Ações</th></tr>";

            // Detectar a chave primária corretamente
            if ($tabela === 'Produtos') {
                $idCol = 'ID_Produtos';
            } elseif ($tabela === 'Categorias') {
                $idCol = 'ID_Categorias';
            } elseif ($tabela === 'Cores') {
                $idCol = 'ID_Cores';
            } elseif ($tabela === 'Tamanhos') {
                $idCol = 'ID_Tamanhos';
            } elseif ($tabela === 'Produto_Img') {
                $idCol = 'ID_Img';
            } else {
                $idCol = 'id';
            }

            // Linhas da tabela
            foreach ($dados as $linha) {
                echo '<tr>';
                foreach ($linha as $celula) {
                    echo "<td>" . htmlspecialchars($celula) . "</td>";
                }

                $id = $linha[$idCol] ?? '';

                echo "<td class='actions'>
                        <button onclick=\"location.href='adcionarProdutos.php?tabela=$tabela&id=$id&col=$idCol'\">Editar</button>
                        <button onclick=\"confirmarExclusao('$tabela', '$id', '$idCol')\">Excluir</button>
                      </td>";
                echo '</tr>';
            }

            echo "</table>";

        } else {
            echo "<p><em>Nenhum dado encontrado nesta tabela.</em></p>";
        }
    }

    echo "</body></html>";

} catch (PDOException $e) {
    echo "Erro ao acessar o banco de dados: " . $e->getMessage();
}
?>