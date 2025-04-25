<!-- <?php
//Colocar o arquivo de conexão do banco de dados
//include_once ou require_once('arquivo de conexão.php');

require_once('conexao.php');

//nome do item, tamanho do item, cor do item e quantidade (nessa ordem)
if ($_POST) {
  empty($_POST["$itemNome"]) || empty($_POST["$tamanoSelecionado"]) || empty($_POST["$corSelecionada"]) || empty($_POST["$quantidade"]);
  //Validações para caso alguém tente cadastrar com o campo vazio  
  if (empty($itemNome)) {
    echo "<font color = 'red'> Erro, Preencha o nome do item que deseja cadastrar!</font>";
  }

  if (empty($tamanoSelecionado)) {
    echo "<font color = 'red'> Erro, selecione o tamanho do item</font>";
  }

  if (empty($corSelecionada)) {
    echo "<font color = 'red'> Erro, selecione a cor do item</font>";
  }

  if (empty($quantidade) || $quantidade <= 0) {
    echo "Digite uma quantidade válida";
  }

  else {
    //Atribuindo dados enviados para váriaveis
    $itemNome = $_POST['itemNome'];
    $tamanoSelecionado = $_POST['tamanho'];
    //talvez precise do id do outro tamanho
    $corSelecionada = $_POST['cor'];
    //talvez precise do id da outra cor
    $quantidade = $_POST['quantidadeProduto'];
  }
  // try {
  //   $sql = $conn->prepare("insert into Produtos(Nome,ID_Tamanhos,ID_Cores,Qtd) ) value(:Nomes, :ID_Tamanhos, ID_Cores, Qtd"); //colocar os dados do banco
  
  //   // $sql ->execute(array()); 
  // //   //colocar colunas e váriaveis
  // //   ':Nome' => $itemNome,
  // //   'ID_Tamanhos' => $tamanoSelecionado,
  // //   'ID_Cores' => $corSelecionada,
  // //   'Qtd' => $quantidade
  
  //   $sql->execute([
  //     ':Nome' => $itemNome,
  //     ':ID_Tamanhos' => $tamanoSelecionado,
  //     ':ID_Cores' => $corSelecionada,
  //     ':Qtd' => $quantidade
  //   ]);
  
  //   if ($sql->rowcount() >= 1) {
  //     echo '<p> Dados cadastrados </p>';
  //   } else {
  //     echo '</p> Algo deu errado </p>';
  //   }
  // } catch (PDOException $ex){
  //   echo $ex->getMessage();
  // } //echo $ex->getMessage() 
  //mensagem de erro de conexão
  //talvez vai precisar mudar essa váriavel
  
  
  // Se eu rodar o código de baixo quebra o front
  
  try {
    $sql = $conexao->prepare("INSERT INTO Produtos (Nome, ID_Tamanhos, ID_Cores, Qtd) VALUES (:Nome, :ID_Tamanhos, :ID_Cores, :Qtd)");
  
    $sql->execute([
        ':Nome' => $itemNome,
        ':ID_Tamanhos' => $tamanoSelecionado,
        ':ID_Cores' => $corSelecionada,
        ':Qtd' => $quantidade
    ]);
  
    if ($sql->rowCount() >= 1) {
        echo '<p style="color: green;">Dados cadastrados com sucesso!</p>';
    } else {
        echo '<p style="color: red;">Algo deu errado ao cadastrar.</p>';
    }
  
  } catch (PDOException $ex) {
    echo "<p style='color: red;'>Erro na inserção: " . $ex->getMessage() . "</p>";
  }

}else{
  echo "Foi";
  header('adcionarProdutos.php');

}
