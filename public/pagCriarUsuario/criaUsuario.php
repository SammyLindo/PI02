<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <link rel="stylesheet" href="../assets/css/criaUsuario.css">
</head>
<body>

  <div class="link">
    <a href="../pag_acessos/acesso.html">Voltar para Acessos</a>
  <div class="form-container">
    <h2>Criar Usuário</h2>
    <form id="cadastroForm" method="POST" action="criaUsuario.php">
      <input type="text" name="nome" placeholder="Nome" required>
      <input type="text" name="sobrenome" placeholder="Sobrenome" required>
      <input type="date" name="nascimento" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>

      <select name="sexo" required>
        <option value="">Selecione o sexo</option>
        <option value="Masculino">Masculino</option>
        <option value="Feminino">Feminino</option>
      </select>

      <select name="funcao" required>
        <option value="">Selecione a função</option>
        <option value="Gerente">Gerente</option>
        <option value="Vendedor">Vendedor</option>
        <option value="Administrador">Administrador</option>
      </select>

      <button type="submit">Cadastrar</button>
    </form>
  </div>

  <script src="./JavaScript/criaUsuario.js"></script>
</body>
</html>