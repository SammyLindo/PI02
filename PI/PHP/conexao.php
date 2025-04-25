 <?php
define('HOST', '127.0.0.1');
define('USUARIO', 'root');
define('SENHA', '');
define('DB', 'db_pi2');

//$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die ('Não foi possível conectar');


// Estabelece a conexão com o banco de dados
// $conexao = mysqli_connect(HOST, USUARIO, SENHA, DB);

// // Verifica se a conexão foi bem-sucedida
// if (!$conexao) {
//     die('Erro de conexão: ' . mysqli_connect_error());
// }

try {
    $conexao = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USUARIO, SENHA);
    // Ativar modo de erros
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Opcional: Definir charset
    $conexao->exec("SET NAMES 'utf8'");
    
    // Apenas para confirmar a conexão
    // echo "Conectado com sucesso!";
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// try {
//     $conexao = new PDO("sqlite:DB_PI2.db");
//     $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Erro na conexão: " . $e->getMessage());
// }
?>


