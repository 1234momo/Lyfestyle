<?php 
require_once("../database/connection.php"); 
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
                <form id="eatenForm" action="postToDB.php" method="post">
                    <input id="add-log-btn" class="btn btn-primary" type="submit" onClick="location.href = './dashboard.php'" value="Add log">
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body> 
</html>