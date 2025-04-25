<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <!-- <link rel="stylesheet" href="../CSS/adicionarProdutosAdm.css"> -->

    <link rel="stylesheet" href="./CSS/adicionarProdutosAdm.css">
</head>

<body>
    <!--Form action-->
    <form action="" method="post" class="form-control">
        <?php 

            //Conectando o banco 
            // require_once('conexao.php');
           //include_once('conexao.php');
            //Corrigindo erro de váriaveis não definidas
            $itemNome = "";
            $outroTamanho = "";
            $corSelecionada = "";
        ?>
        <div class="container">
            <h2>Gerenciar Loja</h2>

            <!-- Seletor de Categoria -->
            <label for="categoriaSelect">Categoria:</label>
            <select id="categoriaSelect" onchange="mostrarCampoCategoriaPersonalizada()">
                <option value="Calça">Calça</option>
                <option value="Camisa">Camisa</option>
                <option value="Camiseta">Camiseta</option>
                <option value="Boné">Boné</option>
                <option value="Sapato">Sapato</option>
                <option value="Outros">Outros</option>
            </select>

            <!-- Input opcional para nova categoria -->
            <input type="text" id="novaCategoriaInput" placeholder="Digite nova categoria"
                style="display:none; margin-top: 5px;" value="<?= $novaCategoria ?>" />

            <input type="text" id="itemNome" placeholder="Nome do item" value=" <?= $itemNome?>">

            <select id="tamanho" onchange="mostrarCampoOutro('tamanho')">
                <option value="">-- Tamanho --</option>
                <option value="P<?php if($tamanhoSelecionado == 'P') echo 'selected'?>;">P</option>
                <option value="M <?php if($tamanhoSelecionado == 'M') echo 'selected'?>;">M</option>
                <option value="G <?php if($tamanhoSelecionado == 'G') echo 'selected'?>;">G</option>
                <option value="GG <?php if($tamanhoSelecionado == 'GG') echo 'selected'?>;">GG</option>
                <option value="outro">Outro</option>
            </select>
            <input type="text" id="outroTamanho" placeholder="Digite o tamanho"
                style="display:none; margin-top: 5px;" <?= $outroTamanho ?>; />

            <select id="cor" onchange="mostrarCampoOutro('cor')">
                <option value="">-- Cor --</option>
                <option value="Azul" <?php if($corSelecionada == 'Azul') echo 'selected'?>;>Azul</option>
                <option value="Verde Oliva" <?php if($corSelecionada == 'Verde Oliva') echo 'selected'?>; >Verde Oliva</option>
                <option value="Preto"<?php if($corSelecionada == 'Preto') echo 'selected'?>;>Preto</option>
                <option value="outro">Outro</option>
            </select>
            <input type="text" id="outraCor" placeholder="Digite a cor" style="display:none; margin-top: 5px;" value = "<?= $outraCor ?>" />

            <input type="number" id="quantidadeProduto" placeholder="Quantidade" min="1" value="1" value = "<?= $quantidade ?>">

            <input type="file" id="imagemProduto" accept="image/*" placeholder="Nome do item">

            <button onclick="adicionarItem()">Adicionar Item</button>
             <!-- <button formaction="cadastrar_item.php" value="cadastrar">Adicionar Item</button> -->

            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Tamanho</th>
                        <th>Cor</th>
                        <th>Quantidade</th>
                        <th>Imagem</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody id="listaItens"></tbody>
            </table>
        </div>

    </form>
    <script src="./JavaScript/adcProdutos.js"></script>
    <!--Caso a página adcProdutos.php esteja na pasta php usar a linha de baixo-->
     <!-- <script src="../JavaScript/adcProdutos.js"></script> -->
</body>

</html>