<?php namespace App;

use PHPScripts;

session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Załączanie innych plików
require "scripts/php/PHPScripts.php";
require "modules/GeneralClasses/ConnectToDatabase.php";
require "modules/CalendarModeClasses/User.php";
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
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>

    <link rel="stylesheet" type="text/css" href="style/style.css"/>

    <script language="JavaScript">
    // window.onbeforeunload = confirmExit;
    // function confirmExit() {
    //     return "Chcesz zamknąć stronę. Czy jesteś tego pewny? Wszystkie zmiany których nie zapisałeś zostaną utracone";
    // }
    // </script>
    <script src="scripts/jquery-3.6.0.min.js"></script>
    <title>Working time schedule</title>
</head>
<body>
    <!-- SECTION __________________________LEFT USER PANEL -->
    
    <div class="userLeftPanel">
        <div class="userLogout">
        <img src="style/img/logo.png"/>
            <a href="modules/LoginClasses/Logout.php">Logout</a><br>
            <!-- <div id="includedConstent" style="width: 20vw; height: 10vh; background-color: green;"></div> -->
            <div class="timeToLogout"><p>Time to logout:</p><p>20min</p></div>
            
        </div>
        
        <div class="userData">           
            
            <!-- //Sprawdzenie czy zostały ustawione zmienne sesyjne dla Shift i Role -->
            <?php PHPScripts::CHECK_AND_SET_Session_Var_Shift_and_Role();?>
            <?php
            $user_id = $_SESSION['user_id'];
            $user = new User($user_id);
            //echo $user->dep_id;
            $_SESSION['dep_id'] = $user->dep_id;
            echo $user->getUserData();
            ?>

           
            <div style="display: none;">
                <select id="usersToAdd" style="width: 100%;height:80%; font-size: 1vw;" name="usersToAdd[]" multiple="multiple">
                <?php echo $user->createOptionsListOfAllUsers(); ?>
                </select>

                <select id="usersToVacation" style="width: 100%;height:80%; font-size: 1vw;" name="usersToVacation[]" multiple="multiple">
                <?php echo $user->createOptionsListOfAllUsers(); ?>
                </select>
            </div>
            <style>
                #toast{
                    width: 300px;
                    height: 200px;
                    background-color: pink;
                }
            </style>
            <div id="toast" style="display: none;">Warning</div>
            <!-- //Sprawdzenie czy formularz z dodaniem użytkownika został wysłany i dodanie użytkownika do obiektu calendar -->
            <?php PHPScripts::ADD_USER_TO_Day_of_Calendar();?>
            <?php PHPScripts::REMOVE_USER_FROM_Day_Of_Calendar();?>
            <?php PHPScripts::GRANT_USER_Vacation_In_Day_of_Calendar();?>
            <?php PHPScripts::REVOKE_VACATION_For_A_User();?>
            <script>
                var listOfUsersToAdd = document.getElementById("usersToAdd").outerHTML;
                var listOfUsersToGrantVacation = document.getElementById("usersToVacation").outerHTML;
            </script>
        </div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('calendarMode').style.display = 'flex';document.getElementById('addUser').style.display = 'none'">Calendar Mode</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block';">Simple Mode</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block';document.getElementById('calendarMode').style.display = 'none'">Add user</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block'">Add shift</div>
    </div>
    
    <!-- SECTION __________________________CENTER PANEL -->
    <div class="centerPanel">
        <!-- // Moduł: Dodawanie użytkownika / pracownika -->
        <div class="addUser" id="addUser">
            <?php include("modules/AddUserModule.php");?>
        </div>

        <!-- // Moduł: Obsługa grafiku w metodzie kalendarzowej -->
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
            Shift::GenerateFormSelectForShifts($_SESSION['dep_id']);
            ?>
        </div>
        <script src="scripts/closeNewWindow.js"></script>

        </script>
        <script src="scripts/showCalendarDayControls.js"></script>
</body>
</html>