<?php
session_start();
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Novo registo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">

</head>
 <body style="background-color: rgba(153, 204, 102, 0.4) ">

<div class="container">
    <div class="page-header">
        <h1 id="titulo">Novo registo </h1>
    </div>

    <form class="form-horizontal" action="register.php" method="post">
        <div class="alert alert error"><? echo $_SESSION['message'];
            $_SESSION['message'] = null; ?></div>
        <br>
        <div class="form-group">
            <label class="control-label col-sm-2" for="usr">Nome:</label>
            <div class="col-sm-10">
                <input type="usr" class="form-control" id="usr" placeholder="Enter name" name="username" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Password:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password"
                       required >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd1">Confirm password:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="pwd1" placeholder="Enter password"
                       name="confirmpassword" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label><input type="checkbox" name="notificacoes"> Quero receber notificações</label>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
    <button class="button buttonNR"><a href="login.php">LOGIN</button>

</div>
</body>
</html> 