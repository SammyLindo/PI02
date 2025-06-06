<?php
//Conexão do banco
include('../conexao/conexao.php');

//Pegando os valores dos inputs e colocando em variáveis
$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$nascimento = $_POST['nascimento'];
$email = $_POST['email'];
$senha = $_POST['senha'];
//Ver isso aqui
$sexo = $_POST['sexo'];
$funcao = $_POST['funcao'];

try {
    
    //Corrigido (?)
    //Quando eu clico ele cria o banco
    // $conn= new PDO("sqlite:DB_PI2.db");
    $conn= new PDO("sqlite:../../database/DB_PI2.db");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Preparando envio
    $sql = $conn->prepare("INSERT INTO usuarios (nome, sobrenome, nascimento, email, senha, sexo, funcao)
            VALUES (:nome, :sobrenome, :nascimento, :email, :senha, :sexo, :funcao)
        
    ");

    //Executando
    $sql->execute([
        ':nome' => $nome,
        ':sobrenome' => $sobrenome,
        ':nascimento' => $nascimento,
        ':email' => $email,
        ':senha' => $senha,
        ':sexo' => $sexo,
        ':funcao' => $funcao
    ]);

    //Mensagem para confirmar que o usuário foi cadastrado

    echo "Usuário cadastrado";
    
    header("location:criaUsuario.php");
}catch(PDOException $ex){
    //Caso de algo errado
    echo $ex->getMessage();
}
?>