<?php
require_once '../conexao/conexao.php';

// --- LÓGICA DE PROCESSAMENTO ---

$status_message = '';
$status_type = '';

try {
    // 1. Captura a mensagem de status da URL (após um redirecionamento)
    if (isset($_GET['status'])) {
        $status_map = [
            'salvo' => ['message' => 'Categoria salva com sucesso!', 'type' => 'success'],
            'excluido' => ['message' => 'Categoria excluída com sucesso!', 'type' => 'success'],
            'erro' => ['message' => 'Ocorreu um erro na operação.', 'type' => 'danger'],
            'erro_excluir' => ['message' => 'Erro: Esta categoria contém produtos e não pode ser excluída.', 'type' => 'danger']
        ];
        if (array_key_exists($_GET['status'], $status_map)) {
            $status_message = $status_map[$_GET['status']]['message'];
            $status_type = $status_map[$_GET['status']]['type'];
        }
    }

    // 2. BUSCAR AS CATEGORIAS NO BANCO DE DADOS
    $query = $conexao->query("
        SELECT 
            c.ID_Categorias as id, 
            c.Nome as nome,
            COUNT(p.ID_Produtos) as quantidade
        FROM Categorias c
        LEFT JOIN Produtos p ON p.ID_Categorias = c.ID_Categorias
        GROUP BY c.ID_Categorias, c.Nome
        ORDER BY c.Nome ASC
    ");
    $categorias = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Erro ao conectar ou buscar dados: ' . $e->getMessage());
}

// --- ESTRUTURA HTML E APRESENTAÇÃO ---
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Categorias</title>
    <link rel="stylesheet" href="../assets/css/painel_mono_estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gerenciar Categorias</h1>
            <button id="btn-adicionar" class="btn btn-primary">
                <i class="fas fa-plus"></i> Adicionar Categoria
            </button>
        </div>

        <?php if ($status_message): ?>
            <div class="alert alert-<?= $status_type ?>">
                <?= htmlspecialchars($status_message) ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nome da Categoria</th>
                            <th>Produtos</th>
                            <th class="acao-header">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categorias)): ?>
                            <tr>
                                <td colspan="3" class="empty-state">Nenhuma categoria cadastrada ainda.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><?= htmlspecialchars($categoria['nome']) ?></td>
                                    <td><?= $categoria['quantidade'] ?></td>
                                    <td class="actions">
                                        <button class="btn btn-secondary btn-edit" 
                                                data-id="<?= $categoria['id'] ?>" 
                                                data-nome="<?= htmlspecialchars($categoria['nome']) ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <form method="POST" action="excluir_categoria.php" onsubmit="return confirm('Atenção! Excluir uma categoria é uma ação permanente. Deseja continuar?');">
                                            <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="footer-link">
                 <a href="../pag_acessos/acesso.html">Voltar para Acessos</a>
            </div>
        </div>
    </div>

    <div class="modal-container" id="modal">
        <div class="modal">
            <h2 id="modal-title">Adicionar Categoria</h2>
            <form id="modal-form" method="POST" action="salvar_categoria.php">
                <input type="hidden" id="m-id" name="id" />
                
                <div class="form-group">
                    <label for="m-nome">Nome da Categoria</label>
                    <input id="m-nome" name="nome" type="text" required autocomplete="off" />
                </div>

                <div class="modal-actions">
                    <button type="button" id="btn-cancelar" class="btn btn-secondary">Cancelar</button>
                    <button id="btn-salvar" type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal');
        const modalForm = document.getElementById('modal-form');
        const modalTitle = document.getElementById('modal-title');
        const inputId = document.getElementById('m-id');
        const inputNome = document.getElementById('m-nome');

        const openModal = (title, id = '', nome = '') => {
            modalTitle.textContent = title;
            inputId.value = id;
            inputNome.value = nome;
            modal.style.display = 'flex';
            inputNome.focus();
        };

        const closeModal = () => {
            modal.style.display = 'none';
        };

        // Event listener para abrir o modal de ADIÇÃO
        document.getElementById('btn-adicionar').addEventListener('click', () => {
            openModal('Adicionar Nova Categoria');
        });

        // Event listener para abrir o modal de EDIÇÃO (usando delegação de eventos)
        document.querySelector('tbody').addEventListener('click', (e) => {
            const editButton = e.target.closest('.btn-edit');
            if (editButton) {
                const id = editButton.dataset.id;
                const nome = editButton.dataset.nome;
                openModal('Editar Categoria', id, nome);
            }
        });

        // Event listeners para fechar o modal
        document.getElementById('btn-cancelar').addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
    </script>
</body>
</html>