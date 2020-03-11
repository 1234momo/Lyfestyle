<?php 
require_once("../database/connection.php"); 
?>

<html>
<head>
  <title>Lyfestyle | Signup</title>
  <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
</head>

<body>
<img id="lyfestyle-logo" src="../assets/images/Lyfestyle_banner_02.png">

<h1 style="color: white; font-weight: bold; ">Signup</h1>
<div class="main-login-form">
  <form method="POST">
    <div class="login-group">  
      <input type="email" name="signup_email" placeholder="Email">
    </div>
    
    <div class="login-group">  
      <input type="text" name="signup_first_name" placeholder="First name">
    </div>
    
    <div class="login-group">  
      <input type="text" name="signup_last_name" placeholder="Last name">
    </div>
    
    <div class="login-group">  
      <input type="password" name="signup_password1" placeholder="Password">
    </div>
    
    <div class="login-group">  
      <input type="password" name="signup_password2" placeholder="Confirm password">
    </div>
    
    <div class="login-group">  
      <input type="submit" name="signup" value="Signup">
    </div>
  </form>
</div>
  
<div class="etc-login-form">
already have an account? <a href="login.php" >login here</a>
</div>
  
</body>
  
</html>


