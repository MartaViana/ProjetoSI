<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Livros comprados</title>
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
            <li class="icones"><a href="livros_comprados.php"><img src="icones/arquivo.png" alt="CARRINHO"
                                                                   style="width:30px;height:30px;"></a></li>
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
<div class="container">
    <?php
    require_once("bd.php");
    $user = $_SESSION['useremail'];

    $books = pg_fetch_all(pg_query($connection, "SELECT distinct 
bookorder_book.book_id,bookorder_book.bookorder_idcompra,bookorder.client_usuario_mail, bookorder.idcompra, book.titulo, book.autor, book.preco, book.imagem, book.pdf,book.id
                                                FROM bookorder_book 
                                                LEFT JOIN book on bookorder_book.book_id = book.id 
                                                LEFT JOIN bookorder on bookorder_book.bookorder_idcompra = bookorder.idcompra
                                                where bookorder_book.book_id=book.id and bookorder.client_usuario_mail='$user' "));

    ?>

    <?php if ($books != null) {
        foreach ($books as $key => $book): ?>
            <div class="book_compra">
                <img class="capa_v" src="<?php echo $book['imagem']; ?>">
                <div class="book_compra2">
            <span style="font-weight: bold"> <?php echo $book['titulo']; ?> <br>
            <p style="font-weight: normal; font-size: 19px"><?php echo $book['autor']; ?></p></span>
                    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
                    <a href="<?php echo $book['pdf'];
                    ?>" id="someID"><p style="font-size: 14px; text-decoration: underline">PDF</p></a>
                    <script>
                        $('a#someID').attr({
                            target: '_blank',
                            href: <?php echo $book['pdf'];?> });

                        // give the location of ur file... in href
                    </script>

                </div>
            </div>

        <?php endforeach;
    } else {
        ?> <h3>Ainda não adquiriu nenhum livro</h3> <?php
    }
    ?>
</div>

</body>
</html>
