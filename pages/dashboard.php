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

$email = $_SESSION['email'];

// Update calorie goal
if (array_key_exists("new_calorie_goal", $_POST)) {
  $new_calorie_goal = intval(sanitizeMySQL($connection, $_POST['new_calorie_goal']));

  // Update user's calorie goal in DB
  $query = "UPDATE calories set goal={$new_calorie_goal} where email='{$email}'";
  $stmt = $connection->prepare($query);
  $stmt -> execute();

  // TODO: output warning msg better
  if (!$stmt) {
      $stmt -> close();
      echo "<p style='text-align:center;color:red'>
              Unable to insert your data into the database. Please try again later.
              </p>";
      exit();
  }

  $stmt -> close();
}

// Retrieve the water data
$stmt = $connection->prepare("SELECT * FROM water WHERE email='{$email}'");
$stmt -> execute();
$water_result = $stmt -> get_result();
$water_result = $water_result->fetch_array(MYSQLI_NUM)[1];

// Retrieve the user's target calorie goal
$stmt = $connection->prepare("SELECT goal FROM calories WHERE email='{$email}'");
$stmt -> execute();
$calories_result = $stmt -> get_result();
$calorie_goal = $calories_result->fetch_array(MYSQLI_NUM)[0];

// Close connection
$connection -> close();

// Sanitizes a string
function sanitizeString($var) {
  $var = stripslashes($var);
  $var = strip_tags($var);
  $var = htmlentities($var);
  return $var;
}

// Sanitizes with mysqli connection object and sanitizeString method
function sanitizeMySQL($connection, $var) {
    $var = $connection -> real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}

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
    <!-- Welcome and logout btn -->
    <div class="container-fluid">
      <h1 class="float-left">Welcome!</h1>
  
      <form action="./dashboard.php" method="POST">
        <button type="submit" name="logout" class="btn btn-primary float-right">Logout</button>
      </form>
    </div>

    <!-- Food and exercise calories with progress toward goal -->
    <div class="container-fluid">
      <div></div>
    </div>

    <!-- Adding to logs and editing logs options -->
    <div class="container-fluid d-inline-flex">
      <div class="row">
        <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
          <div class="card-body text-center">
            <h3 class="card-title"> Add to your logs</h3>
            <div>
              <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addFood.php'">Add food log</button>
              <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addExercise.php'">Add exercise log</button>
              <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addWater.php'">Add water consumption</button>
            </div>
          </div>
        </div>
        <div class="card ml-2 shadow p-3 mb-5 bg-white rounded" style="width: 18rem;">
          <div class="card-body text-center">
            <h3 class="card-title">Edit your logs</h3>
            <div>
              <button type="button" class="btn btn-primary mt-2" onClick="location.href = './editFood.php'">Edit food log</button>
              <button type="button" class="btn btn-primary mt-2" onClick="location.href = './editExercise&Water.php'">Edit exercise & water log</button>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#changeCalorieModal">Change calorie goal</button>

              <!-- Modal -->
              <div class="modal fade" id="changeCalorieModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Change Calorie Goal</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form id="changeCalorieGoal" method="POST">
                      <div class="modal-body">
                        <input type="number" class="form-control" name="new_calorie_goal" id="new_calorie_goal" 
                                            placeholder="Calorie goal for each day" min="0.1" step="0.1" 
                                            value="<?php echo $calorie_goal ?>" required>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>  
  </body>
  
</html>


