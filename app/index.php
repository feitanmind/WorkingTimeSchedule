<?php namespace App;session_start();
session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "ConnectToDatabase.php";
require "User.php";
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
    <script src="scripts/jquery-3.6.0.min.js"></script>
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
                if(isset($_POST['shiftID']))
                {
                    $_SESSION['shift_id']=$_POST['shiftID'];
                }
                else
                {
                    $_SESSION['shift_id']=1;
                }

                if(isset($_POST['roleID']))
                {
                    $_SESSION['role_id']= $_POST['roleID'];
                }
                else
                {
                    $_SESSION['role_id']=1;
                }
                $user_id = $_SESSION['user_id'];
                $user = new User($user_id);
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
                            Role:
                            <select id="role" name="addu_role">
                                <option value="2">Nurse</option>
                                <option value="3">NotNurse</option>
                            </select>
                        <p>Avatar:</p>
                        <img id="userAvatarShow" src="style/img/user.png" alt="your image" />
                            <input accept="image/*" name="addu_userAvatar" type='file' id="addu_userAvatar" />
                            
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
                                <input type="file" style="display:none;" name="maleDefault" value="/var/www/test/public_html/app/style/img/user.png"/>
                                <input type="file" style="display:none;" name="femaleDefault" value="/var/www/test/public_html/app/style/img/user2.png"/>
                            <script>
                                 $('#gender').change(function() {
                                    if($(this).val() === 'female' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/user2.png';
                                    }
                                    if($(this).val() === 'male' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/user.png';
                                    }
                                });

                            </script>
                            Temporary password:
                            <input type="password" name="addu_password"/>
                            
                            <input type="submit" value="Add user" class="button1"/>
            </form>
        </div>
        <?php
        if
        (
            isset($_POST['addu_name']) &&
            isset($_POST['addu_surname']) && 
            isset($_POST['addu_login']) && 
            isset($_POST['addu_email']) && 
            isset($_POST['addu_custom_id']) && 
            isset($_POST['addu_password']) && 
            isset($_POST['addu_role']) && 
            isset($_POST['addu_fulltime']) 
        )
        {
            if(isset($_POST['addu_userAvatar']))
            {
                $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $_POST['addu_userAvatar'], $_POST['addu_custom_id'], $_POST['addu_fulltime']);
            }
            else if(isset($_POST['gender']))
            {
                if($_POST['gender'] == "male")
                {
                    //trzeba pomyśleć czemu nie działa dodawanie s
                $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $_POST['maleDefault'], $_POST['addu_custom_id'], $_POST['addu_fulltime']);

                }
                else
                {
                    $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $_POST['femaleDefault'], $_POST['addu_custom_id'], $_POST['addu_fulltime']);
                }

            }
        }
        ?>

        <div class="calendarMode" id="calendarMode">
            <script src="scripts/calendarModeForm.js"></script>
            <?php
            //później trzeba zmienić na konkretnego użytkownika
                $mth = new Month(10,2022);
                echo "<h2>".$mth->getName()." ".$mth->getYear()."</h2>";
                $mth->drawMonth();
                
            ?>
            <!-- Dodatkowe style zawierające wygląd formularzy i niektóre elementy CalendarMode -->
            <link rel="stylesheet" type="text/css" href="style/calendarModeAdditionalStyles.css"/>
            <!-- Przycisk który generuje wydruk -->
            <div class="button1 printCalendar">Print</div>
        </div>
       
    </div>
    <!-- SECTION __________________________RIGHT STATS PANEL -->
    <div class="rightPanel">
        <form mothod="post">
            <select name="roleID">
            <?php
                $conn1 = new ConnectToDatabase;
                $mysqliAdm1 = $conn1 -> connAdminPass();
                $sqlSelectAllRoles1 = "SELECT * FROM roles;";
                $res1 = $mysqliAdm1->query($sqlSelectAllRoles1);
                
                
                while($row1 = $res1->fetch_assoc())
                {
                    echo '<option onclick="this.form.submit();" value='.$row1['id'].">".$row1['name'].'</option>';
                }
                
                $res1->free();
                unset($conn1);
                
            ?>
            </select>
        </form>

        <form method="post">
            <select name="shiftID">
            <?php
                $conn1 = new ConnectToDatabase;
                $mysqliAdm1 = $conn1 -> connAdminPass();
                $sqlSelectAllRoles1 = "SELECT * FROM shifts;";
                $res1 = $mysqliAdm1->query($sqlSelectAllRoles1);
                

                while($row1 = $res1->fetch_assoc())
                {
                    echo '<option onclick="this.form.submit();" value='.$row1['id'].'>'.$row1['startHour'].'-'.$row1['endHour'].'<i> ('.$row1['name'].')</i></option>';
                }
                $res1->free();
                unset($conn1);
            ?>
            </select>
        </form>
        </div>
</body>
</html>