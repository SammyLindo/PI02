<?php
require_once '../conexao/conexao.php';

try {
    // Busca categorias com ID e Nome
    $queryCategorias = $conexao->query("SELECT ID_Categorias as id, Nome as nome FROM Categorias ORDER BY nome");
    $categorias = $queryCategorias->fetchAll(PDO::FETCH_ASSOC);

    // Busca cores com ID e Nome
    $queryCores = $conexao->query("SELECT ID_Cores as id, Nome as nome FROM Cores ORDER BY nome");
    $cores = $queryCores->fetchAll(PDO::FETCH_ASSOC);

    // Busca tamanhos com ID e Nome
    $queryTamanhos = $conexao->query("SELECT ID_Tamanhos as id, Nome as nome FROM Tamanhos");
    $tamanhos = $queryTamanhos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar dados iniciais: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link rel="stylesheet" href="../assets/css/painel_produtos_estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <header class="main-header">
            <h1>Gerenciar Produtos</h1>
            <a href="../pag_acessos/acesso.html" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para Acessos</a>
        </header>

        <div id="notification-bar" class="notification-bar" style="display: none;"></div>

        <div class="content-card">
            <h2 id="form-title">Adicionar Novo Produto</h2>
            <form id="formProduto" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">Nome do Produto</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço (R$)</label>
                        <input type="number" id="preco" name="preco" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="id_categoria">Categoria</label>
                        <div class="select-with-button select-wrapper">
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
<a href="../modulo_categoria/categoria.php" class="btn-add-inline" title="Gerenciar Categorias"><i class="fas fa-plus"></i></a>                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_tamanho">Tamanho</label>
                        <div class="select-wrapper">
                            <select id="id_tamanho" name="id_tamanho" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($tamanhos as $tamanho): ?>
                                    <option value="<?= $tamanho['id'] ?>"><?= htmlspecialchars($tamanho['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_cor">Cor</label>
                        <div class="select-with-button select-wrapper">
                            <select id="id_cor" name="id_cor" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($cores as $cor): ?>
                                    <option value="<?= $cor['id'] ?>"><?= htmlspecialchars($cor['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" id="btn-add-cor" class="btn-add-inline" title="Adicionar Nova Cor"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qtd">Quantidade em Estoque</label>
                        <input type="number" id="qtd" name="qtd" min="0" value="1" required>
                    </div>
                    <div class="form-group form-group-full">
                        <label for="imagemProduto">Imagem Principal</label>
                        <input type="file" id="imagemProduto" name="imagemProduto" accept="image/*">
                        <div id="image-preview" class="image-preview"></div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="btn-cancelar-edicao" class="btn btn-secondary" style="display: none;">Cancelar Edição</button>
                    <button type="submit" id="btn-submit" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Produto</button>
                </div>
            </form>
        </div>

        <div class="content-card">
            <h2>Produtos Cadastrados</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Cor / Tam.</th>
                            <th>Estoque</th>
                            <th class="acao-header">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="lista-produtos"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-container" id="modal-cor">
        <div class="modal">
            <h2>Adicionar Nova Cor</h2>
            <form id="form-cor">
                <div class="form-group">
                    <label for="cor-nome">Nome da Cor</label>
                    <input id="cor-nome" name="nome" type="text" required>
                </div>
                <div class="form-group">
                    <label for="cor-rgb">Código Hex (ex: #FF5733)</label>
                    <input id="cor-rgb" name="rgb" type="text" required>
                </div>
                <div class="modal-actions">
                    <button type="button" id="btn-cancelar-cor" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Cor</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/gerenciar_produtos.js"></script>
</body>
</html>