<?php 
  require_once("../database/connection.php"); 
  $connection = new mysqli($hostname, $username, $password, $database);
  if ($connection->connect_error) die($connection->connect_error);
    
  if (!isset($_SESSION['email'])) {
    echo "<p style='text-align:center; color:red'>
            Please <a href='./login.php'>sign in</a> to view your dashboard
         </p>";     
    exit();      
  }
  elseif (isset($_POST['submit'])) {
    destroy_session_and_data();
    echo "<p style='text-align:center; color:red'>
            You have been signed out. Please <a href='./login.php'>sign in</a> again if you wish
         </p>";     
    exit(); 
  }

  // Close connection
  $connection -> close();

  function destroy_session_and_data() {
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
  }     
?>

<html>

  <head>
    <title>Lyfestyle | Dashboard</title>
    <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
    <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>

  <body>
    <h1>Welcome!</h1>

    <form action="./dashboard.php" class="form-inline" method="POST">
      <button type="submit" name="submit" class="btn btn-primary">Logout</button>
    </form>

    <div class="row">
      <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
        <div class="card-body text-center">
          <h5 class="card-title"> Add to your logs</h5>
          <div>
            <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addFood.php'">Add food log</button>
            <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addExercise.php'">Add exercise log</button>
            <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addWater.php'">Add water consumption</button>
          </div>
        </div>
      </div>

      <div class="card ml-2 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
        <div class="card-body text-center">
          <h5 class="card-title">Edit your logs</h5>
          <div>
            <button type="button" class="btn btn-primary mt-2" onClick="location.href = './editFood.php'">Edit food log</button>
            <button type="button" class="btn btn-primary mt-2" onClick="location.href = './editExercise&Water.php'">Edit exercise & water log</button>
          </div>
        </div>
      </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>  
  </body>
  
</html>


