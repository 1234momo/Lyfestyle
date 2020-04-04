<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);
    
    if (isset($_POST['submit']) && isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $itemNum = 0;

        // Insert items from DB
        while (array_key_exists("item{$itemNum}", $_POST)) {
            $itemName = sanitizeMySQL($connection, $_POST["item{$itemNum}"]);
            $itemWeight = sanitizeMySQL($connection, $_POST["item{$itemNum}weight"]);
            $itemDayEaten = sanitizeMySQL($connection, $_POST["item{$itemNum}dayeaten"]);    
            
            $stmt = $connection->prepare("SELECT id, {$itemDayEaten} FROM food WHERE id={$id}");
            $stmt -> execute();

            // Retrieve the data corresponding to the day eaten and spit data according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result->fetch_array(MYSQLI_NUM))[1]);

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

            $query = "";

            // If a duplicate exists, update the entire data
            if ($duplicate == true) {
                $infoStr = implode(",", $elements);
                $query = "UPDATE food set {$itemDayEaten}='{$infoStr}' where id={$id}";
            }

            // If a duplicate doesn't exist, append food item entered into data
            else {
                $infoStr = $itemName . "," . $itemWeight . ",";
                $query = "UPDATE food set {$itemDayEaten}=concat({$itemDayEaten},'{$infoStr}') where id={$id}";
            }
            
            $stmt = $connection->prepare($query);
            $stmt -> execute();

            if (!$stmt) {
                $stmt -> close();
                destroy_session_and_data();
                echo "<p style='text-align:center;color:red'>
                        Unable to insert your data into the database, you have been logged out. Please try again later.
                      </p>";
                exit();
            }

            $stmt -> close();

            $itemNum += 1;
        }

        header('location: ../pages/dashboard.php');
    }
    elseif (!isset($_SESSION['id'])) {
        echo "<p style='text-align:center;color:red'>
                Please <a href='./login.php'>sign in</a> to add to your food log
             </p>";     
        exit();      
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
        <title>Lyfestyle | Add Food</title>
        <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
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
                <button id="addOwnFood-btn" class="btn btn-primary" onClick="addCustomItem()">Add own food</button>

                <form id="eatenForm" class="form-inline" method="POST">
                    <button id="addLog-btn" class="btn btn-primary" type="submit" name="submit">Add log</button><br><br>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body> 
</html>