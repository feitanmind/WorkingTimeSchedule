<?php

use App\Shift;
use App\User;
use App\Calendar;
use Exception as Ex;
use PHPUnit\TextUI\CliArguments\Exception;

use function PHPUnit\Framework\throwException;
 
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
    public static function CheckIfUserCanBeSign($dayId,$users,$shiftId,$month_Number,$year,$department_ID,$calend2)
    {
        
    }
    public static function ADD_USER_TO_Day_of_Calendar()
    {
        
    if(isset($_GET['usersToAdd']) && isset($_GET['dayId']))
    {
        $dayId = $_GET['dayId'];
        $users = $_GET['usersToAdd'];
        $shiftId = $_SESSION['shift_id'];
        $month_Number = $_SESSION['Month_Number'];
        $year = $_SESSION['Year_Number'];
        $department_ID = 1;

        $calend = json_decode($_SESSION['calendar']);

            $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);
            $_de = $calend2;
            $debug = $_de->MonthNumber;
            $debug2 = $_de->Year;
            echo "<script>console.log('AddU : month:$debug, year: $debug2')</script>";
 
            foreach($users as $user)
            {
                $user2 = new User($user);
                    $canAdd = $calend2->CanUserBeSignOnDay($user2, $dayId, $shiftId);
                    if(!$canAdd)
                    {
                        echo "<script>
                        
                        
                       let toast = document.getElementById('toast');

                        
                        
                        toast.style.width = '200px';
                        toast.style.height = '200px';
                        toast.style.position = 'absolute';
                        toast.style.display = 'flex';
                        
                        </script>";
                        
                        $_SESSION['calendar'] = json_encode($calend2);
                    
                    }
                    else
                    {
                        $calend2->SignUserToWorkInDay($user2, $dayId, $shiftId);
                        $_SESSION['calendar'] = json_encode($calend2);
                        // //czyszczenie Get
                        // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                        // header("Location: $actual_link");
                    }
               
                

            }
        // $_SESSION['calendar'] = json_encode($calend2);
        // //czyszczenie Get
        // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        // header("Location: $actual_link");
        
    }


    }
    public static function GRANT_USER_Vacation_In_Day_of_Calendar()
    {
        
    if(isset($_GET['usersToVacation']) && isset($_GET['dayId']))
    {
        $dayId = $_GET['dayId'];
        $users = $_GET['usersToVacation'];
        $shiftId = $_SESSION['shift_id'];
        $month_Number = $_SESSION['Month_Number'];
        $year = $_SESSION['Year_Number'];
        $department_ID = 1;

        $calend = json_decode($_SESSION['calendar']);
        $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);

            foreach($users as $user)
            {
                $user2 = new User($user);
                $calend2->SignUserVacation($user2, $dayId, $shiftId);

            }
    $_SESSION['calendar'] = json_encode($calend2);
    //czyszczenie Get
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    header("Location: $actual_link");
        
    }


    }
    public static function REMOVE_USER_FROM_Day_Of_Calendar()
    {
        if(isset($_GET['userToRemove']) &&isset($_GET['dayId']))
        {
            $dayId = $_GET['dayId'];
            $users = $_GET['userToRemove'];
            $shiftId = $_SESSION['shift_id'];
            $month_Number = $_SESSION['Month_Number'];
            $year = $_SESSION['Year_Number'];
            $department_ID = 1;
            $calend = json_decode($_SESSION['calendar']);
            $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);
            foreach($users as $user)
            {
                //echo "<script>console.log(\"".$user->user_id."'\")</script>";
                $user2 = new User($user);
                echo "<script>console.log(\"".$user2->user_id."\")</script>";
                
                $calend2->UnsignWorkingUserFormDay($user2, $dayId, $shiftId);

            }
            $_SESSION['calendar'] = json_encode($calend2);
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header("Location: $actual_link");
        }
    }
    public static function REVOKE_VACATION_For_A_User()
    {
        if(isset($_GET['userToRevokeVacation']) &&isset($_GET['dayId']))
        {
            $dayId = $_GET['dayId'];
            $users = $_GET['userToRevokeVacation'];
            $shiftId = $_SESSION['shift_id'];
            $month_Number = $_SESSION['Month_Number'];
            $year = $_SESSION['Year_Number'];
            $department_ID = 1;
            $calend = json_decode($_SESSION['calendar']);
            $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);
            foreach($users as $user)
            {
                //echo "<script>console.log(\"".$user->user_id."'\")</script>";
                $user2 = new User($user);
                echo "<script>console.log(\"".$user2->user_id."\")</script>";
                
                $calend2->UnsignVacationUserFormDay($user2, $dayId, $shiftId);

            }
            $_SESSION['calendar'] = json_encode($calend2);
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header("Location: $actual_link");
        }
    }
}

       
?>