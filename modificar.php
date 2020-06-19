<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Modificar</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="pagprincipal.php"><img src="icones/logo.png" alt="LOGO" style="width:120px;height:50px;"></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="icones"><a href="addbook.php"><img src="icones/add.png" alt="ADD" style="width:25px;height:25px;"></a></li>
            <li class="icones"><a href="pagprincipalAdmin.php"><img src="icones/main_page.png" alt="MAIN PAGE" style="width:30px;height:30px;"></a></li>
            <li class="icones"><a href="estatisticas.php"><img src="icones/esta.png" alt="MAIN PAGE" style="width:30px;height:30px;"></a></li>

        </ul>
        <h1 class="ola"> <a href="log_out.php">Logout</a></h1>

    </div>
</nav>

<?php
require_once("bd.php");

$id=pg_escape_string($_GET['id']);
$resultv = pg_query($connection, "SELECT * FROM client_book WHERE book_id = '$id'") or die;
$row = pg_num_rows($resultv);

///caso livro tenha sido vizualizado nao é possivel apagar
if ($row >= 1) {
    $_SESSION['message'] = 'Não pode modificar este livro';
    header('Location: '.$_SERVER['HTTP_REFERER']);
    ;die;


}

$result = pg_query($connection, "SELECT * FROM book where id=" . pg_escape_string($_GET['id']));
$result_arr = pg_fetch_array($result);
?>

<form class="form-horizontal" action="modificar2.php" method="post" enctype="multipart/form-data" >
    <div class="alert alert error"><? echo $_SESSION['message'];
        $_SESSION['message'] = null; ?></div>
    <br>

    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Titulo:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="pwd" placeholder="Inserir titulo" name="titulo" value="<?php echo $result_arr['titulo']; ?>"
                   required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="usr">Autor:  </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="usr" placeholder="Inserir autor" name="autor" value="<?php echo $result_arr['autor']; ?>" required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd1">Editora:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="pwd1" placeholder="Inserir editora" name="editora" value="<?php echo $result_arr['editora']; ?>"
                   required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd1">Ano:</label>
        <div class="col-sm-10">
            <input type="number" min="1900" max="2018" class="form-control" id="pwd1" placeholder="Inserir ano" name="ano" value="<?php echo $result_arr['ano']; ?>"
                   required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Preco:</label>
        <div class="col-sm-10">
            <input class="form-control"  id="email" placeholder="Inserir preco" name="preco" value="<?php echo $result_arr['preco']; ?>"
                   required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Descrição:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" placeholder="Inserir descricao" name="descricao" value="<?php echo $result_arr['descricao']; ?>"
                   required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Capa do livro:</label>
        <div class="col-sm-10">
            <input type="file" accept="image/*" name="capa" value="<?php echo $result_arr['capa']; ?>" required  />
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Pdf do livro:</label>
        <div class="col-sm-10">
            <input type="file"  name="pdf" value="<?php echo $result_arr['pdf']; ?>" required  />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">CONFIRMAR MODIFICAÇÃO</button>
        </div>
    </div>
    <input  type="hidden" id="id" name="id" value= "<?php echo pg_escape_string($_GET['id'])?>" >

</form>

<!--<!--<div class="b">-->
<!--    <button class="button buttonL"><a href="pagprincipalAdmin.php">CANCELAR</button>-->
<!----</div>-->


