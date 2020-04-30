<?php 
require_once("../database/connection.php"); 
?>

<html lang="en">
  <head>
    <title>Lyfestyle | Signup</title>
    <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/signup.css" rel="stylesheet">
    
    <script src="../validate_input.js"></script>
  </head>
  <body>
    <div class="login-page">
      <div class="form">
        <form class="register-form" method="POST" >
          <input type="email" id="email" name="signup_email" oninput="validate_email(this)" placeholder="Email address" required>
          <input type="text" id="firstName" name="signup_first_name" oninput="validate_names(this)" placeholder="First name" required>
          <input type="text" id="lastName" name="signup_last_name" oninput="validate_names(this)" placeholder="Last name" required>
          <select name="gender" required>
            <option value="" selected disabled hidden>Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
          <input type="number" min="1" id="weight" name="weight" oninput="validate_weight(this)" placeholder="Weight in pounds" required>
          <input type="password" id="password" name="signup_password1" placeholder="Password" required>
          <input type="password" id="confirmPassword" name="signup_password2" oninput="check_password(this)" placeholder="Confirm password" required>  
          <input type="submit" class="signup-button" name="signup" value="signup">
          
          <p class="message">Already registered? <a href="login_2.php">Login</a></p>
        </form>

      </div>
    </div>
      
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>