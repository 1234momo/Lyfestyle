<?php 
  require_once("../database/connection.php"); 
?>

<html>
<head>
  <title>Lyfestyle | Login</title>
  <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
</head>

<body>
  <img id="lyfestyle-logo" src="../assets/images/Lyfestyle_banner_02.png">

  <h1 style="color: white; font-weight: bold; ">Login</h1>

  <div class="main-login-form">
    <form method="POST">
      <div class="login-group"> 
        <label for="email">Email:</label><br> 
        <input type="email" id="email" name="login_email" required>
      </div>
      
      <div class="login-group">  
        <label for="password">Password:</label><br> 
        <input type="password" id="password" name="login_password" required>
      </div>
      
      <div class="login-group">
        <input type="submit" name="login" value="Login">
      </div>
    </form>
  </div>
    
  <div class="etc-login-form">
  Don't have an account? <a href="signup.php" >Signup here</a>
  </div>
  
</body>
  
</html>


