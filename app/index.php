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
    <script src="/../scripts/jquery-3.6.0.min.js"></script>
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
        <div class="button1 addUserButtonLeft">Add user</div>
    </div>
    <!-- SECTION __________________________CENTER PANEL -->
    <div class="centerPanel">
        <div class="addUser">

            <form method="post" class="formAdduser">
                <h2>Add new user</h2>
                Name: <input type="text" name="name"/>
                Surname: <input type="text" name="surname"/>
                Login: <input type="text" name="login"/>
                Email: <input type="text" name="email"/>
                CustomID: <input type="text" name="custom_id"/>
                FullTime:   <select name="fulltime">
                                <option value="1">Full Time (1)</option>
                                <option value="0.5">Half (1/2)</option>
                                <option value="0.6">Three-Fifths (3/5)</option>
                                <option value="0.8">Four Fifths (4/5)</option>
                            </select><br>
                        
                        <p>Avatar:</p>
                        <img id="userAvatarShow" src="style/img/user.png" alt="your image" />
                            <input accept="image/*" type='file' id="userAvatar" />
                            
                            <script>
                                var changedAv = 0;
                                userAvatar.onchange = evt => {
                                const [file] = userAvatar.files
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
                            
                            <input type="submit" value="Add user" class="button1"/>
            </form>
        </div>

    </div>
    <!-- SECTION __________________________RIGHT STATS PANEL -->
    <div class="rightStatsPanel">
    
    </div>
</body>
</html>