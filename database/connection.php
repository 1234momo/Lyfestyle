<?php

session_start(); 

// Information needed to connect to the MySQL database.
$hostname = "localhost";
$username = "root";
$password = "password";
$database = "lyfestyle";

// Connects to the MySQL database. Error message if unable to connect.
$conn = mysqli_connect($hostname, $username, $password);
if ($conn -> connect_error) die($conn -> connect_error);

// Create database if it does not exist and then connects to tbe database. 
$conn -> query("CREATE DATABASE IF NOT EXISTS $database");
$conn = mysqli_connect($hostname, $username, $password, $database);


//----------------------------------------------------------------------
// CREATING TABLES
//----------------------------------------------------------------------

$users_table = "CREATE TABLE IF NOT EXISTS `users` ( 
  `email` VARCHAR(128) PRIMARY KEY UNIQUE NOT NULL , 
  `first_name` VARCHAR(128) NOT NULL , 
  `last_name` VARCHAR(128) NOT NULL, 
  `password` VARCHAR(256) NOT NULL, 
  `fitness_goal` VARCHAR(128),
  `gender` VARCHAR(6), 
  `weight` INT
)";

$food_table = "CREATE TABLE IF NOT EXISTS `food` ( 
  `email` VARCHAR(128) PRIMARY KEY UNIQUE NOT NULL , 
  `breakfast` LONGTEXT NOT NULL , 
  `breakfast_custom` LONGTEXT NOT NULL , 
  `lunch` LONGTEXT NOT NULL , 
  `lunch_custom` LONGTEXT NOT NULL , 
  `dinner` LONGTEXT NOT NULL ,
  `dinner_custom` LONGTEXT NOT NULL
)";

$exercise_table = "CREATE TABLE IF NOT EXISTS `exercise` ( 
  `email` VARCHAR(128) PRIMARY KEY UNIQUE NOT NULL, 
  `workout` LONGTEXT NOT NULL,
  `workout_custom` LONGTEXT NOT NULL
)";

$water_table = "CREATE TABLE IF NOT EXISTS `water` ( 
  `email` VARCHAR(128) PRIMARY KEY UNIQUE NOT NULL, 
  `consumption` DOUBLE NOT NULL,
  `recommended_intake` DOUBLE NOT NULL
)";

$calories_table = "CREATE TABLE IF NOT EXISTS `calories` ( 
  `email` VARCHAR(128) PRIMARY KEY UNIQUE NOT NULL , 
  `food_calories` DOUBLE NOT NULL,
  `exercise_calories` DOUBLE NOT NULL,
  `goal` INT NOT NULL
)";

// Creates the tables in MySQL
$queries = array($users_table, 
                 $food_table,
                 $exercise_table,
                 $water_table,
                 $calories_table); 

foreach ($queries as $query) {
  $conn -> query($query);
}


//----------------------------------------------------------------------
// SIGN UP
//----------------------------------------------------------------------
if(isset($_POST['signup'])) {
  
  $email = mysqli_real_escape_string($conn, $_POST["signup_email"]); 
  $first_name = mysqli_real_escape_string($conn, $_POST["signup_first_name"]); 
  $last_name = mysqli_real_escape_string($conn, $_POST["signup_last_name"]); 
  $password1 = hash("ripemd128", $_POST["signup_password1"]); 
  $password2 = hash("ripemd128", $_POST["signup_password2"]);
  $weight = mysqli_real_escape_string($conn, $_POST["weight"]);
  $gender = mysqli_real_escape_string($conn, $_POST["gender"]); 
  
  $signup_errors = array(); 
  
  
  // CHECK IF EMAIL IS TAKEN
  $query = "SELECT * FROM `users` WHERE `email` = '$email'"; 
  $check_email = mysqli_query($conn, $query); 
  
  if ($check_email -> num_rows != 0) {
    array_push($signup_errors, "<p style='color:red;text-align:center'>Email is already taken</p>");
  }
  
  // CHECK IF EMAIL IS VALID
  if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email)) {
    array_push($signup_errors, "<p style='color:red;text-align:center'>Email is not valid</p>");
  }

  // CHECK IF FIRST NAME IS VALID
  if (preg_match("/[^a-zA-Z ]+/", $first_name)) {
    array_push($signup_errors, "<p style='color:red;text-align:center'>First name must contain only letters</p>");
  }

  // CHECK IF LAST NAME IS VALID
  if (preg_match("/[^a-zA-Z ]+/", $last_name)) {
    array_push($signup_errors, "<p style='color:red;text-align:center'>Last name must contain only letters</p>");
  }
  

  // CHECK IF WEIGHT IS VALID
  if (preg_match("/[^0-9]/", $weight) || $weight == '') {
    array_push($signup_errors, "<p style='color:red;text-align:center'>Weight must be a whole number</p>");
  }

  
  // CHECK IF PASSWORDS MATCH
  if ($password1 != $password2) {
    array_push($signup_errors, "<p style='color:red;text-align:center'>Passwords did not match</p>");
  }
  
  
  // CREATE USER IF NO ERRORS
  if (count($signup_errors) == 0) {
    
    $_SESSION["signup_email"] = $email; 
    $_SESSION["signup_first_name"] = $first_name; 
    $_SESSION["signup_last_name"] = $last_name; 
    $_SESSION["signup_password"] = $password1; 
    $_SESSION["gender"] = $gender; 
    $_SESSION["weight"] = $weight; 
    
    header('location: fitness_goal_2.php');
  }
  
  // OTHERWISE PRINTS ERRORS
  else {
    foreach ($signup_errors as $error_message) {
      echo "$error_message<br>"; 
    }
  }
}

//----------------------------------------------------------------------
// SIGN UP FITNESS GOAL
//----------------------------------------------------------------------
if(isset($_POST['signup_weight_loss']) or 
   isset($_POST['signup_muscle_building']) or
   isset($_POST['signup_weight_gain'])) {
  
  // Defining the fitness goal
  $fitness_goal = NULL; 
  
  if (isset($_POST['signup_weight_loss'])) {
    $fitness_goal = "weight loss";
    $_SESSION["fitness_goal"] = "weight loss";
  }
  else if (isset($_POST['signup_muscle_building'])) {
    $fitness_goal = "muscle building";
    $_SESSION["fitness_goal"] = "muscle building";
  }
  else {
    $fitness_goal = "weight gain";
    $_SESSION["fitness_goal"] = "weight gain";
  }
  
  // Create new user in database
  $email = $_SESSION["signup_email"];
  $first_name = $_SESSION["signup_first_name"];
  $last_name = $_SESSION["signup_last_name"];
  $password = $_SESSION["signup_password"];
  $weight = $_SESSION["weight"];
  $gender = $_SESSION["gender"]; 
  
  $query = "INSERT INTO users (email, first_name, last_name, password, fitness_goal, gender, weight) 
    VALUES('$email', '$first_name', '$last_name', '$password', '$fitness_goal', '$gender', $weight)";
  mysqli_query($conn, $query);
  
  // Redirect to login page
  header('location: login_2.php');
}

//----------------------------------------------------------------------
// LOGIN
//----------------------------------------------------------------------

if(isset($_POST['login'])) {
  
  $email = $_POST["login_email"];
  $password = hash("ripemd128", $_POST["login_password"]); 
    
  $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  $results = mysqli_query($conn, $query);

  if (!$results) die ($connection -> error);
  elseif ($results -> num_rows) {
    $row = $results -> fetch_array(MYSQLI_NUM);

    // Check password
    if ($password == $row[3]) {
      $email = $row[0];
      $_SESSION['email'] = $email;
      
      // Query food db to check if the user exists
      $query = "SELECT * FROM food WHERE email = '$email'";
      $isEmailInFood = mysqli_query($conn, $query);
      
      // If something went wrong adding the user into the food db, output msg
      if (!$isEmailInFood) {
        echo "<p style='text-align:center;color:red'>
                Uh oh... Something seems to be wrong. Please come back later.
              </p>";
        exit();
      }

      // If user doesn't exist in the food db, add the user
      if (mysqli_num_rows($isEmailInFood) == 0) {
        $query = "INSERT INTO food VALUES('$email', '','','','','','')";
        $isEmailInFood = mysqli_query($conn, $query);

        // If something went wrong adding the user into the food db, output msg
        if (!$isEmailInFood) {
          echo "<p style='text-align:center;color:red'>
                  Uh oh... Something seems to be wrong. Please come back later.
                </p>";
          exit();
        }
      }
      
      // Query exercise db to check if the user exists
      $query = "SELECT * FROM exercise WHERE email = '{$email}'";
      $isEmailInExer = mysqli_query($conn, $query);

      // If user doesn't exist in the exercise db, add the user
      if (mysqli_num_rows($isEmailInExer) == 0) {
        $query = "INSERT INTO exercise VALUES('$email', '', '')";
        $isEmailInExer = mysqli_query($conn, $query);

        // If something went wrong adding the user into the exercise db, output msg
        if (!$isEmailInExer) {
          echo "<p style='text-align:center;color:red'>
                  Uh oh... Something seems to be wrong. Please come back later.
                </p>";
          exit();
        }
      }

      // Query water db to check if the user exists
      $query = "SELECT * FROM water WHERE email = '$email'";
      $isEmailInWater = mysqli_query($conn, $query);

      // If user doesn't exist in the water db, add the user
      if (mysqli_num_rows($isEmailInWater) == 0) {
        
        $query = "INSERT INTO water VALUES('$email', '0.0', 64)";
        $isEmailInWater = mysqli_query($conn, $query);

        // If something went wrong adding the user into the exercise db, output msg
        if (!$isEmailInWater) {
          echo "<p style='text-align:center;color:red'>
                  Uh oh... Something seems to be wrong. Please come back later.
                </p>";
          exit();
        }
      }
      
      // Query calories db to check if the user exists
      $query = "SELECT * FROM calories WHERE email = '{$email}'";
      $isEmailInCalories = mysqli_query($conn, $query);

      // If user doesn't exist in the calories db, add the user
      if (mysqli_num_rows($isEmailInCalories) == 0) {
        $calories_recommended = 0;
        $fitness_goal = $_SESSION['fitness_goal'];
        $gender = $_SESSION['gender'];
        $weight = $_SESSION['weight'];

        if ($gender == "female") {
          if ($fitness_goal == "weight loss") {
            $calories_recommended = 1500;
          }
          elseif ($fitness_goal == "muscle building") {
            $calories_recommended = $weight * 19;
          }
          else {
            $calories_recommended = 2000;
          }
        }
        else {
          if ($fitness_goal == "weight loss") {
            $calories_recommended = 2000;
          }
          elseif ($fitness_goal == "muscle building") {
            $calories_recommended = $weight * 19;
          }
          else {
            $calories_recommended = 2500;
          }
        }

        $query = "INSERT INTO calories VALUES('$email', 0, 0, $calories_recommended)";
        $isEmailInCalories = mysqli_query($conn, $query);

        // If something went wrong adding the user into the exercise db, output msg
        if (!$isEmailInCalories) {
          echo "<p style='text-align:center;color:red'>
                  Uh oh... Something seems to be wrong. Please come back later.
                </p>";
          exit();
        }
      }
    }
    else echo "Invalid username/password";
  }
  else echo "Invalid username/password";
  
  if (mysqli_num_rows($results) == 1) {
    // session_destroy(); 
    header('location: ../pages/dashboard.php');
  }

  $results -> close();
}

//----------------------------------------------------------------------
// LOGOUT
//----------------------------------------------------------------------
if (isset($_POST['logout'])) {
  destroy_session_and_data();
  header("location: ../pages/login_2.php");     
}
?>