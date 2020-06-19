<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Livro</title>
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
            <li class="icones"><a href="pagprincipal.php"><img src="icones/main_page.png" alt="MAIN PAGE" style="width:30px;height:30px;"></a></li>
            <li class="icones"><a href="perfil.php"><img src="icones/user.png" alt="USER" style="width:25px;height:25px;"></a></li>
            <li class="icones"><a href="fave.php"><img src="icones/heart.png" alt="FAV" style="width:25px;height:25px;"></a></li>
            <li class="icones"><a href="carrinho.php"><img src="icones/shopping_car.png" alt="CARRINHO" style="width:30px;height:30px;"></a></li>
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
$id = pg_escape_string($_GET['id']);

$result = pg_query($connection, "SELECT * FROM client_book WHERE client_usuario_mail = '$user'") or die;
$row = pg_num_rows($result);

/////adioona á base de dados que o utilizador visualizou o livro
if ($row === 0) {
    $visualizado = pg_query($connection, "INSERT INTO client_book (client_usuario_mail, book_id)" . "VALUES('$user','$id')");

}


$result = pg_query($connection, "SELECT * FROM book where id=" . pg_escape_string($_GET['id']));
$result_arr = pg_fetch_array($result);
?>
<div class="capa_v"><img src=" <?php echo $result_arr['imagem']; ?>" alt="Livro" style="height:350px;"></div>
<div class="info_v">
    <h2 style="font-weight: bold"> <?php echo $result_arr['titulo']; ?>
        <br> <?php echo $result_arr['autor']; ?> <br></h2>
    <h3><?php echo $result_arr['editora']; ?></h3>
    <h4><?php echo $result_arr['ano']; ?></h4>
    <h4> <?php echo $result_arr['preco'] . "€"; ?></h4>
    <h4 style="width: 80%; font-weight: normal"><?php echo $result_arr['descricao']; ?> </h4>
</div>

<?php
require_once("bd.php");
/////vai buscar a media de rate do livro
$result = pg_query($connection, "SELECT avg(rating) FROM rate where book_id=" . pg_escape_string($_GET['id']));
$result_arr = pg_fetch_array($result);

?>

<div class="b">
    <button type="submit" form="usrform" class="button buttonL">COMPRAR</button>
    <button class="button buttonL"> RATING MÉDIO : <?php echo round($result_arr['avg'],2); ?> </button>

    <form action="shopping_card.php" id="usrform" method="post">
        <input type="hidden" id="id" name="id" value="<?php echo pg_escape_string($_GET['id']) ?>">
    </form>


<!--    /*////////////////////////////////////favorito////////////////////////////////*-->
    <form action="fav.php" id="fave" method="post">
    <?php

    $user =  $_SESSION['useremail'];
    $id= pg_escape_string($_GET['id']);
    $resultf = pg_query($connection, "SELECT * FROM client_book_1 WHERE client_usuario_mail = '$user' and book_id = '$id'") or die;

    //////verifica se o utilizador tem o livro nos favoritos
    $row=pg_num_rows ($resultf);
    if ($row === 1) {
        ?>
        <button disabled type="image" class="button buttonL"><img src="icones/heart2.png" alt="FAV" style="width:25px;height:25px;"></button>
        <?php
    } else {?>
        <button type="image" class="button buttonL"><img src="icones/heart.png" alt="FAV" style="width:25px;height:25px;"></button>
        <?php
    }
    ?>
    <input type="hidden" id="id" name="id" value=<?php echo pg_escape_string($_GET['id']) ?>>
    </form>

</div>
<!--/*////////////////////////////////////rating////////////////////////////////*-->
<div class="rating">
    <div class="container">
        <h2>RATING</h2>
        <form id="usrform" action="rating.php" method="post">
            <label class="radio-inline">
                <input type="radio" name="optradio" value="1">1
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="2">2
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="3">3
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="4">4
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="5">5
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="6">6
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="7">7
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="8">8
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="9">9
            </label>
            <label class="radio-inline">
                <input type="radio" name="optradio" value="10">10
            </label>

            <input type="hidden" id="id" name="id" value="<?php echo pg_escape_string($_GET['id']) ?>">
            <div class="ratingb">
                <button type="submit" value="submit" class="button">SUBMETER</button>
            </div>
        </form>
    </div>
</div>

<!--/*////////////////////////////////////comentarios////////////////////////////////*-->

<div class="comment">
    <div class="opiniao">
        <hi><p style="font-weight: bold; font-size: 20px;"> OPINIÃO DOS LEITORES <br></hi>
    </div>
    <textarea rows="4" cols="50" name="comment" form="usrformc"> </textarea>
    <input form="usrformc" type="hidden" id="id" name="id" value="<?php echo pg_escape_string($_GET['id']) ?>">

    <form action="comentario.php" id="usrformc" method="post">
        <button type="submit" class="button buttonL">COMENTAR</button>
    </form>

    <?php
    /////vai buscar todos os comentarios
    $id=pg_escape_string($_GET['id']);
    $result = pg_query($connection, "SELECT * FROM coment where book_id=$id ");
    $result_arr = pg_fetch_all($result);
    ?>

    <?php
    if ($result_arr != false) {
        foreach ($result_arr as $key => $coment): ?>

            <div class="autor">
        <span class="autor_c"> <?php echo $coment['client_usuario_mail']; ?> <a> comentou: </a>
            </div>
            <div class="comment2"
            <div class="comment"><?php echo $coment['cometario']; ?></div>

        <?php endforeach;
    } ?>
</div>


</body>