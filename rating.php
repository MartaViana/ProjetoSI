<?php
session_start();
require_once("bd.php");

if (isset($_POST['optradio']) && isset($_POST['id'])) {

    $id=$_POST['id'];
    $user= $_SESSION['useremail'];
    $result = pg_query($connection, "SELECT * FROM rate WHERE client_usuario_mail = '$user' and book_id='$id'") or die;
       $row = pg_num_rows($result);

       ////verificar se o usar ja classificou o livro
       if ($row === 1) {
           $_SESSION['message'] = 'Já classificou este livro';
           header('Location: '.$_SERVER['HTTP_REFERER']);

       } else {
           $rate= $_POST ['optradio'];
           $user= $_SESSION['useremail'];
           $id = $_POST ['id'];
           ////adicionar á base de dados rating
           $resultados = pg_query($connection, "INSERT INTO rate (rating, book_id, client_usuario_mail)" .
               "Values('$rate', '$id','$user')") or die;
           header('Location: '.$_SERVER['HTTP_REFERER']);

       }

} else {
    header('Location: '.$_SERVER['HTTP_REFERER']);
}