<?php namespace App;

//use PHPScripts;

session_start();
date_default_timezone_set('America/Los_Angeles');
//date_default_timezone_set('Europe/Warsaw');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
//Załączanie innych plików
require "scripts/php/PHPScripts.php";
require "modules/GeneralClasses/Encrypt.php";
require "modules/GeneralClasses/ConnectToDatabase.php";
require "modules/CalendarModeClasses/User.php";
require "modules/CalendarModeClasses/Shift.php";
require "modules/CalendarModeClasses/Role.php";
require "modules/CalendarModeClasses/Calendar.php";
require "modules/CalendarModeClasses/Day.php";
require "modules/CalendarModeClasses/HoursOfWork.php";
require "modules/CalendarModeClasses/Statistics.php";

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <link rel="icon" type="image/x-icon" href="style/img/favicon.ico">
    <link rel="stylesheet" type="text/css" href="style/style.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_CalendarModule.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_ShiftModule.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_AddDepartmentModule.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_Notifications.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_UserModule.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_userSettings.css"/>
    <link rel="stylesheet" type="text/css" href="style/style_RolesModule.css"/>
    
    <script src="scripts/jquery-3.6.0.min.js"></script>
    <script src="scripts/EnumClasses.js"></script>
    <script language="JavaScript">
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        return "Chcesz zamknąć stronę. Czy jesteś tego pewny? Wszystkie zmiany których nie zapisałeś zostaną utracone";
    }
    </script>
    <script>
        var isCalendarSave = '<?php echo $_SESSION['IsCalendarSave'];?>';
    </script>
    <title>Working time schedule</title>
</head>
<body>
    <script>var roleOfUser = <?php echo $_SESSION['Current_User_Role_Id'];?>;</script>
    <script src="scripts/showCurrentUserSettings.js"></script>
    <script src="scripts/Notification.js"></script>
    <script src="scripts/User.js"></script>
    <script src="scripts/Shift.js"></script>
    <script src="scripts/Roles.js"></script>
    
    <?php PHPScripts::CHANGE_MONTH();?>
    <?php PHPScripts::CHECK_User_Is_Logged()?>
    <?php PHPScripts::CHECK_AND_SET_Session_Var_Shift_and_Role();?>
    <?php PHPScripts::ADD_USER_TO_Day_of_Calendar();?>
    <?php PHPScripts::ADD_NEW_SHIFT();?>
    <?php PHPScripts::ADD_NEW_DEPARTMENT();?>
    <?php PHPScripts::ADD_NEW_ROLE();?>
    <?php PHPScripts::REMOVE_ROLE();?>
    <?php PHPScripts::REMOVE_SHIFT();?>
    <?php PHPScripts::REMOVE_USER_FROM_SYSTEM();?>
    <?php PHPScripts::CHANGE_SETTINGS_CURRENT_USER();?>
    
    
    <!-- SECTION __________________________LEFT USER PANEL -->
    
    <div class="userLeftPanel no-print">
        <div class="userLogout">
        <img src="style/img/logo.png"/>
            <a href="modules/LoginClasses/Logout.php">Logout</a><br>
            <!-- <div id="includedConstent" style="width: 20vw; height: 10vh; background-color: green;"></div> -->
            <div class="timeToLogout"><p>Time to logout:</p><p id="timeToLogout">20min</p></div>
            <script src="scripts/timeToLogout.js"></script>
        </div>
        
        <div class="userData">           
            
            <!-- //Sprawdzenie czy zostały ustawione zmienne sesyjne dla Shift i Role -->

            <?php
            $user_id = $_SESSION['User_Id'];

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
<!-- TOAST -->
            
            <!-- //Sprawdzenie czy formularz z dodaniem użytkownika został wysłany i dodanie użytkownika do obiektu calendar -->

           
            <?php PHPScripts::REMOVE_USER_FROM_Day_Of_Calendar();?>
            <?php PHPScripts::GRANT_USER_Vacation_In_Day_of_Calendar();?>
            <?php PHPScripts::REVOKE_VACATION_For_A_User();?>
            <?php PHPScripts::SAVE_IN_DATABASE();?>
            <?php PHPScripts::CreateArrayOfHoursOfWorkForUsers();?>
            <?php PHPScripts::CHANGE_USER_STATS(); ?>

            <script>
                var listOfUsersToAdd = document.getElementById("usersToAdd").outerHTML;
                var listOfUsersToGrantVacation = document.getElementById("usersToVacation").outerHTML;
            </script>
        </div>
            <?php PHPScripts::PERMISSION_VIEW_MODULES() ?>
    </div>
    
    <!-- SECTION __________________________CENTER PANEL -->
    <div class="centerPanel landScape">
        <!-- // Moduł: Dodawanie użytkownika / pracownika -->
        <div class="UserModule" id="UserModule">
            <?php include("modules/UserModule.php");?>
        </div>

        <!-- // Moduł: Obsługa grafiku w metodzie kalendarzowej -->
        <div class="calendarMode" id="calendarMode">
            <?php include("modules/CalendarModeModule.php");?>
        </div>
        <?php 
        // Moduł: Dodawanie zmiany
        if($_SESSION['Current_User_Role_Id'] == 1 || $_SESSION['Current_User_Role_Id'] == 2) 
        {

        
        echo '<div class="ShiftModule" id="ShiftModule">';
            include("modules/ShiftModule.php");
        echo '</div>';

         // Moduł: Ról 
        echo '<div class="rolesModule" id="rolesModule">';
             include("modules/RolesModule.php");
        echo '</div>';

        if($_SESSION['Current_User_Role_Id'] == 1 )
        {
        // Moduł: Dodawanie działu 
        echo '<div class="addDepartmentModule" id="addDepartmentModule">';
            include("modules/AddDepartmentModule.php");
        echo '</div>';
        }
        
        }
       ?>
       <a id="licences" class="no-print" href="licences.txt">Licences</a>
       <a id="licenceImages" class="no-print" href="http://www.freepik.com">Background Images <br>designed by pch.vector / Freepik</a>
    </div>
    <!-- SECTION __________________________RIGHT STATS PANEL -->
    
    <div class="rightPanel no-print">   
        <div class="filtersForCalendar">
            <div class="Filters_Header header-cal-right">Select Filters</div>
            <?php
            Role::displaySelectRolesForUser();   
            ?>
            <?php
            Shift::GenerateFormSelectForShifts($_SESSION['dep_id']);
            ?>
        </div>
        
            <?php Statistics::DrawListOfUsersForStatistic() ?>
       
       
            
                <?php Statistics::DrawStatisticsChartForUser(); ?>
            <div class="StatisticCalendar">
                <?php Statistics::DrawMiniCalendarForUserStatistics();?>
            </div>
        </div>

    </div>
        <script src="scripts/closeNewWindow.js"></script>
        <script src="scripts/Statistics.js"></script>
        <script src="scripts/Calendar.js"></script>

        
        <script src="scripts/showCalendarDayControls.js"></script>
        
        <?php PHPScripts::WHAT_MODULE_IS_SELECTED();?>
</body>
</html>