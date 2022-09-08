<?php namespace App;session_start();
session_start();
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/style.css"/>
    <title>Working time schedule</title>
</head>
<body>
    <?php
        if(isset($_SESSION['user_id']) && isset($_SESSION['log']))
        {
            if($_SESSION['log'] == true)
            {
            $user_id = $_SESSION['user_id'];
            require "/var/www/test/public_html/app/User.php";
            $user = new User($user_id);
            // echo "Witaj ". $_SESSION['username'] . "<br>";
            echo $user->getUserData();
            }
            else
            {
                header("Location: ../");
            }
            
        }
        else
        {
            header("Location: ../");
        }
        
    ?>
<a href="Logout.php">Logout</a>
</body>
</html>