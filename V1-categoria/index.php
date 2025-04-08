<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Categorias</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include('navbar.php'); ?>
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Lista de Categorias
              <a href="COLOCAR AQUI A CONEXÃO COM A TABELA SQL" class="btn btn-primary float-end">Adicionar Categoria</a> <!--Se atentem aqui-->
            </h4>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Camiseta P</td>
                  <td>
                    <a href="" class="btn btn-secondary btn-sm">Visualizar</a>
                    <a href="" class="btn btn-success btn-sm">Editar</a>
                    <form action="" method="POST" class="d-inline">
                      <button type="submit" name="delete_usuario" value="1" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Moletom G</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Calça M</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>