<?php

use App\Shift;
use App\User;
use App\Calendar;
class PHPScripts
{
    public static function CHECK_User_Is_Logged()
    {
        if(!isset($_SESSION['user_id']) && !isset($_SESSION['log'])) header("Location: ../");
        else if($_SESSION['log'] == false) header("Location: ../");
    }
    public static function CHECK_AND_SET_Session_Var_Shift_and_Role()
    {
        isset($_POST['shiftID']) ? $_SESSION['shift_id'] = $_POST['shiftID'] : $_SESSION['shift_id'] = 1;
        isset($_POST['roleID']) ? $_SESSION['role_id'] = $_POST['roleID'] : $_SESSION['role_id'] = 1;
    }
    public static function ADD_USER_TO_Day_of_Calendar()
    {
        
    if(isset($_GET['usersToAdd']) && isset($_GET['dayId']))
    {
        $dayId = $_GET['dayId'];
        $users = $_GET['usersToAdd'];
        $month_Number = 1;
        $year = 2022;
        $department_ID = 1;
    //echo "MASTER";
        //$cal = json_decode($_SESSION['calendar']);
        //
    // echo $_SESSION['calendar'];
        $calend = json_decode($_SESSION['calendar']);
            $calend2 = new Calendar($month_Number, $year, $department_ID);
            foreach ($calend as $key => $value) $calend2->{$key} = $value;

        //


            foreach($users as $user)
            {
                $user2 = new User($user);
                $calend2->SignUserToWorkInDay($user2, 0, 0);
            }
    $_SESSION['calendar'] = json_encode($calend2);
    //czyszczenie Get
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    header("Location: $actual_link");
        

    }

    }
}

       
?>