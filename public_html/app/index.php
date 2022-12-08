<?php namespace App;

use PHPScripts;

session_start();
session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "scripts/php/PHPScripts.php";
require "ConnectToDatabase.php";
require "modules/CalendarModeClasses/User.php";
// require "modules/CalendarModeClasses/Month.php";
require "modules/CalendarModeClasses/Shift.php";
require "modules/CalendarModeClasses/Role.php";
require "modules/CalendarModeClasses/Calendar.php";
require "modules/CalendarModeClasses/Day.php";

?>
<!DOCTYPE html>
<html>
    <?php PHPScripts::CHECK_User_Is_Logged()?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/style.css"/>
    <script src="scripts/jquery-3.6.0.min.js"></script>
    <title>Working time schedule</title>
</head>
<body>
    <!-- SECTION __________________________LEFT USER PANEL -->
    
    <div class="userLeftPanel">
        <div class="userLogout">
        <img src="style/img/logo.png"/>
            <a href="Logout.php">Logout</a><br>
            <!-- <div id="includedConstent" style="width: 20vw; height: 10vh; background-color: green;"></div> -->
            <div class="timeToLogout"><p>Time to logout:</p><p>20min</p></div>
            
        </div>
        
        <div class="userData">           
            <?php
            //Sprawdzenie czy zostały ustawione zmienne sesyjne dla Shift i Role
            PHPScripts::CHECK_AND_SET_Session_Var_Shift_and_Role();
            $user_id = $_SESSION['user_id'];
            $user = new User($user_id);
            $_SESSION['dep_id'] = $user->dep_id;
            // Wyświetlenie danych użytkownika
            echo $user->getUserData();
            echo '<div style="display: none;">';
            echo $user->getListOfUsers();
            echo '</div>';
            ?>
            <script>
                var listOfUsers = document.getElementById("usersToAdd").outerHTML;
            </script>
        </div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('calendarMode').style.display = 'flex';document.getElementById('addUser').style.display = 'none'">Calendar Mode</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block';">Simple Mode</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block';document.getElementById('calendarMode').style.display = 'none'">Add user</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block'">Add shift</div>
    </div>

    <!-- SECTION __________________________CENTER PANEL -->
    <div class="centerPanel">
       
        <div class="addUser" id="addUser">
            <?php include("modules/AddUserModule.php");?>
        </div>

        
        <div class="calendarMode" id="calendarMode">
            <?php include("modules/CalendarModeModule.php");?>
        </div>
       
    </div>
    <!-- SECTION __________________________RIGHT STATS PANEL -->
    <div class="rightPanel">   
            <?php
            Role::displaySelectRolesForUser();   
            ?>
            <?php
            Shift::GenerateFormSelectForShifts($user->dep_id);
            ?>
        </div>
</body>
</html>