<?php
session_start();
$_SESSION['message'] = null;

require_once ("bd.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = pg_escape_string($_POST['username']);
    $email = pg_escape_string($_POST['email']);
    $password = pg_escape_string($_POST['password']);
    $confirmpassword = pg_escape_string($_POST['confirmpassword']);

    if (isset($_POST['notificacoes'])) {
        $notificacoes = 1;
    } else {
        $notificacoes = 0;

    }

    if ($password === $confirmpassword) {

        $result = pg_query($connection, "SELECT usuario_mail FROM client WHERE usuario_mail = '$email' ");
        $result_arr = pg_fetch_array($result);

        if($result_arr==false){

            //guadar dados na sessao
            $_SESSION['username'] = $username;
            $_SESSION['usersaldo'] = '10';
            $_SESSION['useremail'] = $email;
            $_SESSION['userpass'] = $password;
            $_SESSION['notificacoes'] = $notificacoes;


            $resultados = pg_query($connection, "INSERT INTO client (nome, saldo,noticacoes, usuario_mail, usuario_password)" .
                "Values('$username','40','$notificacoes','$email',$password)") or die;


            header("location:pagprincipal.php");

        } else {
            header("location:index.php");
            $_SESSION['message'] = 'A conta já existe';
        }

    } else {
        $_SESSION['message'] = "As passwords não são iguais";
        header("location:index.php");


    }
}

?>