<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);
    $email = $_SESSION['email'];
    
    if (isset($_POST['submit']) && isset($_SESSION['email'])) {
        $itemNum = 0;

        // Clear the Breakfast column
        $stmt = $connection->prepare("UPDATE food SET Breakfast='' WHERE email='{$email}'");
        $stmt -> execute();

        // Clear the Lunch column
        $stmt = $connection->prepare("UPDATE food SET Lunch='' WHERE email='{$email}'");
        $stmt -> execute();

        // Clear the Dinner column
        $stmt = $connection->prepare("UPDATE food SET Dinner='' WHERE email='{$email}'");
        $stmt -> execute();

        $stmt -> close();
        
        // Insert items to DB
        while (array_key_exists("item{$itemNum}", $_POST)) {
            $itemName = sanitizeMySQL($connection, $_POST["item{$itemNum}"]);
            $itemWeight = sanitizeMySQL($connection, $_POST["item{$itemNum}weight"]);
            $itemDayEaten = sanitizeMySQL($connection, $_POST["item{$itemNum}dayeaten"]);    
            
            // Select the email column and column where the item should belong in
            $stmt = $connection->prepare("SELECT email, {$itemDayEaten} FROM food WHERE email='{$email}'");
            $stmt -> execute();

            // Retrieve the data corresponding to the day eaten and spit data according to ","
            $result = $stmt -> get_result();
            $elements = explode(",", ($result->fetch_array(MYSQLI_NUM))[1]);

            $duplicate = false;

            // Check if food item already exists
            if (sizeof($elements) > 0) {
                for ($i = 0; $i < sizeof($elements); $i += 2) {
    
                    // If a food item has the same name, add the weight entered to the weight in DB
                    // There should only be only unique names
                    if ($elements[$i] == $itemName) {
                        $elements[$i + 1] += $itemWeight;
                        $duplicate = true;
                        break;
                    }
                }
            }

            $query = "";

            // If a duplicate exists, update the entire data
            if ($duplicate == true) {
                $infoStr = implode(",", $elements);
                $query = "UPDATE food SET {$itemDayEaten}='{$infoStr}' where email='{$email}'";
            }

            // If a duplicate doesn't exist, append food item entered into data
            else {
                $infoStr = $itemName . "," . $itemWeight . ",";
                $query = "UPDATE food SET {$itemDayEaten}=concat({$itemDayEaten},'{$infoStr}') where email='{$email}'";
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
    elseif (!isset($_SESSION['email'])) {
        echo "<p style='text-align:center;color:red'>
                Please <a href='./login.php'>sign in</a> to add to your water log
             </p>";     
        exit();      
    }

    $stmt = $connection->prepare("SELECT * FROM food WHERE email='{$email}'");
    $stmt -> execute();

    // Retrieve the data
    $food_result = $stmt -> get_result();
    $food_result = $food_result->fetch_array(MYSQLI_NUM);
    
    // Close all connections
    $stmt -> close();
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
        <title>Lyfestyle | Edit Food</title>
        <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
        <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script type="text/javascript"> var food_array =<?php echo json_encode($food_result); ?>;</script>
        <script src="../EditFoodDB.js"></script>
    </head>

    <body onload="displayFoods()">
        <h1>Edit your food log</h1>

        <br><br>
        
        <form method="POST">
            <div class="container-fluid">
                <div class="row text-center">
                    <div class="col">
                        <h2>Breakfast</h2>
                        <div id="Breakfast-forms"></div>
                    </div>
                    <div class="col">
                        <h2>Lunch</h2>
                        <div id="Lunch-forms"></div>
                    </div>
                    <div class="col">
                        <h2>Dinner</h2>
                        <div id="Dinner-forms"></div>
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