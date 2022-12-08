<?php
namespace App;
    require_once("User.php");
    if(isset($_GET['usersToAdd']) && isset($_GET['dayId']))
    {
        $dayId = $_GET['dayId'];
        $users = $_GET['usersToAdd'];
    echo intval($_SESSION['dep_id']);
        $cal = Calendar::JsonDecodeCalendar($_SESSION['calendar'],$_SESSION['dep_id']);
        
            foreach($users as $user)
            {
                $user2 = new User($user);
                $cal->SignUserToWorkInDay($user2, 0, 0);
            }
            $_SESSION['calendar'] = $cal->JsonEncodeCalendar();
        
        

    }



?>