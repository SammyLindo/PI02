<?php
require_once '../conexao/conexao.php';

try {
    $query = $conexao->query("SELECT ID_Categorias as id, Nome as nome FROM Categorias");
    $categorias = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../assets/css/adicionarProduto.css">
</head>
<body>
    
    <div class="link">
        <a href="../pag_acessos/acesso.html">Voltar para Acessos</a>
    </div>
    <div class="container">
        <h2>Gerenciar Loja</h2>

        <!-- Seletor de Categoria -->
        <label for="categoriaSelect">Categoria:</label>
        <select id="categoriaSelect" onchange="mostrarCampoCategoriaPersonalizada()">
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= htmlspecialchars($categoria['nome']) ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>
            <?php endforeach; ?>
            <option value="Outros">Outros</option>
        </select>

        <!-- Input opcional para nova categoria -->
        <input type="text" id="novaCategoriaInput" placeholder="Digite nova categoria" style="display:none; margin-top: 5px;"/>

        <input type="text" id="itemNome" placeholder="Nome do item">

        <select id="tamanho" onchange="mostrarCampoOutro('tamanho')">
            <option value="">-- Tamanho --</option>
            <option value="P">P</option>
            <option value="M">M</option>
            <option value="G">G</option>
            <option value="GG">GG</option>
            <option value="outro">Outro</option>
        </select>
        <input type="text" id="outroTamanho" placeholder="Digite o tamanho" style="display:none; margin-top: 5px;"/>

        <select id="cor" onchange="mostrarCampoOutro('cor')">
            <option value="">-- Cor --</option>
            <option value="Azul">Azul</option>
            <option value="Verde Oliva">Verde Oliva</option>
            <option value="Preto">Preto</option>
            <option value="outro">Outro</option>
        </select>
        <input type="text" id="outraCor" placeholder="Digite a cor" style="display:none; margin-top: 5px;"/>

        <input type="number" id="quantidadeProduto" placeholder="Quantidade" min="1" value="1">

        <input type="file" id="imagemProduto" accept="image/*" placeholder="Nome do item">
        <button onclick="adicionarItem()">Adicionar Item</button>

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

    <script src="/public/adcProdutos/adcProdutos.js"></script>
    <script>
        function mostrarCampoCategoriaPersonalizada() {
            const select = document.getElementById('categoriaSelect');
            const input = document.getElementById('novaCategoriaInput');
            if (select.value === 'Outros') {
                input.style.display = 'block';
            } else {
                input.style.display = 'none';
            }
        }
        function mostrarCampoOutro(tipo) {
            if (tipo === 'tamanho') {
                const select = document.getElementById('tamanho');
                const input = document.getElementById('outroTamanho');
                input.style.display = select.value === 'outro' ? 'block' : 'none';
            }
            if (tipo === 'cor') {
                const select = document.getElementById('cor');
                const input = document.getElementById('outraCor');
                input.style.display = select.value === 'outro' ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>