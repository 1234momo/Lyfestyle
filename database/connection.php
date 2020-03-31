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
  `breakfast` VARCHAR(256) NOT NULL , 
  `lunch` VARCHAR(256) NOT NULL , 
  `dinner` VARCHAR(256) NOT NULL, 
  `everything` LONGTEXT NOT NULL
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
  
  echo "email = $email<br> password = $password<br>"; 
  
  $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  $results = mysqli_query($conn, $query);
  
  if (mysqli_num_rows($results) == 1) {
   header('location: dashboard.php');
  }
}
?>