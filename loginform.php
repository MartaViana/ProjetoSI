<?php

session_start();

require_once ("bd.php");

if(empty($_POST['email']) || empty($_POST['password'])) {
    $_SESSION['message'] = 'Campos não preenchidos';
    header('Location: login.php');
}
else {
    $mail = $_POST['email'];
    $pass = $_POST['password'];

    $result = pg_query($connection, "SELECT * FROM client WHERE usuario_mail = '$mail' AND usuario_password= '$pass' ") or die;
    $row = pg_num_rows($result);

    $resultA = pg_query($connection, "SELECT * FROM admin WHERE usuario_mail = '$mail' AND usuario_password= '$pass' ") or die;
    $rowA= pg_num_rows($resultA);

        if ($row === 1) {

            //buscar dados do utilizador á base de dados
            $row = pg_fetch_array(pg_query($connection, "SELECT * FROM client WHERE usuario_mail = '$mail'"));
            $username = $row['nome'];
            $usersaldo = $row['saldo'];
            $userEmail = $row['usuario_mail'];
            $userPass = $row['usuario_password'];
            $notificacoes = $row['noticacoes'];


            //guardas dados na sessao
            $_SESSION['username'] = $username;
            $_SESSION['usersaldo'] = $usersaldo;
            $_SESSION['useremail'] = $userEmail;
            $_SESSION['userpass'] = $userPass;
            $_SESSION['notificacoes'] = $notificacoes;



            header('Location: pagprincipal.php');

    }

    else if ($rowA === 1) {
        //buscar dados do utilizador á base de dados
        $rowA = pg_fetch_array(pg_query($connection, "SELECT * FROM admin WHERE usuario_mail = '$mail'"));
        $userEmail = $rowA['usuario_mail'];
        $userPass = $rowA['usuario_password'];


        //guardas dados na sessao
        $_SESSION['useremail'] = $userEmail;
        $_SESSION['userpass'] = $userPass;



        header('Location: pagprincipalAdmin.php');

    }
    else {
        $_SESSION['message'] = 'Nome de utilizador ou palavra passe inválidos';
        header('Location: login.php');
    }

}