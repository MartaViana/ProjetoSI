<?php

session_start();
$_SESSION['message'] = null;
require_once("bd.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $mail = $_SESSION['useremail'];

    //////vais burcar os livros que estao no carrinho de compras
    $results = pg_query($connection, "SELECT bookorder.book_id, book.preco, bookorder.idcompra, book.id
                                                FROM bookorder 
                                                LEFT JOIN book on bookorder.book_id = book.id 
                                                where bookorder.client_usuario_mail='$user' and bookorder.finalizado=false");

    $books = pg_fetch_all($results);

    //////vais burcar o saldo atual do utilizador
    $res_s = pg_query($connection, "SELECT client.saldo
                                                FROM client
                                                LEFT JOIN bookorder on bookorder.client_usuario_mail = client.usuario_mail
                                                where bookorder.client_usuario_mail='$user' and bookorder.finalizado=false");
    $s = pg_fetch_assoc($res_s);
    $saldo = $s['saldo'];

    var_dump($saldo);

    $row = pg_num_rows($results);
    $soma_precos = 0;

    //////verifica se ha livros no carrinho
    if ($row >= 1) {
        foreach ($books as $book) {

            //////soma os preços de cada livro
            $soma_precos += $book['preco'];
            $id = $book['id'];
            var_dump($id);
            $bookorder_id = $book['idcompra'];
        }
        //////verifica se o utilizador tel saldo suficiente
        if ($saldo >= $soma_precos) {

            foreach ($books as $book) {
                $bookorder_id = $book['idcompra'];
                $id = $book['id'];
                $resultados2 = pg_query($connection, "INSERT INTO bookorder_book ( bookorder_idcompra, book_id)" .
                    "Values('$bookorder_id','$id')") or die;
            }
            $saldoatual = $saldo - $soma_precos;
            //////boolean finalizado passa a true na compra
            $res = pg_query($connection, "UPDATE bookorder set finalizado=true WHERE client_usuario_mail='$user'") or die;
            //////atualiza saldo do utilizador
            $result = pg_query($connection, "UPDATE client set saldo='$saldoatual' WHERE usuario_mail='$user'") or die;
            //////apaga dados do carrinho
            $resul = pg_query($connection, "delete FROM bookorder where client_usuario_mail=$user and finalizado=false");
            $_SESSION['message'] = 'Compra efetuada com sucesso';

            header('Location: pagprincipal.php');
        } else {
            $_SESSION['message'] = 'Saldo insuficiente';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);


    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);


}