<?php
require_once '../conexao/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    h2 {
      margin-top: 40px;
      border-bottom: 2px solid #ccc;
      padding-bottom: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #eaeaea;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }

    input[type="text"],
    input[type="number"] {
      padding: 6px 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }

    button {
      padding: 8px 16px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #555;
      transform: scale(1.05);
    }

    button:active {
      transform: scale(0.98);
    }

    @media (max-width: 768px) {
      table, form {
        font-size: 12px;
      }

      input[type="text"],
      input[type="number"],
      button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
    function atualizarProduto($conexao, $id, $nome, $preco, $categoria, $cor, $tamanho, $qtd) {
        $stmt = $conexao->prepare("UPDATE Produtos 
            SET Nome = ?, Preco = ?, ID_Categorias = ?, ID_Cores = ?, ID_Tamanhos = ?, Qtd = ? 
            WHERE ID_Produtos = ?");
        return $stmt->execute([$nome, $preco, $categoria, $cor, $tamanho, $qtd, $id]);
    }

    $tabelas = ['Produtos', 'Categorias', 'Cores', 'Tamanhos'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar']) && isset($_POST['tabela'])) {
        $tabela = $_POST['tabela'];
        if ($tabela === 'Produtos') {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $preco = $_POST['preco'];
            $categoria = $_POST['categoria'];
            $cor = $_POST['cor'];
            $tamanho = $_POST['tamanho'];
            $qtd = $_POST['qtd'];

            if (atualizarProduto($conexao, $id, $nome, $preco, $categoria, $cor, $tamanho, $qtd)) {
                echo "<script>alert('Produto atualizado com sucesso!'); window.location.href = 'estoque.php';</script>";
            } else {
                echo "<script>alert('Erro ao atualizar o produto.');</script>";
            }
        } elseif (in_array($tabela, ['Categorias', 'Cores', 'Tamanhos'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $colunaId = "ID_" . $tabela;

            $stmt = $conexao->prepare("UPDATE $tabela SET Nome = ? WHERE $colunaId = ?");
            if ($stmt->execute([$nome, $id])) {
                echo "<script>alert('$tabela atualizada com sucesso!'); window.location.href = 'estoque.php';</script>";
            } else {
                echo "<script>alert('Erro ao atualizar $tabela.');</script>";
            }
        }
    }

    foreach ($tabelas as $tabela) {
        echo "<h2>$tabela</h2>";

        $stmt = $conexao->query("SELECT * FROM $tabela");
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($registros) {
            echo "<table>";
            echo "<tr>";
            foreach (array_keys($registros[0]) as $coluna) {
                echo "<th>$coluna</th>";
            }
            echo "<th>Ações</th></tr>";

            foreach ($registros as $registro) {
                echo "<tr>";
                foreach ($registro as $valor) {
                    echo "<td>" . htmlspecialchars($valor) . "</td>";
                }

                echo "<td><form method='POST'>";
                echo "<input type='hidden' name='tabela' value='$tabela'>";
                echo "<input type='hidden' name='id' value='" . $registro[array_key_first($registro)] . "'>";

                if ($tabela === 'Produtos') {
                    echo "<input type='text' name='nome' value='" . htmlspecialchars($registro['Nome']) . "' required>";
                    echo "<input type='number' step='0.01' name='preco' value='" . $registro['Preco'] . "' required>";
                    echo "<input type='hidden' name='categoria' value='" . $registro['ID_Categorias'] . "' required>";
                    echo "<input type='hidden' name='cor' value='" . $registro['ID_Cores'] . "' required>";
                    echo "<input type='hidden' name='tamanho' value='" . $registro['ID_Tamanhos'] . "' required>";
                    echo "<input type='number' name='qtd' value='" . $registro['Qtd'] . "' required>";
                } elseif (in_array($tabela, ['Categorias', 'Cores', 'Tamanhos'])) {
                    echo "<input type='text' name='nome' value='" . htmlspecialchars($registro['Nome']) . "' required>";
                }

                echo "<button type='submit' name='editar'>Salvar</button>";
                echo "</form></td>";
                echo "</tr>";
            }
            echo "</table><br>";
        } else {
            echo "<p>Nenhum registro encontrado para $tabela.</p>";
        }
    }
    ?>
  </div>
</body>
</html>
