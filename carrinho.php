<?php
session_start();
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Carrinho</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://use.fontawesome.com/c560c025cf.js"></script>


    <link rel="stylesheet" href="style.css">

</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="pagprincipal.php"><img src="icones/logo.png" alt="LOGO"
                                                                 style="width:120px;height:50px;"></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="icones"><a href="pagprincipal.php"><img src="icones/main_page.png" alt="MAIN PAGE"
                                                               style="width:30px;height:30px;"></a></li>
            <li class="icones"><a href="perfil.php"><img src="icones/user.png" alt="USER"
                                                         style="width:25px;height:25px;"></a></li>
            <li class="icones"><a href="fave.php"><img src="icones/heart.png" alt="FAV" style="width:25px;height:25px;"></a>
            </li>
            <li class="icones"><a href="carrinho.php"><img src="icones/shopping_car.png" alt="CARRINHO"
                                                           style="width:30px;height:30px;"></a></li>
            <li class="icones"><a href="livros_comprados.php"><img src="icones/arquivo.png" alt="CARRINHO" style="width:30px;height:30px;"></a></li>

            <div class="box">
                <div class="notifications">
                    <i class="fa fa-bell"></i>


                    <?php
                    ////////////////////notificaçoes//////////////////////

                    require_once("bd.php");
                    $noti = $_SESSION['notificacoes'];
                    $user = $_SESSION['useremail'];

                    if ($noti !== 'f') {

                        //////burcar todos os livros vizualizados
                        $resultv = pg_query($connection, "SELECT * FROM client_book WHERE client_usuario_mail = '$user'") or die;
                        $rowv = pg_num_rows($resultv);

                        //////burcar todos os livros favoritos
                        $resultf = pg_query($connection, "SELECT * FROM client_book_1 WHERE client_usuario_mail = '$user'") or die;
                        $rowf = pg_num_rows($resultf);

                        if ($rowv >= 1 || $rowf >= 1) {
                            //////burcar todos os livros vizualizados
                            $resultv_arr = pg_fetch_assoc($resultv);
                            $idv = $resultv_arr['book_id'];

                            //////burcar todos os livros favoritos
                            $resultf_arr = pg_fetch_assoc($resultf);
                            $idf = $resultf_arr['book_id'];

                            //////burcar todos comentarios que cumprem todas as condiçoes
                            $resultc_arr = pg_fetch_all(pg_query($connection, "SELECT coment.client_usuario_mail, coment.book_id, book.titulo
                                                FROM coment
                                                LEFT JOIN book on book.id = coment.book_id 
                                                where book_id=$idf or book_id=$idv"));
                        } else {
                            $resultc_arr = null;
                        }
                    }
                    ?>
                    <ul>

                        <?php
                        $count = 0;
                        //////verificar se utilizador tem as notificaçoes ativas
                        if ($noti !== 'f') {
                            if ($resultc_arr != false) {
                                foreach ($resultc_arr as $key => $coment):
                                    $count++;
                                    ?>

                                    <li class="icon">
                                        <span class="icon"><i class="fa fa-user"></i></span>
                                        <span class="text">O utilizador <?php echo $coment['client_usuario_mail']; ?>
                                            comentou o livro <?php echo $coment['titulo']; ?></span>
                                    </li>

                                <?php endforeach;
                            } else {
                                ?>
                                <li class="icon">
                                    <span class="text">Não tem notificações </span>
                                </li><?php
                            }
                        } else {

                            ?>
                            <li class="icon">
                                <span class="text">Não tem notificações ativas </span>
                            </li><?php

                        } ?>
                    </ul>
                    <span class="num"><?php
                        echo $count;
                        ?></span>

                </div>
            </div>
        </ul>

        <h1 class="ola">Olá, <?php echo $_SESSION['username']; ?></h1>
        <h1 class="ola"><a href="log_out.php">Logout</a></h1>

    </div>
</nav>
<div class="alert alert error"><? echo $_SESSION['message'];
    $_SESSION['message'] = null; ?></div>

<?php
require_once("bd.php");

$user = $_SESSION['useremail'];
/////vai buscar á bd todos os livros que nao finalizado=false e que sejam adicionados pelo utilizador atual
$books= pg_fetch_all( pg_query($connection, "SELECT bookorder.book_id, book.titulo, book.autor, book.preco, book.imagem
                                                FROM bookorder 
                                                LEFT JOIN book on bookorder.book_id = book.id 
                                                where bookorder.client_usuario_mail='$user' and bookorder.finalizado=false"));
?>

<div class="comprab">

</div>


<div class="container" >
    <div class="row" >
        <div class="col-xs-12">
            <div class="panel panel-info" style="border-color:#99CC66;">
                <div class="panel-heading" style="background-color:#99CC66 ;">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-xs-6">
                                <h5 style="color: white"><span class="glyphicon glyphicon-shopping-cart"></span> Carrinho de compras</h5>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="panel-body">

                    <?php
                    $total=0;

                    if ($books != null) {
                    foreach ($books as $key => $book): ?>
                    <div class="row">
                        <div class="col-xs-1"><img style="height: 60px" class="img-responsive" src="<?php echo $book['imagem']; ?> ">
                        </div>
                        <div class="col-xs-4">
                            <h4 class="product-name"><strong><?php echo $book['titulo']; ?></strong></h4><h4><small><?php echo $book['autor']; ?></small></h4>
                        </div>
                        <div class="col-xs-6">
                            <div class="col-xs-8 text-right">
                                <h4><strong><?php echo $book['preco'] . "€"; ?> <span class="text-muted"></span></strong></h4>
                            </div>
                            <div class="col-xs-2">

                            </div>
                        </div>
                    </div>

                    <?php $total+=$book['preco'];
                    endforeach;
                    }
                    ?>
                </div>
                <div class="panel-footer">
                    <div class="row text-center">
                        <div class="col-xs-9">
                            <h4 class="text-right">Total <strong><?php echo $total; ?>€</strong></h4>
                        </div>
                        <div class="col-xs-3">
                            <form action="finalizar_compra.php" id="usrform" method="post">
                                <button style="background-color:#99CC66 " class="btn btn-success btn-block"  type="submit" >FINALIZAR COMPRA</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>


</html>
