<?php
require_once '../conexao/conexao.php'; // Mantém sua conexão centralizada

// --- LÓGICA DE PROCESSAMENTO ---

$status_message = '';
$status_type = '';

try {
    // 1. PROCESSAR A EXCLUSÃO (MÉTODO POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
        $tabela = $_POST['tabela'];
        $id = $_POST['id'];
        $coluna_id = $_POST['coluna_id'];

        $stmt = $conexao->prepare("DELETE FROM `$tabela` WHERE `$coluna_id` = ?");
        if ($stmt->execute([$id])) {
            header("Location: estoque.php?tabela_selecionada=$tabela&status=excluido");
            exit;
        } else {
            header("Location: estoque.php?tabela_selecionada=$tabela&status=erro");
            exit;
        }
    }
    
    // Captura a mensagem de status da URL
    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'excluido') {
            $status_message = 'Registro excluído com sucesso!';
            $status_type = 'success';
        } elseif ($_GET['status'] === 'editado') {
            $status_message = 'Registro atualizado com sucesso!';
            $status_type = 'success';
        } elseif ($_GET['status'] === 'erro') {
            $status_message = 'Ocorreu um erro na operação.';
            $status_type = 'danger';
        }
    }

    // 2. LISTAR TODAS AS TABELAS PARA A NAVEGAÇÃO
    $tabelasQuery = $conexao->query("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'");
    $todas_tabelas = $tabelasQuery->fetchAll(PDO::FETCH_COLUMN);

    // 3. DETERMINAR A TABELA SELECIONADA E BUSCAR SEUS DADOS
    $tabela_selecionada = null;
    $dados_tabela = [];
    $colunas_tabela = [];
    $coluna_id_pk = null;

    if (isset($_GET['tabela_selecionada']) && in_array($_GET['tabela_selecionada'], $todas_tabelas)) {
        $tabela_selecionada = $_GET['tabela_selecionada'];
    } elseif (!empty($todas_tabelas)) {
        $tabela_selecionada = $todas_tabelas[0];
    }
    
    if ($tabela_selecionada) {
        $dataQuery = $conexao->query("SELECT * FROM `$tabela_selecionada`");
        $dados_tabela = $dataQuery->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($dados_tabela)) {
            $colunas_tabela = array_keys($dados_tabela[0]);
            
            $pkQuery = $conexao->query("PRAGMA table_info(`$tabela_selecionada`)");
            $table_info = $pkQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($table_info as $column_info) {
                if ($column_info['pk'] > 0) {
                    $coluna_id_pk = $column_info['name'];
                    break;
                }
            }
        }
    }

} catch (PDOException $e) {
    die("Erro ao acessar o banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senaté Couture - Painel de Estoque</title>
    <link rel="stylesheet" href="admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                Velours Marcas
                <i class="fas fa-cubes icon"></i>
            </h2>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($todas_tabelas as $tabela_nav): ?>
                <a href="?tabela_selecionada=<?= urlencode($tabela_nav) ?>" 
                   class="<?= ($tabela_nav === $tabela_selecionada) ? 'active' : '' ?>">
                    <i class="fas fa-table"></i>
                    <?= htmlspecialchars($tabela_nav) ?>
                </a>
            <?php endforeach; ?>
            <hr>
            <a href="../pag_acessos/acesso.html" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> 
                Voltar para Acessos
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <h1>
                <i class="fas fa-boxes icon"></i>
                Painel de Controle de Estoque
            </h1>
        </header>

        <?php if ($status_message): ?>
            <div class="alert alert-<?= $status_type ?>">
                <i class="fas fa-<?= $status_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?> icon"></i>
                <?= htmlspecialchars($status_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($tabela_selecionada): ?>
            <div class="content-card">
                <h2 class="table-title">
                    <i class="fas fa-database icon"></i>
                    Gerenciando Tabela: <strong><?= htmlspecialchars($tabela_selecionada) ?></strong>
                </h2>

                <?php if (!empty($dados_tabela) && !empty($colunas_tabela) && $coluna_id_pk): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <?php foreach ($colunas_tabela as $coluna): ?>
                                        <th><?= htmlspecialchars($coluna) ?></th>
                                    <?php endforeach; ?>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="dados-tabela">
                                <?php foreach ($dados_tabela as $linha): ?>
                                    <tr>
                                        <?php foreach ($linha as $coluna => $celula): ?>
                                        <?php
                                            $keywords_senha = ['senha', 'password', 'pass', 'pwd'];
                                            $is_coluna_senha = false;
                                            foreach ($keywords_senha as $keyword) {
                                                if (stripos($coluna, $keyword) !== false) {
                                                    $is_coluna_senha = true;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <td>
                                            <?= $is_coluna_senha ? '••••••••' : htmlspecialchars($celula ?? 'N/A') ?>
                                        </td>
                                        <?php endforeach; ?>
                                        <td class="actions">
                                            <button class="btn-editar btn-abrir-modal" 
                                                    data-id="<?= htmlspecialchars($linha[$coluna_id_pk]) ?>" 
                                                    data-tabela="<?= htmlspecialchars($tabela_selecionada) ?>"
                                                    data-coluna-pk="<?= htmlspecialchars($coluna_id_pk) ?>">
                                                <i class="fa-solid fa-pencil"></i> Editar
                                            </button>

                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="tabela" value="<?= htmlspecialchars($tabela_selecionada) ?>">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($linha[$coluna_id_pk]) ?>">
                                                <input type="hidden" name="coluna_id" value="<?= htmlspecialchars($coluna_id_pk) ?>">
                                                <button type="submit" name="excluir" class="btn-excluir" 
                                                        onclick="return confirm('⚠️ Tem certeza que deseja excluir este registro?\n\nEsta ação não pode ser desfeita.');">
                                                    <i class="fa-solid fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-inbox icon"></i>
                        <p>Nenhum dado encontrado nesta tabela.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="content-card">
                <div class="no-data">
                    <i class="fas fa-database icon"></i>
                    <p>Nenhuma tabela encontrada no banco de dados ou nenhuma foi selecionada.</p>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- Modal de Edição -->
<div id="modal-edicao" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">
                <i class="fas fa-edit icon"></i>
                Editar Registro
            </h3>
            <button id="modal-close-btn" class="modal-close-btn">&times;</button>
        </div>
        <form id="form-edicao">
            <!-- Formulário será gerado dinamicamente pelo JavaScript -->
        </form>
    </div>
</div>

<script src="estoque.js"></script>
</body>
</html>
