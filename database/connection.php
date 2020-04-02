<?php
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

// Creates the users table
$users_table = "CREATE TABLE IF NOT EXISTS `users` ( 
  `id` INT PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT , 
  `email` VARCHAR(128) NOT NULL , 
  `first_name` VARCHAR(128) NOT NULL , 
  `last_name` VARCHAR(128) NOT NULL, 
  `password` VARCHAR(256) NOT NULL
)";
  
$conn -> query($users_table);

// Creates the food table
$food_table = "CREATE TABLE IF NOT EXISTS `food` ( 
  `id` INT PRIMARY KEY UNIQUE NOT NULL , 
  `Breakfast` LONGTEXT NOT NULL , 
  `Lunch` LONGTEXT NOT NULL , 
  `Dinner` LONGTEXT NOT NULL
)";

$conn -> query($food_table);

//----------------------------------------------------------------------
// SIGN UP (WIP)
//----------------------------------------------------------------------
if(isset($_POST['signup'])) {
  
  $email = $_POST["signup_email"]; 
  $first_name = $_POST["signup_first_name"]; 
  $last_name = $_POST["signup_last_name"]; 
  $password1 = hash("ripemd128", $_POST["signup_password1"]); 
  $password2 = hash("ripemd128", $_POST["signup_password2"]); 
  
  $query = "INSERT INTO users (email, first_name, last_name, password) 
      VALUES('$email', '$first_name', '$last_name', '$password1')";
  mysqli_query($conn, $query);
  
  header('location: login.php');
}


//----------------------------------------------------------------------
// LOGIN (TODO)
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
    if ($password == $row[4]) {
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
   header('location: ../pages/dashboard.php');
  }

  $results -> close();
}
?>