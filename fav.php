<?php
require_once("bd.php");
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id'])){

    $user = $_SESSION['useremail'];
    $id = $_POST['id'];
    //////////inserir na base de dados os livros favoritos
    $resultados2 = pg_query($connection, "INSERT INTO client_book_1 ( client_usuario_mail, book_id)".
                "Values('$user','$id')") or die;

        header("location:pagprincipal.php");

    }

}