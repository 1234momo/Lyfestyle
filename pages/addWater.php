<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);
    
    if (isset($_POST['submit']) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        $consumption = sanitizeMySQL($connection, $_POST["consumptionNum"]);
            
        // Select the row of the user from DB
        $stmt = $connection->prepare("SELECT * FROM water WHERE email='{$email}'");
        $stmt -> execute();

        // Retrieve the data of the user
        $result = $stmt -> get_result();
        $consumption_DB = ($result->fetch_array(MYSQLI_NUM))[1];

        //If the size of the consumption isn't 0, add the consumption number from the db to the consumption variable
        if ($consumption_DB != 0) {
            $consumption += $consumption_DB;
        } 

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
        header('location: ../pages/dashboard.php');
    }
    elseif (!isset($_SESSION['email'])) {
        echo "<p style='text-align:center;color:red'>
                Please <a href='./login.php'>sign in</a> to add to your water log
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
        <title>Lyfestyle | Add Water</title>
        <link rel="stylesheet" type="text/css", href="../assets/css/main.css">
        <link rel="icon" type="image/png" href="../assets/images/Lyfestyle_favicon.png">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="../RetrieveExerciseDB.js"></script>
    </head>

    <body>
        <h1>Add to your water log</h1>

        <div class="container">
            <div class="row align-items-center h-100">
                <div class="col text-center align-middle col-6 mx-auto">
                    <div class="form-group">
                        <form id="waterForm" method="POST">
                            <label for="waterInput">Input water consumption</label>
                            <input type="number" class="form-control" name="consumptionNum" id="waterInput" min="0.1" max="100" step="0.1" required><br>
                            <button id="addWaterLog-btn" class="btn btn-primary" type="submit" name="submit">Add log</button>
                        </form>

                        <?php 
                            require_once("../database/connection.php"); 
                            $connection = new mysqli($hostname, $username, $password, $database);
                            if ($connection->connect_error) die($connection->connect_error);

                            $email = $_SESSION['email'];

                            // Select the row of the user from DB
                            $stmt = $connection->prepare("SELECT * FROM water WHERE email='{$email}'");
                            $stmt -> execute();

                            // Retrieve the data of the user
                            $result = $stmt -> get_result();
                            $consumption_DB = ($result->fetch_array(MYSQLI_NUM))[1];
                            
                            echo "<i> You have drank " . $consumption_DB . " liters of water so far";
                        ?>

                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body> 
</html>