<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);
    
    if (isset($_POST['submit']) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $itemNum = 0;

        // Insert selected items from list of exercises to DB
        while (array_key_exists("item{$itemNum}", $_POST)) {
            $exerciseName = sanitizeMySQL($connection, trim($_POST["item{$itemNum}"]));
            $timeExercised = sanitizeMySQL($connection, $_POST["item{$itemNum}timeInput"]);
            $calories_per_min_burned = search_calories_in_json($exerciseName); 

            // Select the row of the user from DB
            $stmt = $connection->prepare("SELECT workout FROM exercise WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data of the user and split it according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result->fetch_array(MYSQLI_NUM))[0]);

            $duplicate = false;

            // Look through the user's data to determine if exercise name already exists
            for ($i = 0; $i < sizeof($elements); $i += 2) {

                // If a duplicate exercise name exist, add the time entered to the time of the duplicate exercise name in the DB
                // There should only be only unique names
                if ($elements[$i] == $exerciseName) {
                    $elements[$i + 1] += $timeExercised;
                    $duplicate = true;
                    break;
                }
            }

            $elements = implode(",", $elements);

            // If a duplicate doesn't exist, append elements with the item name and weight
            if (!$duplicate) {
                $elements .= "$exerciseName,$timeExercised,";
            }

            $elements = sanitizeMySQL($connection, $elements);
            $query = "UPDATE exercise SET workout=\"{$elements}\" WHERE email='{$email}'";
            
            // Update exercise DB
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

            // Update the calories table for exercise_calories
            $calories = $calories_per_min_burned * $timeExercised;
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

            ++$itemNum;
        }

        $itemNum = 0;

        // Insert custom items from user to DB
        while (array_key_exists("item{$itemNum}customName", $_POST)) {
            $exerciseName = sanitizeMySQL($connection, trim($_POST["item{$itemNum}customName"]));
            $calories = sanitizeMySQL($connection, $_POST["item{$itemNum}calories"]);

            // Select the row of the user from DB
            $stmt = $connection->prepare("SELECT workout_custom FROM exercise WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data of the user and split it according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result->fetch_array(MYSQLI_NUM))[0]);

            $duplicate = false;

            // Look through the user's data to determine if exercise name already exists
            for ($i = 0; $i < sizeof($elements); $i += 2) {

                // If a duplicate exercise name exist, add the calories entered to the calories of the duplicate exercise name in the DB
                // There should only be only unique names
                if ($elements[$i] == $exerciseName) {
                    $elements[$i + 1] += $calories;
                    $duplicate = true;
                    break;
                }
            }

            $elements = implode(",", $elements);

            // If a duplicate doesn't exist, append elements with the item name and weight
            if (!$duplicate) {
                $elements .= "$exerciseName,$calories,";
            }

            $elements = sanitizeMySQL($connection, $elements);
            $query = "UPDATE exercise SET workout_custom=\"{$elements}\" WHERE email='{$email}'";
            
            // Update exercise DB
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

            // Update the calories table for exercise_calories
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

            ++$itemNum;
        }

        header('location: ../pages/dashboard.php');
    }
    elseif (!isset($_SESSION['email'])) {
        echo "<p style='text-align:center;color:red'>
                Please <a href='./login.php'>sign in</a> to add to your exercise log
             </p>";     
        exit();      
    }
    
    // Close connection
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
        <title>Lyfestyle | Add Exercise</title>
        <link rel="stylesheet" type="text/css", href="../assets/css/addExercise.css">
        <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="../RetrieveExerciseDB.js"></script>
    </head>

    <body onload="showExercises(event)">
        <h1>Insert Your Exercise</h1>

        <div id="overall-container">
            <div id="search-container">
                <div class="search-exercises">
                    <input id="keyword" oninput="showExercises(event)" type="text" placeholder="Search here for an exercise">
                    <div id="search-list"></div>
                </div>
            
            </div>
            <div id="list-container">
                <button id="addOwnExercise-btn" class="btn btn-primary mr-3" onClick="addCustomItem()">Add own exercise</button>

                <form id="exerciseForm" class="form-inline" method="POST">
                    <button id="addLog-btn" class="btn btn-primary" type="submit" name="submit">Add log</button><br><br>
                    <div id="results-container"></div>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body> 
</html>