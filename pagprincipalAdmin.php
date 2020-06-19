<?php
session_start();
require_once ("bd.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Comics</title>
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
            <a class="navbar-brand" href="pagprincipalAdmin.php"><img src="icones/logo.png" alt="LOGO" style="width:120px;height:50px;"></a>
        </div>
        <form class="navbar-form navbar-left" action="pagprincipalAdmin.php" method="post">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" name="search">
                <div class="input-group-btn">
                    <button id="search" class="btn btn-default" type="submit" value="search">
                        <img src="icones/search.png" alt="SEARCH" style="width:30px;height:30px;">
                    </button>
                </div>
            </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li class="icones"><a href="addbook.php"><img src="icones/add.png" alt="ADD" style="width:25px;height:25px;"></a></li>
            <li class="icones"><a href="pagprincipalAdmin.php"><img src="icones/main_page.png" alt="MAIN PAGE" style="width:30px;height:30px;"></a></li>
            <li class="icones"><a href="estatisticas.php"><img src="icones/esta.png" alt="MAIN PAGE" style="width:30px;height:30px;"></a></li>

        </ul>
        <h1 class="ola"> <a href="log_out.php">Logout</a></h1>

    </div>
</nav>

<div class="alert alert error"><?
    echo $_SESSION['message'];
    $_SESSION['message'] = null; ?></div>
<br>


<div class="ordenar">
    <div class="grid-container">
        <form action="pagprincipalAdmin.php" method="post">
            <h3>Ordenar por: </h3>
            <div class="grid-item item1">
                <div class="form-group">
                    <label for="ordem" class="col-sm-2 control-label"></label>
                    <select class="form-control" id="ordem" name="ordem">
                        <option value="precoa">Preço (ascendente)</option>
                        <option value="precos">Preço (descendente)</option>
                        <option value="az">A-Z</option>
                        <option value="za">Z-A</option>
                    </select></div>
                <div class="grid-item item2">
                    <div class="ordenar2"><input type="submit" name="formsubmit" class="btn btn-primary" value="Ordenar"></div>
                </div>
        </form>
    </div>
</div>

<div class="grid-container">

    <?php
    ///////////////////////////////order/////////////////////////////////

    require_once("bd.php");
    if (isset($_POST['formsubmit'])) {
        $option = $_POST['ordem'];
        ////////////opçao preço ancentente selecionada
        if ($option === 'precoa') {
            $result = pg_query($connection, "SELECT * FROM book order by preco desc");
            $result_arr = pg_fetch_all($result);

            ////////////opçao az selecionada
        } else if ($option === 'az') {
            $result = pg_query($connection, "SELECT * FROM book order by titulo");
            $result_arr = pg_fetch_all($result);

        }
        ////////////opçao preço descendente selecionada
        else if ($option === 'precos') {
            $result = pg_query($connection, "SELECT * FROM book order by preco asc");
            $result_arr = pg_fetch_all($result);

            ////////////opçao za selecionada
        } else if ($option === 'za') {
            $result = pg_query($connection, "SELECT * FROM book order by titulo desc ");
            $result_arr = pg_fetch_all($result);

        }
    }
    ///////////////////////////////search/////////////////////////////////

    if (isset($_POST['search'])) {
        $search = pg_escape_string($_POST['search']);

        ////////////procura em book o texto que o user escreveu no search
        $result = pg_query($connection, "SELECT * FROM book WHERE autor like '%$search%' OR
    descricao like '%$search%' OR
    titulo like '%$search%' OR
    editora like '%$search%'") or die;

        $row = pg_num_rows($result);
        if ($row === 0) {
            $_SESSION['message'] = 'Não foram encontrados resultados';
            $result_arr = pg_fetch_all($result);

        } else {
            $result_arr = pg_fetch_all($result);
        }
    }

    ///////////ir buscar todos os livros po default
    if (!isset($_POST['search']) && !isset($_POST['formsubmit'])) {
        $result = pg_query($connection, "SELECT * FROM book ");
        $result_arr = pg_fetch_all($result);
    }

    ?>


    <?php
    ///////////////////////////////dispor todos os livros////////////////////////////////

    if ($result_arr != false) {
        foreach ($result_arr as $key => $book): ?>

            <div class="grid-item">
                <div class="capa">
                    <a href="vislivroadmin.php?id=<?php echo $book['id']; ?>">
                        <img class="capa_i" src="<?php echo $book['imagem']; ?>">
                    </a>
                </div>
                <div class="info">
            <span class="titulo"> <?php echo $book['titulo']; ?> <br>
            <p class="autor"><?php echo $book['autor']; ?></p></span>
                    <span class="preço"> <br><?php echo $book['preco'] . "€"; ?></span>
                </div>

            </div>
        <?php endforeach;
    } ?>

</div>


</body>
