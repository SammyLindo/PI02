<?php
session_start();
//garantindo que é post
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Pegando os valores dos inputs e colocando nos inputs
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    try {
        $conn = new PDO("sqlite:../../database/DB_PI2.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Preparando o comando de busca do sql
        $sql = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        //Usando array assoativo para guardar os emails dos usuários no array
        $sql->execute([":email" => $email]);
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        //Verificação de usuário

        if ($usuario && $usuario["senha"] === $senha) {
            $_SESSION["usuario"] = $usuario;
            header("location:../pag_acessos/acesso.html");
            exit();
        } else {
            $erro = "Erro, Email ou senha incorretos";
        }
    } catch (PDOException $e) {
        $erro = "Erro ao se conectar ao banco" . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-Br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

    <header></header>
    <div class="Pai">
        <div class="Login">

            <!--script para caso algo de errado antes de qualquer ação-->
            <?php if (isset($erro)) echo "<p>$erro</p>"; ?>

            <h1>FAÇA SEU LOGIN!</h1>
            <!--Inserindo o form aqui-->
            <form method="post" class="formulario">
                <input type="email" name="email" placeholder="E-mail" required>
            
                <input type="password" name="senha" placeholder="Senha" required>

                <button onclick="">ENTRAR</button>
                <!-- <a href="../pagCriarUsuario/criaUsuario.php">Cadastre-se </a> -->
                <!--Mudar isso aqui ó-->
    
            </form>
        </div>
    </div>
    <footer></footer>
</body>

</html>