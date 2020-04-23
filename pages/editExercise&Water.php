<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);

    // Checks if email is set. If it isn't set, ask user to log in
    if (!isset($_SESSION['email'])) {
        echo "<p style='text-align:center;color:red'>
                Please <a href='./login.php'>sign in</a> to add to your water log
             </p>";     
        exit();      
    }

    $email = $_SESSION['email'];
    
    // Save edit changes to database
    if (isset($_POST['submit']) && isset($_SESSION['email'])) {
        $water_value = floatval(sanitizeMySQL($connection, $_POST["consumptionNum"]));
        
        // Update the water value first
        $stmt = $connection->prepare("UPDATE water set consumption={$water_value} where email='{$email}'");
        $stmt -> execute();

        // If updating water value went wrong, output a message
        if (!$stmt) {
            $stmt -> close();
            echo "<p style='text-align:center;color:red'>
            Unable to insert your data into the database. Please try again later.
            </p>";
            exit();
        }

        $stmt -> close();

        // --------------------------------------------------------------------------------------------------------------------------------------
        // Clear the exercise table
        $stmt = $connection->prepare("UPDATE exercise set workout='' where email='{$email}'");
        $stmt -> execute();

        // Clear the exercise table
        $stmt = $connection->prepare("UPDATE exercise set workout_custom='' where email='{$email}'");
        $stmt -> execute();
        
        // Clear the calories table for the exercise_calories
        $stmt = $connection->prepare("UPDATE calories set exercise_calories=0 where email='{$email}'");
        $stmt -> execute();

        // --------------------------------------------------------------------------------------------------------------------------------------
        $itemNum = 0;

        // Insert items from list to DB
        while (array_key_exists("item{$itemNum}", $_POST)) {
            $exerciseName = sanitizeMySQL($connection, trim($_POST["item{$itemNum}"]));
            $timeExercised = sanitizeMySQL($connection, $_POST["item{$itemNum}time"]);
            $calories_per_min = search_calories_in_json($exerciseName);
            
            // Select the row of the user from DB
            $stmt = $connection->prepare("SELECT workout FROM exercise WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data of the user and split it according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result -> fetch_array(MYSQLI_NUM))[0]);

            $duplicate = false;

            // Look through the user's data to determine if exercise name already exists
            for ($i = 0; $i < sizeof($elements); $i += 2) {

                // If a duplicate exercise name exist, add the time entered to the time of the duplicate exercise name in the DB
                // There should only be only unique names
                if (trim($elements[$i]) == $exerciseName) {
                    $elements[$i + 1] += $timeExercised;
                    $duplicate = true;
                    break;
                }
            }

            $query = "";

            // If a duplicate exists, update the entire data
            if ($duplicate == true) {
                // Combine the updated together with a "," separating each element
                $infoStr = implode(",", $elements);

                // Update the data in the DB with the new data
                $query = "UPDATE exercise set workout='{$infoStr}' where email='{$email}'";
            }

            // If a duplicate doesn't exist, append exercise name and time into DB data
            else {
                $infoStr = $exerciseName . "," . $timeExercised . ",";
                $query = "UPDATE exercise set workout=concat(workout,'{$infoStr}') where email='{$email}'";
            }
            
            $stmt = $connection->prepare($query);
            $stmt -> execute();

            // If something went wrong, output a message
            if (!$stmt) {
                $stmt -> close();
                echo "<p style='text-align:center;color:red'>
                        Unable to insert your data into the database. Please try again later.
                      </p>";
                exit();
            }

            // Update the calories table for food_calories
            $calories = $calories_per_min * $timeExercised;
            $stmt = $connection->prepare("UPDATE calories set exercise_calories = exercise_calories + {$calories} where email='{$email}'");
            $stmt -> execute();

            // If something went wrong, output a message
            if (!$stmt) {
                $stmt -> close();
                echo "<p style='text-align:center;color:red'>
                        Unable to insert your data into the database. Please try again later.
                      </p>";
                exit();
            }

            $stmt -> close();

            $itemNum += 1;
        }

        // --------------------------------------------------------------------------------------------------------------------------------------
        $itemNum = 0;

        // Insert items from list to DB
        while (array_key_exists("item{$itemNum}customName", $_POST)) {
            $exerciseName = sanitizeMySQL($connection, trim($_POST["item{$itemNum}customName"]));
            $calories = sanitizeMySQL($connection, $_POST["item{$itemNum}calories"]);
            $calories_per_min = search_calories_in_json($exerciseName);

            // Select the row of the user from DB
            $stmt = $connection->prepare("SELECT workout_custom FROM exercise WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data of the user and split it according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result -> fetch_array(MYSQLI_NUM))[0]);

            $duplicate = false;

            // Look through the user's data to determine if exercise name already exists
            for ($i = 0; $i < sizeof($elements); $i += 2) {

                // If a duplicate exercise name exist, add the time entered to the time of the duplicate exercise name in the DB
                // There should only be only unique names
                if (trim($elements[$i]) == $exerciseName) {
                    $elements[$i + 1] += $calories;
                    $duplicate = true;
                    break;
                }
            }

            $query = "";

            // If a duplicate exists, update the entire data
            if ($duplicate == true) {
                // Combine the updated together with a "," separating each element
                $infoStr = implode(",", $elements);

                // Update the data in the DB with the new data
                $query = "UPDATE exercise set workout_custom='{$infoStr}' where email='{$email}'";
            }

            // If a duplicate doesn't exist, append exercise name and time into DB data
            else {
                $infoStr = $exerciseName . "," . $calories . ",";
                $query = "UPDATE exercise set workout_custom=concat(workout_custom,'{$infoStr}') where email='{$email}'";
            }
            
            $stmt = $connection->prepare($query);
            $stmt -> execute();

            // If something went wrong, output a message
            if (!$stmt) {
                $stmt -> close();
                echo "<p style='text-align:center;color:red'>
                        Unable to insert your data into the database. Please try again later.
                      </p>";
                exit();
            }

            // Update the calories table for food_calories
            $calories = doubleval($calories);
            $stmt = $connection->prepare("UPDATE calories set exercise_calories = exercise_calories + {$calories} where email='{$email}'");
            $stmt -> execute();

            // If something went wrong, output a message
            if (!$stmt) {
                $stmt -> close();
                echo "<p style='text-align:center;color:red'>
                        Unable to insert your data into the database. Please try again later.
                      </p>";
                exit();
            }

            $stmt -> close();

            $itemNum += 1;
        }

        header('location: ../pages/dashboard.php');
    }
    
    // --------------------------------------------------------------------------------------------------------------------------------------
    // Retrieve the exercise data
    $stmt = $connection->prepare("SELECT * FROM exercise WHERE email='{$email}'");
    $stmt -> execute();
    $exercise_result = $stmt -> get_result();
    $exercise_result = $exercise_result->fetch_array(MYSQLI_NUM);

    // Retrieve the water data
    $stmt = $connection->prepare("SELECT * FROM water WHERE email='{$email}'");
    $stmt -> execute();
    $water_result = $stmt -> get_result();
    $water_result = $water_result->fetch_array(MYSQLI_NUM)[1];

    // --------------------------------------------------------------------------------------------------------------------------------------
    // Close all connections
    $stmt -> close();
    $connection -> close();

    function search_calories_in_json($search_name) {
        $data = json_decode(file_get_contents("../database/exercise_database.json"), true);
        $data = $data["Sheet1"];

        foreach ($data as $name) {
            if ($name["Exercise_name"] == $search_name) {
                return floatval($name["Calories_per_minute"]);
            }
        }
    }
    
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
        <title>Lyfestyle | edit exercise & water</title>
        <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
        <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script type="text/javascript"> var exercise_array =<?php echo json_encode($exercise_result); ?>;</script>
        <script src="../EditExerciseDB.js"></script>
        <!-- <script src="../EditWaterDB.js"></script> -->
    </head>

    <body onload="displayExerciseLog()">
        <h1>Edit your exercise & water log</h1>

        <br><br>
        
        <form method="POST">
            <div class="container-fluid">
                <div class="row text-center">
                    <div class="col-4">
                        <h2>Exercises from list</h2>
                        <div id="Exercise-forms" class="mt-3"></div>
                    </div>
                    <div class="col-4">
                        <h2>Exercises you added</h2>
                        <div id="Exercise-forms-custom" class="mt-3"></div>
                    </div>
                    <div class="col-4">
                        <h2>Water</h2>
                        <div id="Water-forms" class="form-group row text-center justify-content-center">
                            <div class="col-7 mt-1">
                                <input type="number" class="form-control" name="consumptionNum" id="waterInput" 
                                       placeholder="Water drank in OZ" min="0.01" step="0.01" 
                                       value="<?php echo $water_result ?>" required><br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row text-center">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                </div>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body> 
</html>