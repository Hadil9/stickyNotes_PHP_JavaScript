<?php

require_once "Authentication.php";
require_once "ConnectionToDB.php";

session_start();

$connection = new ConnectionToDB();
$authentication = new Authentication($connection);
$currentUser = $authentication->checkLoggedIn();
if(!$currentUser)
{
  header("Location:login.php");
  exit;
}
?>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <script src="./createStickyNote.js"></script>
        <title>Sticky notes</title>
    </head>
    <body>
        <h3>Current user: <?php echo $currentUser->getUsername(); ?></h3>
        <button onclick="window.location.href='logout.php'">Logout</button>
        <textarea rows="4" cols="50" id="stickyContent"></textarea>
        <button id="newStickyNote"> Add new sticky note </button>
    </body>
</html>
