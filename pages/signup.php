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

<!--  SIGNUP INPUTS -->
<div class="main-login-form">
  <form method="POST"> <!-- action="fitness_goal.php" --> 
    
    <!--  EMAIL -->
    <div class="login-group">  
      <input type="email" id="email" name="signup_email" placeholder="Email" required>
    </div>
    
    <!--  FIRST NAME -->
    <div class="login-group"> 
      <input type="text" id="firstName" name="signup_first_name" placeholder="First name" required>
    </div>
    
    <!--  LAST NAME -->
    <div class="login-group">  
      <input type="text" id="lastName" name="signup_last_name" placeholder="Last name" required>
    </div>
    
    <!--  PASSWORD -->
    <div class="login-group">  
      <input type="password" id="password" name="signup_password1" placeholder="Password" required>
    </div>
    
    <!--  CONFIRM PASSWORD -->
    <div class="login-group">  
      <input type="password" id="confirmPassword" name="signup_password2"  placeholder="Confirm password" required>
    </div>
    
    <!--  SUBMIT -->
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


