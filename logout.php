<?php

require_once "Authentication.php";
require_once('ConnectionToDB.php');

session_start();

$connection = new ConnectionToDB();

$authentication = new Authentication($connection);
$authentication->logout();
header("Location:login.php");

?>