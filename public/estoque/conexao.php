<?php 
 $banco = 'DB_PI2.db';

    try{
        //criando conexão com pdo
        $conexao = new PDO("sqlite:" . $banco);
        //modo de erro para exceções
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //mensagem para caso funcione
        echo "Banco conectado";
    }catch(PDOException $e){
        die("Erro na conexäo com o banco de dados". $e->getMessage());
    }
?>