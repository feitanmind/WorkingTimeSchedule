<?php

use App\ConnectToDatabase;
use App\Shift;
use App\User;
use App\Calendar;
use App\HoursOfWork;
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
        if (isset($_POST['shiftID'])) {
            $_SESSION['Shift_Id'] = $_POST['shiftID'];
        }
        if (isset($_POST['roleID'])) {

            $_SESSION['Role_Id'] = $_POST['roleID'];
        }
    }

    public static function ADD_USER_TO_Day_of_Calendar()
    {
        
    if(isset($_GET['usersToAdd']) && isset($_GET['dayId']))
    {
        $dayId = $_GET['dayId'];
        $users = $_GET['usersToAdd'];
        $shiftId = $_SESSION['Shift_Id'];
        $month_Number = $_SESSION['Month_Number'];
        $year = $_SESSION['Year_Number'];
        $department_ID = 1;

        $calend = json_decode($_SESSION['calendar']);

            $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);
            // $_de = $calend2;
            // $debug = $_de->MonthNumber;
            // $debug2 = $_de->Year;
            // echo "<script>console.log('AddU : month:$debug, year: $debug2')</script>";
 
            foreach($users as $user)
            {
                $user2 = new User($user);
                    $canAdd = $calend2->CanUserBeSignOnDay($user2, $dayId, $shiftId);
                    if(!$canAdd)
                    {
                        echo '<script src="/../app/scripts/warningForUser.js"></script>';
                        
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
    public static function CreateArrayOfHoursOfWorkForUsers()
    {
        
        if(!isset($_SESSION['arrOfHours']))
        {
            $arrOfHours = array();
            $dep_id = $_SESSION['Current_User_Department_Id'];
        $accessConnection = ConnectToDatabase::connAdminPass();
        $sql = "SELECT usr_id, hours_of_work FROM user_data WHERE dep_id = $dep_id";
        $result = $accessConnection->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $user = new User($row['usr_id']);
                if(!empty($row['hours_of_work']))
                {
                    
                    $encodedUserHoursOfWork = $row['hours_of_work'];
                    $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork);
                    //print_r($decodedUserHoursOfWorkAsArrayOfStdClass);
                    foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
                    {
                        if($uh->month == 1 && $uh->year == 2022){
                            $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
                            $how->ActualizeTimeAndHours($uh->hours);
                            break;
                        }  
                    }
                }
                else
                {
                    $how = new HoursOfWork($user, 1, 2022, $user->hours_per_shift);
                }
                    
                array_push($arrOfHours, $user);            
            }
        }
        $_SESSION['arrOfHours'] = $arrOfHours;
        return $arrOfHours;   
        }
        else
        {
            return $_SESSION['arrOfHours'];
        }
         
        
           
    }
    public static function GRANT_USER_Vacation_In_Day_of_Calendar()
    {
        
    if(isset($_GET['usersToVacation']) && isset($_GET['dayId']))
    {
        $dayId = $_GET['dayId'];
        $users = $_GET['usersToVacation'];
        $shiftId = $_SESSION['Shift_Id'];
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
            $shiftId = $_SESSION['Shift_Id'];
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
            $shiftId = $_SESSION['Shift_Id'];
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