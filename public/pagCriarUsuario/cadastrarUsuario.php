<?php
// Usar a conexão centralizada.
require_once '../conexao/conexao.php';

// Validação dos dados recebidos via POST
$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');
$nascimento = trim($_POST['nascimento'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';
$sexo = $_POST['sexo'] ?? '';
$funcao = $_POST['funcao'] ?? '';

// --- VALIDAÇÕES ---

if (empty($nome) || empty($sobrenome) || empty($nascimento) || empty($email) || empty($senha) || empty($sexo) || empty($funcao)) {
    header('Location: criaUsuario.php?status=erro_campos');
    exit;
}

if ($senha !== $confirmar_senha) {
    header('Location: criaUsuario.php?status=erro_senha');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: criaUsuario.php?status=erro');
    exit;
}

// --- CRIPTOGRAFIA DA SENHA ---
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);


// --- INSERÇÃO NO BANCO DE DADOS ---
try {
    // Usar a variável $conexao do arquivo incluído
    $sql = $conexao->prepare("INSERT INTO usuarios (nome, sobrenome, nascimento, email, senha, sexo, funcao)
                              VALUES (:nome, :sobrenome, :nascimento, :email, :senha, :sexo, :funcao)");

    $sql->execute([
        ':nome' => $nome,
        ':sobrenome' => $sobrenome,
        ':nascimento' => $nascimento,
        ':email' => $email,
        ':senha' => $senha_hash,
        ':sexo' => $sexo,
        ':funcao' => $funcao
    ]);

    // Redireciona para a página de criação com status de sucesso
    header("Location: criaUsuario.php?status=sucesso");
    exit;

} catch (PDOException $e) {
    // Agora, qualquer erro de banco de dados será tratado como um erro genérico.
    // Você pode registrar o erro real em um arquivo de log para depuração.
    // error_log($e->getMessage()); 
    header("Location: criaUsuario.php?status=erro");
    exit;
}
?>