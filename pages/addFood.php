<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);
    
    if (isset($_POST['submit']) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $itemNum = 0;

        // --------------------------------------------------------------------------------------------------------------------------------------
        // Insert items that were from the list of foods to DB
        while (array_key_exists("item{$itemNum}", $_POST)) {
            $itemName = sanitizeMySQL($connection, trim($_POST["item{$itemNum}"]));
            $itemWeight = sanitizeMySQL($connection, $_POST["item{$itemNum}weight"]);
            $itemDayEaten = sanitizeMySQL($connection, $_POST["item{$itemNum}dayeaten"]);    
            $calories_per_oz = search_calories_in_json($itemName);
            
            // Select the email column and column where the item should belong in
            $stmt = $connection->prepare("SELECT {$itemDayEaten} FROM food WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data corresponding to the day eaten and spit data according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result->fetch_array(MYSQLI_NUM))[0]);

            $duplicate = false;

            // Check if food item already exists
            for ($i = 0; $i < sizeof($elements); $i += 2) {
                // If a food item has the same name, add the weight entered to the weight in DB
                // There should only be only unique names
                if ($elements[$i] == $itemName) {
                    $elements[$i + 1] += $itemWeight;
                    $duplicate = true;
                    break;
                }
            }

            $elements = implode(",", $elements);

            // If a duplicate doesn't exist, append elements with the item name and weight
            if ($duplicate != true) {
                $elements .= $itemName . "," . $itemWeight . ",";
            }

            $elements = sanitizeMySQL($connection, $elements);
            $query = "UPDATE food SET $itemDayEaten=\"{$elements}\" WHERE email='{$email}'";
            
            // Update database
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
            $calories = $calories_per_oz * $itemWeight;
            $stmt = $connection->prepare("UPDATE calories set food_calories = food_calories + {$calories} where email='{$email}'");
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

        // Insert custom items that were from the user to DB
        while (array_key_exists("item{$itemNum}customName", $_POST)) {
            $name = sanitizeMySQL($connection, trim($_POST["item{$itemNum}customName"]));
            $calories = sanitizeMySQL($connection, $_POST["item{$itemNum}calories"]);
            $dayEaten = sanitizeMySQL($connection, $_POST["item{$itemNum}customSelector"]);    
            
            // Select the email column and column where the item should belong in
            $stmt = $connection->prepare("SELECT {$dayEaten}_custom FROM food WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data corresponding to the day eaten and spit data according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result->fetch_array(MYSQLI_NUM))[0]);

            $duplicate = false;

            // Check if food item already exists
            for ($i = 0; $i < sizeof($elements); $i += 2) {
                // If a food item has the same name, add the weight entered to the weight in DB
                // There should only be only unique names
                if ($elements[$i] == $name) {
                    $elements[$i + 1] += $calories;
                    $duplicate = true;
                    break;
                }
            }

            $elements = implode(",", $elements);

            // If a duplicate doesn't exist, append elements with the item name and weight
            if (!$duplicate) {
                $elements .= "$name,$calories,";
            }

            $elements = sanitizeMySQL($connection, $elements);
            $query = "UPDATE food SET {$dayEaten}_custom=\"$elements\" WHERE email='{$email}'";
            
            // Update database
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
            $stmt = $connection->prepare("UPDATE calories set food_calories = food_calories + {$calories} where email='{$email}'");
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
    elseif (!isset($_SESSION['email'])) {
        echo "<p style='text-align:center;color:red'>
                Please <a href='./login.php'>sign in</a> to add to your food log
             </p>";     
        exit();      
    }
    
    // Close connection
    $connection -> close();

    function search_calories_in_json($search_name) {
        $data = json_decode(file_get_contents("../database/food_database.json"), true);
        $data = $data["Sheet1"];

        foreach ($data as $name) {
            if ($name["Food_name"] == $search_name) {
                return floatval($name["Calories_per_oz"]);
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
        <title>Lyfestyle | Add Food</title>
        <link rel="stylesheet" type="text/css", href="../assets/css/addFood.css">
        <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="../RetrieveFoodDB.js"></script>
    </head>

    <body onload="showFoods(event)">
        <h1>Insert Your Food Intake</h1>

        <div id="overall-container">
            <div id="search-container">
                <div class="search-foods">
                    <input id="keyword" oninput="showFoods(event)" type="text" placeholder="Search food item..">
                    <div id="search-list"></div>
                </div>
            
            </div>
            <div id="list-container">
                <button id="addOwnFood-btn" class="btn btn-primary mr-3" onClick="addCustomItem()">Add own food</button>

                <form id="eatenForm" class="form-inline" method="POST">
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