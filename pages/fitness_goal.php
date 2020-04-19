<?php 
require_once("../database/connection.php"); 
?>

<html>
<head>
  <title>Lyfestyle | Signup</title>
  <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
</head>

<body>
<img id="lyfestyle-logo" src="../assets/images/Lyfestyle_banner_02.png">

<h1>Select fitness goal</h1>

<!--  SELECT FITNESS GOAL -->
<div class="container">
  <form method=POST>
    <div class="row">
    
      <!--  WEIGHT LOSS -->
      <div class="col-sm">
        <input type="submit" id="weight-loss" name="signup_weight_loss" value="Weight loss" href="login.php">
      </div>

      <!--  MUSCLE BUILDING -->
      <div class="col-sm">
         <input type="submit" id="muscle-building" name="signup_muscle_building" value="Muscle building" href="login.php">
      </div>

      <!--  STAMINA -->
      <div class="col-sm">
         <input type="submit" id="weight-gain" name="signup_weight_gain" value="Weight gain" href="login.php">
      </div>
         
    </div>
  </form> 
</div>

<!-- BACK BUTTON-->
<form action="signup.php" method=POST>
  <input type="submit" name="fitness_goal_back" value="Back">
</form>
  
</body>
</html>