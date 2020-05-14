<?php

require_once "Authentication.php";
require_once "ConnectionToDB.php";

$connection = new ConnectionToDB();
var_dump($connection);
$authentication = new Authentication($connection);
var_dump($authentication);
if(!$authentication->checkLoggedIn())
{
    echo "test\n";
}

$authentication->login('q', 'w');


?>