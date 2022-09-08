<?php namespace App;session_start();
session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "ConnectToDatabase.php";
require "/var/www/test/public_html/app/User.php";
?>
<!DOCTYPE html>
<html>
    <?php
        // Sprawdzenie czy użytkownik jest zalogowany
        if(!isset($_SESSION['user_id']) && !isset($_SESSION['log'])) header("Location: ../");
        else if($_SESSION['log'] == false) header("Location: ../");
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/style.css"/>
    <title>Working time schedule</title>
</head>
<body>
    <!-- SECTION __________________________LEFT USER PANEL -->
    <div class="userLeftPanel">
        <div class="userLogout">
            <a href="Logout.php">Logout</a><br>
            <div class="timeToLogout"><p>Time to logout: </p><p>20min</p></div>
        </div>
        
        <div class="userData">           
            <?php
                $user_id = $_SESSION['user_id'];
                $user = new User($user_id);
                // Wyświetlenie danych użytkownika
                echo $user->getUserData();
            ?>
        </div>
    </div>
    <!-- SECTION __________________________CALENDAR PANEL -->
    <div class="calendarCenterPanel">


    </div>
    <!-- SECTION __________________________RIGHT STATS PANEL -->
    <div class="rightStatsPanel">
    
    </div>
</body>
</html>