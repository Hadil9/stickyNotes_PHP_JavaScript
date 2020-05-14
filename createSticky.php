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

//for testing
$id = "";

$userId = $currentUser->getUserId(); 
$content = $_POST["content"];
$posX = $_POST["posX"];
$posY = $_POST["posY"];

$noteWithoutId = new Note($id, $userId, $content, $posX, $posY);

$noteWithID = $noteDAO->createNote($noteWithoutId);

//for testing
//echo "noteid=".$noteWithID->getId();

$note = json_encode($noteWithID);

echo $note;

?>