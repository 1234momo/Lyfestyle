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
$conn = mysqli_connect($servername, $username, $password, $database);
?>