<?php

session_start();
require_once ("bd.php");

///verificar se os campos estão preenchidos
if(isset($_POST['comment']) && isset($_POST['id'])){

    $comment=pg_escape_string($_POST['comment']);
    $mail=$_SESSION['useremail'];
    $id= $_POST['id'];

    ///inserir na base de dados os comentarios
    $resultados = pg_query($connection, "INSERT INTO coment (cometario, book_id,client_usuario_mail)" .
        "Values('$comment','$id','$mail')") or die;

    header('Location: '.$_SERVER['HTTP_REFERER']);

}else{

    header('Location: '.$_SERVER['HTTP_REFERER']);

}