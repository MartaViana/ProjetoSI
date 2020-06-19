<?php

require_once ("bd.php");

$result = pg_query($connection, "delete FROM bookorder");

session_start();
session_destroy();
header('Location: login.php');
exit();