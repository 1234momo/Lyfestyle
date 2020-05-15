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
    <link href="../assets/css/fitness_goal.css" rel="stylesheet">
  </head>
  <body>
      
    <div class="fitnessgoal-page">
      <div class="form">
        <form class="fitnessgoal-form" method="POST">
            <p class = "fitnessgoal-form"> Pick your fitness goal</p>
          
            <input type="submit" id="weight-loss" name="signup_weight_loss" value="Weight loss">
            <input type="submit" id="muscle-building" name="signup_muscle_building" value="Muscle building">
            <input type="submit" id="weight-gain" name="signup_weight_gain" value="Weight gain">
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