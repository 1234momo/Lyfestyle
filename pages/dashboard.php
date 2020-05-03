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
  
  // Retrieve the data of the user
  $stmt = $connection->prepare("SELECT * FROM users WHERE email='{$email}'");
  $stmt -> execute();
  $result = $stmt -> get_result();
  $user_data = $result->fetch_array(MYSQLI_NUM);
  $old_fitness_goal = $user_data[4];

  // Update the calorie goal if the new calorie goal isn't the old
  if ($new_fitness_goal != $old_fitness_goal) {
    $query = "UPDATE users SET fitness_goal='{$new_fitness_goal}' WHERE email='{$email}'; "; 
    mysqli_query($conn, $query);

    $calories_recommended = 0;
    $gender = $user_data[5];

    // Determine the new daily calorie goal 
    if ($gender == "female") {
      if ($new_fitness_goal == "weight loss") {
        $calories_recommended = 1500;
      }
      elseif ($new_fitness_goal == "muscle building") {
        $calories_recommended = $user_data[6] * 19;
      }
      else {
        $calories_recommended = 2000;
      }
    }
    else {
      if ($new_fitness_goal == "weight loss") {
        $calories_recommended = 2000;
      }
      elseif ($new_fitness_goal == "muscle building") {
        $calories_recommended = $user_data[6] * 19;
      }
      else {
        $calories_recommended = 2500;
      }
    }

    $query = "UPDATE calories SET goal=$calories_recommended WHERE email='$email'";
    $updated_goal = mysqli_query($conn, $query);

    if (!$updated_goal) {
      echo "<p style='text-align:center;color:red'>
              Uh oh... Something seems to be wrong. Please come back later.
            </p>";
      exit();
    }
  }
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
// CHANGE WATER GOAL
//----------------------------------------------------------------------
if (array_key_exists("new_water_goal", $_POST)) {
  $new_water_goal = sanitizeMySQL($connection, $_POST['new_water_goal']);

  // Update user's water goal in DB
  $query = "UPDATE water set recommended_intake={$new_water_goal} where email='{$email}'";
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

//----------------------------------------------------------------------
// RETRIEVE WATER DATA 
//----------------------------------------------------------------------
$stmt = $connection->prepare("SELECT * FROM water WHERE email='{$email}'");
$stmt -> execute();
$water_result = $stmt -> get_result();
$water_result = $water_result->fetch_array(MYSQLI_NUM);
$water_intake = $water_result[1];
$recommended_intake = $water_result[2];
$water_card_msg;

// Determine what type of message to display in the card
if ($recommended_intake > $water_intake) {
  $water_card_msg = "<h1 class='display-3 float-left'>$water_intake<span class='d-inline-block'><h6> out of $recommended_intake</h6></span></h1>";
}
else if ($recommended_intake == $water_intake) {
  $water_card_msg = "<h1 class='display-4 float-left'>Water intake reached!</h1>";
}
else {
  $water_card_msg = "<h1 class='display-5 float-left' id='overdrinking-msg'>Overdrinking by ". ($water_intake - $recommended_intake) ." OZ!</h1>";
}

//----------------------------------------------------------------------
// RETRIEVE CALORIES DATA
//----------------------------------------------------------------------
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

// Calculate the remaining calories user needs to eat
$remaining_calories = ($calorie_goal - $food_calories) + $exercise_calories;
$remaining_calories = round($remaining_calories, 1);
$calorie_card_msg;

// Determine what type of message to display in the card
if ($remaining_calories < 0) {
  $calorie_card_msg = "<h1 class='display-5' id='overeating-msg'>Overeating " . ($remaining_calories * -1) . " calories</h1>";
}
else if ($remaining_calories == 0) {
  $calorie_card_msg = "<h1 class='display-4'>Calorie goal reached!</h1>";
}
else {
  $calorie_card_msg = "<h1 class='display-3'>$remaining_calories</h1>";
}

//----------------------------------------------------------------------
// RETRIEVE USER NAME
//----------------------------------------------------------------------
$stmt = $connection->prepare("SELECT * FROM users WHERE email='{$email}'");
$stmt -> execute();
$name_result = $stmt -> get_result();
$name_result = $name_result->fetch_array(MYSQLI_NUM);
$first_name = $name_result[1];
$last_name = $name_result[2];
$fitness_goal = $name_result[4];
$name = "$first_name $last_name";

//----------------------------------------------------------------------
// RETRIEVE FOOD LOG
//----------------------------------------------------------------------
$stmt = $connection->prepare("SELECT * FROM food WHERE email='{$email}'");
$stmt -> execute();
$food_log = $stmt -> get_result();
$food_log = $food_log->fetch_array(MYSQLI_NUM);

//----------------------------------------------------------------------
// RETRIEVE EXERCISE LOG
//----------------------------------------------------------------------
$stmt = $connection->prepare("SELECT * FROM exercise WHERE email='{$email}'");
$stmt -> execute();
$exercise_log = $stmt -> get_result();
$exercise_log = $exercise_log->fetch_array(MYSQLI_NUM);

// Close connection
$connection -> close();
$stmt -> close();

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
    <script type="text/javascript"> var food_log =<?php echo json_encode($food_log); ?>;</script>
    <script type="text/javascript"> var exercise_log =<?php echo json_encode($exercise_log); ?>;</script>    
    <script src="../view_exercise_log.js"></script>
    <script src="../view_food_log.js"></script>
  </head>

  <body onload="display_exercise_log(); display_food_log();">
    <!-- Welcome and logout btn -->
    <div class="container-fluid">
      <h1 class="display-2 d-inline-block" id="greeting-message">Welcome <?php echo $name ?>!<br>Your fitness goal is <?php echo $fitness_goal ?>.</h1>
  
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
                    <h6 class="text-uppercase" id="card-header">Eaten calories</h6>
                    <h1 class="display-3"><?php echo $food_calories ?></h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 py-2">
            <div class="card text-white bg-warning h-100 shadow">
                <div class="card-body bg-warning">
                    <h6 class="text-uppercase" id="card-header">Exercised calories</h6>
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
                          <input type="number" class="form-control" name="addWater" id="waterInput" 
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
                <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#changeWaterModal">Change water intake goal</button>

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
                          <p>Select a new fitness goal to pursue</p>
                          
                          <!-- TODO: Display default fitness goal -->
                          <select class="form-control" name="new_fitness_goal" id="new_fitness_goal" >
                            <option value="" selected disabled hidden>Fitness goal</option>
                            <option value="weight loss">Weight loss</option>
                            <option value="muscle building">Muscle building</option>
                            <option value="weight gain">Weight gain</option>
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
                          <p>Enter your daily calorie goal</p>
                          <input type="number" class="form-control" name="new_calorie_goal" id="new_calorie_goal" 
                                              placeholder="Calorie goal for each day" min="1" step="1" 
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

                <!-- CHANGE WATER GOAL MODAL -->
                <div class="modal fade" id="changeWaterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Change Water Goal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form id="changeWaterGoal" method="POST">
                        <div class="modal-body">
                          <p>Enter your daily recommended water intake in OZ</p>
                          <input type="number" class="form-control" name="new_water_goal" id="new_water_goal" 
                                              placeholder="Water intake goal for each day in OZ" min="0.1" step="0.1" 
                                              value="<?php echo $recommended_intake ?>" required>
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

    <!-- VIEW WHAT IS IN THE LOGS -->
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-food-tab" data-toggle="tab" href="#nav-food" role="tab" aria-controls="nav-food" aria-selected="true">Food log</a>
        <a class="nav-item nav-link" id="nav-exercise-tab" data-toggle="tab" href="#nav-exercise" role="tab" aria-controls="nav-exercise" aria-selected="false">Exercise log</a>
      </div>
    </nav>
    <div class="tab-content shadow" id="nav-tabContent">

      <!-- FOOD LOG TAB -->
      <div class="tab-pane fade show active bg-white" id="nav-food" role="tabpanel" aria-labelledby="nav-food-tab">
        <h1 class="text-center mt-4">Foods from list<h1><hr>
        <div class="container-fluid">
          <div class="row text-center">
            <div class="col">
              <h2 class="font-weight-light">Breakfast</h2>
              <div class="row text-center d-flex justify-content-center" id="breakfast-header">
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Weight in OZ</h5>
                  <hr>
                </div>
              </div>
              <div id="Breakfast-area" class="form-inline d-flex justify-content-center"></div>
            </div>
            <div class="col">
              <h2 class="font-weight-light">Lunch</h2>
              <div class="row text-center d-flex justify-content-center" id="lunch-header">
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Weight in OZ</h5>
                  <hr>
                </div>
              </div>
              <div id="Lunch-area" class="form-inline d-flex justify-content-center"></div>
            </div>
            <div class="col">
              <h2 class="font-weight-light">Dinner</h2>
              <div class="row text-center d-flex justify-content-center" id="dinner-header">
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Weight in OZ</h5>
                  <hr>
                </div>
              </div>
              <div id="Dinner-area" class="form-inline d-flex justify-content-center"></div>
            </div>
          </div>
        </div>

        <br>

        <h1 class="text-center">Foods you added<h1><hr>
        <div class="container-fluid">
          <div class="row text-center">
            <div class="col">
              <h2 class="font-weight-light">Breakfast</h2>
              <div class="row text-center d-flex justify-content-center" id="breakfast-custom-header">
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Calories</h5>
                  <hr>
                </div>
              </div>
              <div id="Breakfast-area-custom" class="form-inline d-flex justify-content-center"></div>
            </div>
            <div class="col">
              <h2 class="font-weight-light">Lunch</h2>
              <div class="row text-center d-flex justify-content-center" id="lunch-custom-header">
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Calories</h5>
                  <hr>
                </div>
              </div>
              <div id="Lunch-area-custom" class="form-inline d-flex justify-content-center"></div>
            </div>
            <div class="col">
              <h2 class="font-weight-light">Dinner</h2>
              <div class="row text-center d-flex justify-content-center" id="dinner-custom-header">
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col">
                  <h5 class="font-weight-light mb-3 font-italic">Calories</h5>
                  <hr>
                </div>
              </div>
              <div id="Dinner-area-custom" class="form-inline d-flex justify-content-center"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- EXERCISE LOG TAB -->
      <div class="tab-pane fade bg-white" id="nav-exercise" role="tabpanel" aria-labelledby="nav-exercise-tab">
        <div class="container-fluid">
          <div class="row text-center">
            <div class="col px-4">
              <h2 class="mt-4 mb-1">Exercises from list</h2>
              <div class="row text-center justify-content-center" id="workout-header">
                <div class="col-lg-4">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col-lg-4 mr-6">
                  <h5 class="font-weight-light mb-3 font-italic">Time in min</h5>
                  <hr>
                </div>
              </div>
              <div id="Exercise-area" class="form-inline d-flex justify-content-center col-10"></div>
            </div>
            <div class="col px-4">
              <h2 class="mt-4 mb-1">Exercises you added</h2>
              <div class="row text-center justify-content-center" id="workout-custom-header">
                <div class="col-lg-4">
                  <h5 class="font-weight-light mb-3 font-italic">Name</h5>
                  <hr>
                </div>
                <div class="col-lg-4 mr-6">
                  <h5 class="font-weight-light mb-3 font-italic">Calories burned</h5>
                  <hr>
                </div>
              </div>
              <div id="Exercise-area-custom" class="form-inline d-flex justify-content-center col-10"></div>
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


