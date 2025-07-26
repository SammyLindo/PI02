<?php
// Lógica para exibir mensagens de status vindas do redirecionamento
$status_message = '';
$status_type = '';
if (isset($_GET['status'])) {
    $status_map = [
        'sucesso' => ['message' => 'Usuário cadastrado com sucesso!', 'type' => 'success'],
        'erro' => ['message' => 'Ocorreu um erro. Tente novamente.', 'type' => 'danger'],
        'erro_senha' => ['message' => 'As senhas não coincidem.', 'type' => 'danger'],
        'erro_campos' => ['message' => 'Por favor, preencha todos os campos.', 'type' => 'danger']
    ];
    if (array_key_exists($_GET['status'], $status_map)) {
        $status_message = $status_map[$_GET['status']]['message'];
        $status_type = $status_map[$_GET['status']]['type'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Painel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/painel_usuario_estilo.css"> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <div class="main-container">

        <header class="main-header">
            <h1>Criar Novo Usuário</h1>
        </header>

        <?php if ($status_message): ?>
            <div class="notification-bar <?= htmlspecialchars($status_type) ?>">
                <?= htmlspecialchars($status_message) ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <form action="cadastrarUsuario.php" id="cadastroForm" method="POST">
                
                <div class="form-grid">

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" id="sobrenome" name="sobrenome" required>
                    </div>

                    <div class="form-group form-group-full">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="date" id="nascimento" name="nascimento" required>
                    </div>

                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <div class="select-wrapper">
                            <select id="sexo" name="sexo" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha (9-16 caracteres)</label>
                        <input type="password" id="senha" name="senha" required>
                        </div>

                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                    </div>

                    <div class="form-group form-group-full">
                        <label for="funcao">Função</label>
                         <div class="select-wrapper">
                            <select id="funcao" name="funcao" required>
                                <option value="" disabled selected>Selecione uma função...</option>
                                <option value="Gerente">Gerente</option>
                                <option value="Vendedor">Vendedor</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="../pag_acessos/acesso.html" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-primary">Criar Conta</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/criaUsuario.js"></script>
</body>
</html>