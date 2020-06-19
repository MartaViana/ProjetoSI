<?php
session_start();
require_once("bd.php");

/////verificar se todos os campos estao preenchidos
if (isset($_POST['titulo']) && isset($_POST['autor']) && isset($_POST['editora']) && isset($_POST['ano']) && isset($_POST['preco']) && isset($_POST['descricao'])) {

    $imageFileType = strtolower(pathinfo($_FILES['pdf']['name'],PATHINFO_EXTENSION));

    $capa_path = pg_escape_string('image/' . $_FILES['capa']['name']);
    $conteudo_path = pg_escape_string('conteudo/' . $_FILES['pdf']['name']);

    /////verificar se os ficheiros submetidos sao pdf ou imagem
    if (preg_match("!image!", $_FILES['capa']['type']) && $imageFileType == "pdf") {

        if (copy($_FILES['capa']['tmp_name'], $capa_path) && copy($_FILES['pdf']['tmp_name'], $conteudo_path)){
            $titulo = pg_escape_string($_POST['titulo']);
            $autor = pg_escape_string($_POST['autor']);
            $editora = pg_escape_string($_POST['editora']);
            $ano = pg_escape_string($_POST['ano']);
            $preco = pg_escape_string($_POST['preco']);
            $descricao = pg_escape_string($_POST['descricao']);
            $capa = $capa_path;
            $conteudo = $conteudo_path;

        /////inserir na base de dados

        $resultados = pg_query($connection, "INSERT INTO book (titulo, autor, editora, ano, preco, descricao, imagem,pdf)" .
                "Values('$titulo', '$autor','$editora','$ano','$preco', '$descricao', '$capa_path','$conteudo')") or die;


            header('Location: pagprincipalAdmin.php');

        }
    } else {
        $_SESSION['message'] = "Apenas sao aceites ficheiros png, jpg e jpeg no campo capa e ficheiros pdf no campo conteúdo.";
        header('Location: addbook.php');

    }
} else {
    $_SESSION['message'] = 'Campos não preenchidos';
    header('Location: addbook.php');

}


?>




