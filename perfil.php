<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Perfil</title>
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
            <li class="icones"><a href="livros_comprados.php"><img src="icones/arquivo.png" alt="CARRINHO" style="width:30px;height:30px;"></a></li>


        </ul>
        <h1 class="ola">Olá, <?php echo $_SESSION['username']; ?></h1>
        <h1 class="ola"><a href="log_out.php">Logout</a></h1>

    </div>
</nav>

    <div class="container">
        <h2>Nome: <?php echo $_SESSION['username']; ?> </h2>
        <h2>Email: <?php echo $_SESSION['useremail']; ?> </h2>
        <h2>Notificações: </h2>
        <form action="perfil.php" method="post" name="notification" id="notification">
            <?php
            require_once("bd.php");
            $user = $_SESSION['useremail'];


            ////vai buscar o valor de notificaçoes do utilizador
            $result1 = pg_query($connection, "SELECT noticacoes FROM client WHERE usuario_mail = '$user' ");
            $result_arr1 = pg_fetch_assoc($result1);
            $value = $result_arr1['noticacoes'];

            if ($value === 'f'){
            ?>

            <label class="switch">
                <input form="notification" type="checkbox" name="notification"></label>

            <p class="switch2"><?php
                echo "off";
                }
                else {
                ?></p>

            <label class="switch2">
                <input form="notification" type="checkbox" name="notification" checked></label>
            <p style="font-size: 25px" class="switch"><?php
                echo "on";

                }

                ?></p>

            <button class="alterar" type="submit" value="Submit">Alterar</button>
        </form>
        <?php
        require_once("bd.php");
        $user = $_SESSION['useremail'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['notification'])) {
                $notificacoes = 't';
                $resultados = pg_query($connection, "UPDATE client set noticacoes ='$notificacoes' WHERE usuario_mail='$user'") or die;
                header("location:perfil.php");


            }
            if (!isset($_POST['notification'])) {
                $notificacoes = 'f';
                $resultados = pg_query($connection, "UPDATE client set noticacoes ='$notificacoes' WHERE usuario_mail='$user'") or die;
                header("location:perfil.php");

            }
        }
        ?>

        <h2>Saldo: <?php
            require_once("bd.php");

            $user = $_SESSION['useremail'];
            $result = pg_query($connection, "SELECT saldo FROM client WHERE usuario_mail = '$user'") or die;
            $result_arr = pg_fetch_array($result);
            echo $result_arr["saldo"] . '€';
            ?>  </h2>


    </div>

</body>
</html>