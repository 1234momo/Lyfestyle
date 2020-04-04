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
  `fitness_goal` VARCHAR(128)
)";

$food_table = "CREATE TABLE IF NOT EXISTS `food` ( 
  `id` INT PRIMARY KEY UNIQUE NOT NULL , 
  `Breakfast` LONGTEXT NOT NULL , 
  `Lunch` LONGTEXT NOT NULL , 
  `Dinner` LONGTEXT NOT NULL
)";

// Creates the tables in MySQL
$queries = array($users_table, 
                 $food_table); 

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
  
  $signup_errors = array(); 
  
  
  // CHECK IF EMAIL IS TAKEN
  $query = "SELECT * FROM `users` WHERE `email` = '$email'"; 
  $check_email = mysqli_query($conn, $query); 
  
  if ($check_email -> num_rows != 0) {
    array_push($signup_errors, "Email is already taken");
  }
  
  
  // CHECK IF PASSWORDS MATCH
  if ($password1 != $password2) {
    array_push($signup_errors, "Passwords did not match");
  }
  
  
  // CREATE USER IF NO ERRORS
  if (count($signup_errors) == 0) {
    
    $_SESSION["signup_email"] = $email; 
    $_SESSION["signup_first_name"] = $first_name; 
    $_SESSION["signup_last_name"] = $last_name; 
    $_SESSION["signup_password"] = $password1; 
    
    header('location: fitness_goal.php');
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
   isset($_POST['signup_stamina'])) {
  
  // Defining the fitness goal
  $fitness_goal = NULL; 
  
  if (isset($_POST['signup_weight_loss'])) {
    $fitness_goal = "weight loss";
  }
  else if (isset($_POST['signup_muscle_building'])) {
    $fitness_goal = "muscle building";
  }
  else {
    $fitness_goal = "stamina";
  }
  
  // Create new user in database
  $email = $_SESSION["signup_email"];
  $first_name = $_SESSION["signup_first_name"];
  $last_name = $_SESSION["signup_last_name"];
  $password = $_SESSION["signup_password"];
  
  $query = "INSERT INTO users (email, first_name, last_name, password, fitness_goal) 
    VALUES('$email', '$first_name', '$last_name', '$password', '$fitness_goal')";
  mysqli_query($conn, $query);
  
  // Redirect to login page
  header('location: login.php');
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
      session_start();
      $id = $row[0];
      $_SESSION['id'] = $id;
      
      // Query food db to check if the user exists
      $query = "SELECT * FROM food WHERE id = {$id}";
      $isIDInFood = mysqli_query($conn, $query);

      // If user doesn't exist in the food db, add the user
      if (mysqli_num_rows($isIDInFood) == 0) {
        $space = " ";
        $query = "INSERT INTO food VALUES('$id', '$space','$space', '$space')";
        $isIDInFood = mysqli_query($conn, $query);
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
?>