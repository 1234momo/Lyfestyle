<?php
require_once("connection.php"); 

// Creates the users table
$users_table = "CREATE TABLE IF NOT EXISTS `users` ( 
  `id` INT PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT , 
  `email` VARCHAR(128) NOT NULL , 
  `first_name` VARCHAR(128) NOT NULL , 
  `last_name` VARCHAR(128) NOT NULL, 
  `password` VARCHAR(256) NOT NULL
)";
  
$conn -> query($users_table);
?>