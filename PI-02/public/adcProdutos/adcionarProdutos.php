<?php
require_once '../conexao/conexao.php';

try {
    $query = $conexao->query("SELECT ID_Categorias as id, Nome as nome FROM Categorias");
    $categorias = $query->fetchAll(PDO::FETCH_ASSOC);

    $queryCores = $conexao->query("SELECT Nome FROM Cores");
    $cores = $queryCores->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Erro ao buscar categorias ou cores: " . $e->getMessage());
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

        <label for="categoriaSelect">Categoria:</label>
        <select id="categoriaSelect">
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= htmlspecialchars($categoria['nome']) ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <a href="../modulo_categoria/categoria.php"><button type="button">Cadastrar Nova Categoria</button></a>

        <input type="text" id="itemNome" placeholder="Nome do item">
        <input type="number" id="precoProduto" placeholder="Preço" step="0.01" min="0" style="margin-top: 5px;"/>

        <select id="tamanho">
            <option value="">-- Tamanho --</option>
            <option value="P">P</option>
            <option value="M">M</option>
            <option value="G">G</option>
            <option value="GG">GG</option>
        </select>

        <select id="cor">
            <option value="">-- Cor --</option>
            <?php foreach ($cores as $cor): ?>
                <option value="<?= htmlspecialchars($cor) ?>"><?= htmlspecialchars($cor) ?></option>
            <?php endforeach; ?>
        </select>
        <a href="adicionarCor.php"><button type="button">Cadastrar Nova Cor</button></a>

        <input type="number" id="quantidadeProduto" placeholder="Quantidade" min="1" value="1">
        <!-- Campo de imagem apenas visual, não será salvo -->
        <input type="file" id="imagemProduto" accept="image/*">
        <button id="btnAdicionar" onclick="adicionarItem()">Adicionar Item</button>
        <button id="btnSalvarEdicao" onclick="salvarEdicao()" style="display:none;">Salvar Edição</button>
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

    <script>
        let idEditando = null;

        function adicionarItem() {
            const nome = document.getElementById('itemNome').value;
            const preco = document.getElementById('precoProduto').value;
            const categoria = document.getElementById('categoriaSelect').value;
            const tamanho = document.getElementById('tamanho').value;
            const cor = document.getElementById('cor').value;
            const qtd = document.getElementById('quantidadeProduto').value;

            if (!nome || !preco || !categoria || !tamanho || !cor || !qtd) {
                alert('Preencha todos os campos!');
                return;
            }

            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('preco', preco);
            formData.append('categoria', categoria);
            formData.append('tamanho', tamanho);
            formData.append('cor', cor);
            formData.append('qtd', qtd);

            fetch('inserirProdutos.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Produto adicionado com sucesso!');
                    limparCampos();
                    carregarProdutos();
                } else {
                    alert('Erro ao adicionar produto!');
                }
            })
            .catch(() => alert('Erro ao conectar ao servidor!'));
        }

        function carregarProdutos() {
            fetch('listarProdutos.php')
                .then(r => r.json())
                .then(produtos => {
                    const tbody = document.getElementById('listaItens');
                    tbody.innerHTML = '';
                    produtos.forEach(produto => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${produto.produto}</td>
                            <td>${produto.categoria ?? ''}</td>
                            <td>${produto.tamanho ?? ''}</td>
                            <td>${produto.cor ?? ''}</td>
                            <td>${produto.quantidade}</td>
                            <td>-</td>
                            <td>
                                <button onclick="editarProduto(${produto.id})">Editar</button>
                                <button onclick="removerProduto(${produto.id})">Excluir</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        }

        function removerProduto(id) {
            if (confirm('Deseja remover este produto?')) {
                const formData = new FormData();
                formData.append('id', id);
                fetch('removerProduto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Produto removido com sucesso!');
                        carregarProdutos();
                    } else {
                        alert('Erro ao remover produto!');
                    }
                });
            }
        }

        function editarProduto(id) {
            fetch('buscarProduto.php?id=' + id)
                .then(r => r.json())
                .then(produto => {
                    idEditando = id;
                    document.getElementById('itemNome').value = produto.produto;
                    document.getElementById('precoProduto').value = produto.preco;
                    document.getElementById('categoriaSelect').value = produto.categoria;
                    document.getElementById('tamanho').value = produto.tamanho;
                    document.getElementById('cor').value = produto.cor;
                    document.getElementById('quantidadeProduto').value = produto.quantidade;

                    document.getElementById('btnAdicionar').style.display = 'none';
                    document.getElementById('btnSalvarEdicao').style.display = 'inline-block';
                });
        }

        function salvarEdicao() {
            const nome = document.getElementById('itemNome').value;
            const preco = document.getElementById('precoProduto').value;
            const categoria = document.getElementById('categoriaSelect').value;
            const tamanho = document.getElementById('tamanho').value;
            const cor = document.getElementById('cor').value;
            const qtd = document.getElementById('quantidadeProduto').value;

            if (!nome || !preco || !categoria || !tamanho || !cor || !qtd) {
                alert('Preencha todos os campos!');
                return;
            }

            const formData = new FormData();
            formData.append('id', idEditando);
            formData.append('nome', nome);
            formData.append('preco', preco);
            formData.append('categoria', categoria);
            formData.append('tamanho', tamanho);
            formData.append('cor', cor);
            formData.append('qtd', qtd);

            fetch('editarProduto.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Produto editado com sucesso!');
                    limparCampos();
                    carregarProdutos();
                    document.getElementById('btnAdicionar').style.display = 'inline-block';
                    document.getElementById('btnSalvarEdicao').style.display = 'none';
                    idEditando = null;
                } else {
                    alert('Erro ao editar produto!');
                }
            });
        }

        function limparCampos() {
            document.getElementById('itemNome').value = '';
            document.getElementById('precoProduto').value = '';
            document.getElementById('tamanho').value = '';
            document.getElementById('cor').value = '';
            document.getElementById('quantidadeProduto').value = 1;
            document.getElementById('imagemProduto').value = '';
        }

        window.onload = carregarProdutos;
    </script>
</body>
</html>