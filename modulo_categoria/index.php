<?php
require_once 'conexao.php';

// Busca os dados do banco de dados
try {
    $query = $conexao->query("SELECT id, nome FROM categorias");
    $categorias = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Categorias</title>
  <link rel="stylesheet" href="style.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <div class="container">
    <div class="header">
      <span>Gerenciar Categorias</span>
      <button onclick="openModal()" id="new" type="button">Adicionar Categoria</button>
    </div>

    <div class="divTable">
      <table>
        <thead>
          <tr>
            <th>Nome da Categoria</th>
            <th class="acao">Editar</th>
            <th class="acao">Excluir</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categorias as $categoria): ?>
            <tr>
              <td><?= htmlspecialchars($categoria['nome']) ?></td>
              <td class="acao">
                <button type="button" onclick="editItem(<?= $categoria['id'] ?>, '<?= htmlspecialchars($categoria['nome']) ?>')">
                  <i class='bx bx-edit'></i>
                </button>
              </td>
              <td class="acao">
                <form method="POST" action="excluir_categoria.php" style="display: inline;">
                  <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
                  <button type="submit" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                    <i class='bx bx-trash'></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="modal-container">
      <div class="modal">
        <form method="POST" action="salvar_categoria.php">
          <input type="hidden" id="m-id" name="id" />
          <label for="m-nome">Nome da Categoria</label>
          <input id="m-nome" name="nome" type="text" required />

          <button id="btnSalvar" type="submit">Salvar</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const modal = document.querySelector('.modal-container');
    const inputId = document.querySelector('#m-id');
    const inputNome = document.querySelector('#m-nome');
    const btnNew = document.querySelector('#new');

    function openModal() {
      inputId.value = '';
      inputNome.value = '';
      modal.style.display = 'flex';
    }

    function editItem(id, nome) {
      inputId.value = id;
      inputNome.value = nome;
      modal.style.display = 'flex';
    }

    function closeModal() {
      modal.style.display = 'none';
    }

    if (btnNew) {
      btnNew.addEventListener('click', openModal);
    }

    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-container')) {
          closeModal();
        }
      });
    }
  </script>
</body>

</html>