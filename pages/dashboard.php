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

//----------------------------------------------------------------------
// CHANGE FITNESS GOAL
//----------------------------------------------------------------------
if (isset($_POST['new_fitness_goal'])) {
  
  $email = $_SESSION['email'];
  $new_fitness_goal = sanitizeMySQL($connection, $_POST['new_fitness_goal']);  
  
  $query = "UPDATE users SET fitness_goal='{$new_fitness_goal}' WHERE email='{$email}'; "; 
  mysqli_query($conn, $query);
}

//----------------------------------------------------------------------
// CHANGE CALORIE GOAL
//----------------------------------------------------------------------
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

//----------------------------------------------------------------------
// ADD WATER CONSUMPTION
//----------------------------------------------------------------------
if (array_key_exists("waterSubmit", $_POST)) {
  $consumption = sanitizeMySQL($connection, $_POST["addWater"]);
            
  // Select the row of the user from DB
  $stmt = $connection->prepare("SELECT consumption FROM water WHERE email='{$email}'");
  $stmt -> execute();

  // Retrieve the data of the user
  $result = $stmt -> get_result();
  $consumption_DB = ($result->fetch_array(MYSQLI_NUM))[0];

  //If the size of the consumption isn't 0, add the consumption number from the db to the consumption variable
  $consumption += $consumption_DB;

  $query = "UPDATE water set consumption={$consumption} where email='{$email}'";
  $stmt = $connection->prepare($query);
  $stmt -> execute();

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
$water_result = $water_result->fetch_array(MYSQLI_NUM);
$water_intake = $water_result[1];
$recommended_intake = $water_result[2];
$water_card_msg;

if ($recommended_intake > $water_intake) {
  $water_card_msg = "<h1 class='display-3 float-left'>$water_intake<span class='d-inline-block'><h6> out of $recommended_intake</h6></span></h1>";
}
else {
  $water_card_msg = "<h1 class='display-5 float-left' id='overdrinking-msg'>Overdrinking by ". ($water_intake - $recommended_intake) ." OZ</h1>";
}

// Retrieve the user's target calorie goal
$stmt = $connection->prepare("SELECT * FROM calories WHERE email='{$email}'");
$stmt -> execute();
$calories_result = $stmt -> get_result();
$calories_result = $calories_result->fetch_array(MYSQLI_NUM);
$calorie_goal = $calories_result[3];

// Retrieve the user's food calorie 
$food_calories = $calories_result[1];
$food_calories = round($food_calories, 1);

// Retrieve the user's exercise calories
$exercise_calories = $calories_result[2];
$exercise_calories = round($exercise_calories, 1);

// Retrieve the user's name
$stmt = $connection->prepare("SELECT * FROM users WHERE email='{$email}'");
$stmt -> execute();
$name_result = $stmt -> get_result();
$name_result = $name_result->fetch_array(MYSQLI_NUM);
$first_name = $name_result[1];
$last_name = $name_result[2];
$name = "$first_name $last_name";

$remaining_calories = ($calorie_goal - $food_calories) + $exercise_calories;
$remaining_calories = round($remaining_calories, 1);
$calorie_card_msg;

if ($remaining_calories < 0) {
  $calorie_card_msg = "<h1 class='display-5' id='overeating-msg'>Overeating " . ($remaining_calories * -1) . " calories</h1>";
}
else {
  $calorie_card_msg = "<h1 class='display-3'>$remaining_calories</h1>";
}

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
    <link rel="stylesheet" type="text/css", href="../assets/css/dashboard.css">
    <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>

  <body>
    <!-- Welcome and logout btn -->
    <div class="container-fluid">
      <h1 class="display-2 d-inline-block" id="greeting-message">Welcome <?php echo $name ?>!</h1>
  
      <div class="d-inline-block float-right">
        <form action="./dashboard.php" method="POST">
          <button type="submit" name="logout" class="btn btn-primary btn-lg float-right">Logout</button>
        </form>
      </div>
    </div>

    <br clear="all">
    
    <!-- Food and exercise calories with progress toward goal -->
    <div class="container-fluid mt-3">
      <div class="row mb-5">
        <div class="col-xl-3 col-sm-6 py-2">
            <div class="card bg-success text-white h-100 shadow">
                <div class="card-body bg-success">
                    <h6 class="text-uppercase" id="card-header">food calories</h6>
                    <h1 class="display-3"><?php echo $food_calories ?></h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 py-2">
            <div class="card text-white bg-warning h-100 shadow">
                <div class="card-body bg-warning">
                    <h6 class="text-uppercase" id="card-header">exercise calories</h6>
                    <h1 class="display-3"><?php echo $exercise_calories ?></h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 py-2">
            <div class="card text-white bg-danger h-100 shadow">
                <div class="card-body bg-danger">
                    <h6 class="text-uppercase" id="card-header">remaining calorie goal</h6>
                    <?php echo $calorie_card_msg ?>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 py-2">
            <div class="card text-white bg-info h-100 shadow">
                <div class="card-body">
                    <h6 class="text-uppercase">water (oz)</h6>
                    <div>
                      <?php echo $water_card_msg ?>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>

    <hr>

    <!-- Row of card options -->
    <div class="container-fluid d-inline-flex justify-content-center mt-5 mb-3">
      <div class="row">

        <!-- Add to your logs card -->
        <div class="col-md-4 d-flex justify-content-around">
          <div class="card shadow p-3 mb-5 bg-white rounded" style="width: 20rem;">
            <div class="card-body text-center">
              <h3 class="card-title"> Add to your logs</h3>
              <div>
                <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addFood.php'">Add food log</button>
                <button type="button" class="btn btn-primary mt-2" onClick="location.href = './addExercise.php'">Add exercise log</button>
                <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#addWaterModal">Add water consumption</button>

                <!-- Modal for adding water consumption -->
                <div class="modal fade" id="addWaterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Add water consumption</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form id="addWater" method="POST">
                        <div class="modal-body">
                          <input type="number" class="form-control" name="addWater" id="addWater" 
                                              placeholder="Add how much water you drank in OZ" min="0.1" step="0.1" required>
                          <p class="mt-4">Total water drank today: <?php echo $water_intake ?> OZ</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" name="waterSubmit" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit your logs card -->
        <div class="col-xs-12 col-md-4 d-flex justify-content-around">
          <div class="card ml-2 shadow p-3 mb-5 bg-white rounded" style="width: 20rem;">
            <div class="card-body text-center">
              <h3 class="card-title">Edit your logs</h3>
              <div>
                <button type="button" class="btn btn-primary mt-2" onClick="location.href = './editFood.php'">Edit food log</button>
                <button type="button" class="btn btn-primary mt-2" onClick="location.href = './editExercise&Water.php'">Edit exercise & water log</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Change your goals card -->
        <div class="col-xs-12 col-md-4 d-flex justify-content-around">
          <div class="card ml-2 shadow p-3 mb-5 bg-white rounded" style="width: 20rem;">
            <div class="card-body text-center">
              <h3 class="card-title">Change your goals</h3>
              <div>
                <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#changeFitnessModal">Change fitness goal</button>
                <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#changeCalorieModal">Change calorie goal</button>

                <!-- CHANGE FITNESS GOAL MODAL -->
                <div class="modal fade" id="changeFitnessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Change Fitness Goal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form id="changeFitnessModal" method="POST">
                        <div class="modal-body">
                          
                          <!-- TODO: Display default fitness goal -->
                          <select class="form-control" name="new_fitness_goal" id="new_fitness_goal" >
                            <option value="" selected disabled hidden>fitness goal</option>
                            <option value="weight loss">weight loss</option>
                            <option value="muscle building">muscle building</option>
                            <option value="weight gain">weight gain</option>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                 
                <!-- CHANGE CALORIE GOAL MODAL -->
                <div class="modal fade" id="changeCalorieModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Change Calorie Goal</h5>
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
    </div>

    <hr>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>  
  </body>  
</html>


