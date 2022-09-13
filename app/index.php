<?php namespace App;session_start();
session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "ConnectToDatabase.php";
require "/var/www/test/public_html/app/User.php";
require "Month.php";
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
    <script src="/../scripts/jquery-3.6.0.min.js"></script>
    <title>Working time schedule</title>
</head>
<body>
    <!-- SECTION __________________________LEFT USER PANEL -->
    <div class="userLeftPanel">
        <div class="userLogout">
        <img src="style/img/logo.png"/>
            <a href="Logout.php">Logout</a><br>
            <div class="timeToLogout"><p>Time to logout:</p><p>20min</p></div>
        </div>
        
        <div class="userData">           
            <?php
                $user_id = $_SESSION['user_id'];
                $user = new User($user_id);
                // Wyświetlenie danych użytkownika
                echo $user->getUserData();
            ?>
        </div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('calendarMode').style.display = 'flex';document.getElementById('addUser').style.display = 'none'">Calendar Mode</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block';">Simple Mode</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block';document.getElementById('calendarMode').style.display = 'none'">Add user</div>
        <div class="button1 addUserButtonLeft" onclick="document.getElementById('addUser').style.display = 'block'">Add shift</div>
    </div>
    <!-- SECTION __________________________CENTER PANEL -->
    <div class="centerPanel">
        <div class="addUser" id="addUser">

            <form method="post" class="formAdduser">
                <h2>Add new user</h2>
                Name: <input type="text" name="addu_name"/>
                Surname: <input type="text" name="addu_surname"/>
                Login: <input type="text" name="addu_login"/>
                Email: <input type="text" name="addu_email"/>
                CustomID: <input type="text" name="addu_custom_id"/>
                FullTime:   <select name="addu_fulltime">
                                <option value="1">Full Time (1)</option>
                                <option value="0.5">Half (1/2)</option>
                                <option value="0.6">Three-Fifths (3/5)</option>
                                <option value="0.8">Four Fifths (4/5)</option>
                            </select><br>
                        
                        <p>Avatar:</p>
                        <img id="userAvatarShow" src="style/img/user.png" alt="your image" />
                            <input accept="image/*" type='file' id="addu_userAvatar" />
                            
                            <script>
                                var changedAv = 0;
                                addu_userAvatar.onchange = evt => {
                                const [file] = addu_userAvatar.files
                                if (file)
                                {
                                    userAvatarShow.src = URL.createObjectURL(file);
                                    changedAv = 1;
                                }
                                }
                               
                            </script>
                            Gender:
                            <select id="gender" name="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <script>
                                 $('#gender').change(function() {
                                    console.log("hello");
                                    if($(this).val() === 'female' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/user2.png';
                                    }
                                    if($(this).val() === 'male' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/user.png';
                                    }
                                });

                            </script>
                            Temporary password:
                            <input type="password" name="password"/>

                            <input type="submit" value="Add user" class="button1"/>
            </form>
        </div>

        <div class="calendarMode" id="calendarMode">
            
            <?php
            //później trzeba zmienić na konkretnego użytkownika
                $mth = new Month(10,2022);
                echo "<h2>".$mth->getName()." ".$mth->getYear()."</h2>";
                $mth->drawMonth();
            ?>
             <div class="button1 printCalendar">Print</div>
        </div>
       
    </div>
    <!-- SECTION __________________________RIGHT STATS PANEL -->
    <div class="rightStatsPanel">
    
    </div>
</body>
</html>