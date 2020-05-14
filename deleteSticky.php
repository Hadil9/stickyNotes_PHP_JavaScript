<?php

require_once "Authentication.php";
require_once('ConnectionToDB.php');
require_once('Note.php');
require_once('NoteDAO.php');

session_start();

$connection = new ConnectionToDB();

$authentication = new Authentication($connection);
$currentUser = $authentication->checkLoggedIn();
if(!$currentUser)
{
    http_response_code (401); 
    exit;
}

$noteDAO = new NoteDAO($connection);

$id = $_POST["id"];

$noteDAO->deleteNote($currentUser->getUserId(), $id);


?>
