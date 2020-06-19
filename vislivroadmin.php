<?php
session_start();
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Livro</title>
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

<div class="alert alert error"><? echo $_SESSION['message'];
    $_SESSION['message'] = null; ?></div>
<?php
require_once("bd.php");
/////vai buscar as informaçoes do livro
$result = pg_query($connection, "SELECT * FROM book where id=" . pg_escape_string($_GET['id']));
$result_arr = pg_fetch_array($result);
?>

<div class="capa_v"><img src=" <?php echo $result_arr['imagem']; ?>" alt="Livro" style="height:350px;"></div>
<div class="info_v">
    <h2 style="font-weight: bold"> <?php echo $result_arr['titulo']; ?>
        <br> <?php echo $result_arr['autor']; ?> <br></h2>
    <h3><?php echo $result_arr['editora']; ?></h3>
    <h4><?php echo $result_arr['ano']; ?></h4>
    <h4> <?php echo $result_arr['preco'] . "€"; ?></h4>
    <h4 style="width: 80%; font-weight: normal"><?php echo $result_arr['descricao']; ?> </h4>
</div>

<div class="b">
    <button class="button buttonL"><a href="modificar.php?id=<?php echo pg_escape_string($_GET['id']) ?>"> MODIFICAR</a>
    </button>

    <!-- Button trigger modal -->
    <button type="button" class="button buttonL" data-toggle="modal" data-target="#exampleModal">APAGAR
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Vai apagar o livro. Tem a certeza?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form action="delete.php" id="usrform" method="post">
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                        <input type="hidden" id="id" name="id" value="<?php echo pg_escape_string($_GET['id']) ?>">

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container">

    <h2>Historico de preços</h2>


    <?php
    require_once("bd.php");
    $user = $_SESSION['useremail'];
    $id = pg_escape_string($_GET['id']);
    ///vai buscar o historico do livro
    $his = pg_fetch_all(pg_query($connection, "SELECT * 
                                                FROM historico 
                                                where book_id=$id"));
    ?>

    <?php if ($his != null) { ?>
    <table>
        <tr>
            <th>Dia</th>
            <th>Email de admistrador</th>
            <th>Valor</th>

        </tr><?php
        foreach ($his as $key => $entrada): ?>
            <tr>
                <td><?php echo $entrada['dia'] ?></td>
                <td><?php echo $entrada['admin_usuario_mail'] ?></td>
                <td><?php echo $entrada['valor'] ?></td>
            </tr>

        <?php endforeach;
        } else {
            echo 'Livro não sofreu alteração de preço';
        }

        ?>


    </table>


</div>

</body>
