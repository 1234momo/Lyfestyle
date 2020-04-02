<?php 
    require_once("../database/connection.php"); 
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);
    
    session_start();

    if (isset($_POST['submit']) && isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $itemNum = 0;
        $customNum = 0;

        // Insert items from DB
        while (array_key_exists("item{$itemNum}", $_POST)) {
            $itemName = sanitizeMySQL($connection, $_POST["item{$itemNum}"]);
            $itemWeight = sanitizeMySQL($connection, $_POST["item{$itemNum}weight"]);
            $itemDayEaten = sanitizeMySQL($connection, $_POST["item{$itemNum}dayeaten"]);            

            $allInfoToArray = array("name" => $itemName, "weight" => $itemWeight);
            $infoArray = serialize($allInfoToArray);

            $stmt = $connection->prepare("UPDATE food set {$itemDayEaten}=concat({$itemDayEaten},'|{$infoArray}') where id={$id}");
            $stmt -> execute();

            $stmt->close();
            $itemNum += 1;
        }

        // Insert custom items
        while (array_key_exists("custom{$customNum}", $_POST)) {
            $customName = sanitizeMySQL($connection, $_POST["custom{$customNum}"]);
            $customWeight = sanitizeMySQL($connection, $_POST["custom{$customNum}weight"]);
            $customDayEaten = sanitizeMySQL($connection, $_POST["custom{$customNum}dayeaten"]);            

            $allInfoToArray = array("name" => $customName, "weight" => $customWeight);
            $infoArray = serialize($allInfoToArray);

            $stmt = $connection->prepare("UPDATE food set {$itemDayEaten}=concat({$itemDayEaten},'|{$infoArray}') where id={$id}");
            $stmt -> execute();

            $stmt->close();
            $customNum += 1;
        }

        header('location: ../pages/dashboard.php');
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
                <button id="add-customItem-btn" class="btn btn-primary" onClick="addCustomItem()">Add custom food</button>

                <form id="eatenForm" action="#" method="POST">
                    <button id="add-log-btn" class="btn btn-primary" type="submit" name="submit" onClick="location.href = '#'">Add log</button><br>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body> 
</html>