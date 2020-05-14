<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="loginForm.css">
    </head>
    <body>
        
        <?php
            $token = hash('sha512', mt_rand(0, mt_getrandmax()) . microtime(TRUE));
            $_SESSION['token'] = $token;
        ?>
        
        <h2>Registration Form</h2>
        
        <form action="/action_page.php" method="post">
          <div class="container">
              
            
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>
        
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Confirm Password" name="psw" required>
                
            <button type="submit">Submit</button>
          </div>
        
         
        </form>
    
    </body>
</html>
