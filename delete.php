<?php
require_once("bd.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        /////vai buscar todos os livrosque foram vizualizados
        $result = pg_query($connection, "SELECT * FROM client_book WHERE book_id = '$id'") or die;
        $row = pg_num_rows($result);

        /////se a query tiver resultados e livro ja foi vizualizado e nao pode apagar
        if ($row >= 1) {
            $_SESSION['message'] = 'Não pode apagar este livro';
            header('Location: '.$_SERVER['HTTP_REFERER']);

        } else {
            pg_query($connection, "DELETE FROM book  WHERE id=$id");
            header('Location: pagprincipalAdmin.php');
            $_SESSION['message'] = 'Livro apagado';

        }
    }

}




