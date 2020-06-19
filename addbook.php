<?php
session_start();
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Novo livro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

</head>
<body style="background-color: rgba(153, 204, 102, 0.4)">

<div class="container">
    <div class="page-header">
        <h1> ADICIONAR LIVRO</h1>
    </div>
</div>
<form class="form-horizontal" action="add.php" method="post" enctype="multipart/form-data" >
    <div class="alert alert error"><? echo $_SESSION['message'];
        $_SESSION['message'] = null; ?></div>
    <br>

    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Titulo:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="pwd" placeholder="Inserir titulo" name="titulo"
                   required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="usr">Autor:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="usr" placeholder="Inserir autor" name="autor" required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd1">Editora:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="pwd1" placeholder="Inserir editora" name="editora"
                   required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd1">Ano:</label>
        <div class="col-sm-10">
            <input type="number" min="1900" max="2018" class="form-control" id="pwd1" placeholder="Inserir ano" name="ano"
                   required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Preco:</label>
        <div class="col-sm-10">
            <input class="form-control" id="pwd" placeholder="Inserir preco" name="preco"
                   required>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Descrição:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" placeholder="Inserir descricao" name="descricao"
                   required>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Capa do livro:</label>
        <div class="col-sm-10">
            <input type="file" accept="image/*" name="capa" required  />
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Pdf do livro:</label>
        <div class="col-sm-10">
            <input type="file"  name="pdf" required  />
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Adicionar</button>
        </div>
    </div>
</form>
</body>