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
      <label for="email">Email:</label><br> 
      <input type="email" id="email" name="signup_email" required>
    </div>
    
    <div class="login-group"> 
      <label for="firstName">First name:</label><br> 
      <input type="text" id="firstName" name="signup_first_name" required>
    </div>
    
    <div class="login-group">  
      <label for="lastName">Last name:</label><br> 
      <input type="text" id="lastName" name="signup_last_name" required>
    </div>
    
    <div class="login-group">  
      <label for="password">Password:</label><br> 
      <input type="password" id="password" name="signup_password1" required>
    </div>
    
    <div class="login-group">  
      <label for="email">Confirm password:</label><br> 
      <input type="password" id="confirmPassword" name="signup_password2" required>
    </div>
    
    <div class="login-group">  
      <input type="submit" name="signup" value="Signup">
    </div>
  </form>
</div>
  
<div class="etc-login-form">
Already have an account? <a href="login.php" >Login here</a>
</div>
  
</body>
  
</html>


