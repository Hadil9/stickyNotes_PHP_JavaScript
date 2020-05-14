<?php

require_once "Authentication.php";
require_once "ConnectionToDB.php";

session_start();

$connection = new ConnectionToDB();
$authentication = new Authentication($connection);
$currentUser = $authentication->checkLoggedIn();
if($currentUser)
{
  header("Location:index.php");
  exit;
}

$showErrorMessage = false;
$showTooManyAttempts = false;
$showPassDoesNotMatch = false;

function checkLogin($authentication)
{
  if (!isset($_POST['token']) ||
      !isset($_POST['form_type']) ||
      !isset($_POST['uname']) ||
      !isset($_POST['psw']) ||
      !isset($_SESSION['token'])
      )
  {
    return;
  }

  if ($_POST['token'] !== $_SESSION['token'])
	{
    return;
	}

  if($_POST['form_type'] == "login")
  {
    $loginResult = $authentication->login($_POST['uname'],$_POST['psw']);
    if($loginResult == Authentication::LOGIN_SUCCESSFULL)
    {
      header("Location:index.php");
      exit;
    }

    if($loginResult == Authentication::TOO_MENY_ATTEMPTS)
    {
      $GLOBALS['showTooManyAttempts'] = true;
    }
    return;
  }
  
  if($_POST['form_type'] == "registration")
  {
    if(!isset($_POST['psw_conf']))
    {
      return;
    }
    if($_POST['psw'] != $_POST['psw_conf'])
    {
      $GLOBALS['showPassDoesNotMatch'] = true;
      return;
    }

    $registrationResult = $authentication->register($_POST['uname'], $_POST['psw']);
    if($registrationResult == TRUE)
    {
      header("Location:index.php");
      exit;
    }
    return;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $showErrorMessage = true;
  checkLogin($authentication);
}

$token = hash("sha3-512", mt_rand(0, mt_getrandmax()));
$_SESSION['token'] = $token;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="loginForm.css">
    </head>
    <body>
<?php 
if($showErrorMessage)
{
  echo "<h1 class='login_error'>Login failed. Try again.</h1>";
}
if($showTooManyAttempts)
{
  echo "<h1 class='login_error'>Too many attempts. Please wait 30 seconds.</h1>";
}
if($showPassDoesNotMatch)
{
  echo "<h1 class='login_error'>Password confirmation does not match.</h1>";
}
?>
        <h2>Login Form</h2>
        
        <form method="post">
          <div class="container">
              
            <input type="hidden" name="form_type" value="login">
            <input type="hidden" name="token" value="<?php echo $token;  ?>">
            
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>
        
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
                
            <button type="submit">Login</button>
          </div>
        </form>
        
        <h2>Registration Form</h2>
        
        <form method="post">
          <div class="container">
              
            <input type="hidden" name="form_type" value="registration">
            <input type="hidden" name="token" value="<?php echo $token;  ?>">
            
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>
        
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            
            <label for="psw_conf"><b>Password confirmation</b></label>
            <input type="password" placeholder="Confirm Password" name="psw_conf" required>
                
            <button type="submit">Submit</button>
          </div>
        </form>

    </body>
</html>