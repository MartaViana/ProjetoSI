<?php
session_start();
require_once("bd.php");

if (isset($_POST['titulo']) && isset($_POST['autor']) && isset($_POST['editora']) && isset($_POST['ano']) && isset($_POST['preco']) && isset($_POST['descricao']  )) {
echo "1";
    $imageFileType = strtolower(pathinfo($_FILES['pdf']['name'],PATHINFO_EXTENSION));
    $capa_path = pg_escape_string('image/' . $_FILES['capa']['name']);
    $conteudo_path = pg_escape_string('conteudo/' . $_FILES['pdf']['name']);


    if (preg_match("!image!", $_FILES['capa']['type']) && $imageFileType == "pdf") {
        echo "2";

        if (copy($_FILES['capa']['tmp_name'], $capa_path) && copy($_FILES['pdf']['tmp_name'], $conteudo_path)){
            $titulo = pg_escape_string($_POST['titulo']);
            $autor = pg_escape_string($_POST['autor']);
            $editora = pg_escape_string($_POST['editora']);
            $ano = pg_escape_string($_POST['ano']);
            $preco = pg_escape_string($_POST['preco']);
            $descricao = pg_escape_string($_POST['descricao']);
            $capa = $capa_path;
            $conteudo = $conteudo_path;
            $id = $_POST['id'];
            echo "3";

            $res = pg_query($connection, "SELECT preco FROM book WHERE id = '$id'");
            $res = pg_fetch_assoc($res);
            $precoanterior = $res['preco'];

            var_dump($precoanterior);

            $resultados =pg_query ($connection,"UPDATE book set titulo ='$titulo', autor = '$autor', editora = '$editora', ano = '$ano', preco = '$preco', descricao = '$descricao', imagem = '$capa', pdf = '$conteudo' WHERE id='$id'") or die;

            if($preco!=$precoanterior){
                echo '4';
                $dia= date("Y/m/d");
                $admin= $_SESSION['useremail'];
                 $historico = pg_query($connection, "INSERT INTO historico ( dia,valor, book_id, admin_usuario_mail)" .
                "Values('$dia','$preco','$id','$admin')") or die;
            }
;
            header('Location: pagprincipalAdmin.php');

        }

    } else {
        $_SESSION['message'] = "Apenas sao aceites ficheiros png, jpg e jpeg no campo capa e ficheiros pdf no campo conteúdo.";
        header('Location: '.$_SERVER['HTTP_REFERER']);

    }
} else {
    $_SESSION['message'] = 'Campos não preenchidos';
    header('Location: '.$_SERVER['HTTP_REFERER']);

}
?>
