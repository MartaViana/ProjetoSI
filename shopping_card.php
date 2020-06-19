<?php
require_once("bd.php");
session_start();
$_SESSION['message'] = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['id'])) {
    $id=$_POST['id'];

        $res = pg_query($connection, "SELECT preco FROM book WHERE id = '$id'") or die;
        $res_arr=pg_fetch_assoc($res);
        $preco=$res_arr['preco'];

        $cliente=$mail=$_SESSION['useremail'];

        //////adiciona ao carrinho de compras
        $resultados2 = pg_query($connection, "INSERT INTO bookorder (client_usuario_mail, book_id,preco)" .
            "Values('$cliente','$id','$preco')") or die;
        $_SESSION['message'] = 'adicionado ao carrinho';
        header('Location: '.$_SERVER['HTTP_REFERER']);

    }

}
