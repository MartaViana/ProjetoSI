<?php
session_start();
require_once("bd.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Estatisticas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="pagprincipal.php"><img src="icones/logo.png" alt="LOGO"
                                                                 style="width:120px;height:50px;"></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="icones"><a href="addbook.php"><img src="icones/add.png" alt="ADD"
                                                          style="width:25px;height:25px;"></a></li>
            <li class="icones"><a href="pagprincipalAdmin.php"><img src="icones/main_page.png" alt="MAIN PAGE"
                                                                    style="width:30px;height:30px;"></a></li>
            <li class="icones"><a href="estatisticas.php"><img src="icones/esta.png" alt="MAIN PAGE"
                                                               style="width:30px;height:30px;"></a></li>

        </ul>
        <h1 class="ola"><a href="log_out.php">Logout</a></h1>

    </div>
</nav>


<div class="container">

    <h2>Total de livros:
        <?php
        require_once("bd.php");

        ///////conta o total de livro na bd
        $result = pg_query($connection, "SELECT * FROM book") or die;
        $row = pg_num_rows($result);

        echo $row;
        ?>
    </h2>

    <h2>Valor de vendas por dia:    </h2>

        <?php
        ///////vai buscar o valor total de vendas por dia no espaço de um mês
        $result = pg_query($connection, "SELECT date_trunc('day', datac) AS \"dia\", sum(preco) AS \"total\"
                                                FROM bookorder
                                                WHERE datac > now() - interval '1 month' 
                                                GROUP BY dia
                                                ORDER BY dia;") or die;
        $row = pg_fetch_all($result);

        ?>
    <table style="width: 30%">
        <tr>
            <th>Dia</th>
            <th>Total</th>
        </tr>
        <?php foreach ($row as $key => $rows): ?>

        <tr>
            <td><?php echo $rows['dia']?></td>
            <td><?php echo $rows['total']?>€</td>
        </tr>
        <?php endforeach;?>
    </table>

        <h2>Média que cada utilizador gasta:
    <?php
    $res = pg_query($connection, "SELECT avg(preco)  FROM bookorder where finalizado=true group by client_usuario_mail") or die;
    $res_arr=pg_fetch_assoc($res);
    $value=  $res_arr['avg'];
    echo round($value,2);
    ?>
          €</h2>

    <h2>Livros mais vizualizados:

    </h2><?php
    $reso = pg_query($connection, " Select client_book.book_id, book.titulo, Count(book_id) 
    as ordem 
    from client_book 
    left join book on client_book.book_id=book.id
    group by client_book.book_id, book.id
    Order By Count(client_book.book_id) Desc;") or die;
    $reso_arr=pg_fetch_all($reso);
$count=1;
?>
    <table style="width: 30%">
        <tr>            <th></th>

            <th>Título</th>

        </tr>
        <?php foreach ($reso_arr as $key => $rowo): ?>

            <tr><td><?php echo $count?>º mais visto</td>
                <td><?php echo $rowo['titulo']?></td>
            </tr>
        <?php         $count++;
        endforeach;?>
    </table>

    <h2>Total de comentários:

        <?php
        $rescm = pg_query($connection, "select * from coment ") or die;
        $rowc= pg_num_rows($rescm) ;
        echo $rowc
        ?>
        </h2>
</body>
