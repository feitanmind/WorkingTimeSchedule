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
                    echo '<script>Notification.createAndDisplayWarningAboutCantSignUserOnDay();</script>';
                        $_SESSION['calendar'] = json_encode($calend2);
                    
                    }
                    else
                    {
                        $calend2->SignUserToWorkInDay($user2, $dayId, $shiftId);
                        // $c = array();
                        // $a = json_decode($_SESSION['arrOfHours'],0);
                        // foreach ($a as $b) {
                        //     $user3 = new User($b->User->user_id);
                        //     if ($b->Month == $month_Number && $b->Year == $year)
                        //     {
                        //         $how = new HoursOfWork($user3, $b->Month, $b->Year, $user3->hours_per_shift);
                            
                        //         $how->ActualizeTimeAndHours($b->Hours);
                        //         if($user3->user_id == $user2->user_id)
                        //         {
                        //             $how->SubstractTimeOfWork();
                        //         }
                        //     }
                        //     array_push($c, $how);
                        // }

                        
                        // $_SESSION['arrOfHours'] = json_encode($c);
                        $_SESSION['calendar'] = json_encode($calend2);
                        //czyszczenie Get
                        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                        header("Location: $actual_link");
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
        $monthNumber = $_SESSION['Month_Number'];
        $yearNumber = $_SESSION['Year_Number'];
        $dep_id = $_SESSION['Current_User_Department_Id'];

        if(!isset($_SESSION['arrayOfHoursOfWorkForCurrentMonth']))
        {
        
            $accessConnection = ConnectToDatabase::connAdminPass();
            $sql = "SELECT usr_id, hours_of_work, hours_per_shift FROM user_data WHERE dep_id = $dep_id;";
            $result = $accessConnection->query($sql);
            $finalHoursOfWork = array();
            while($row = $result->fetch_assoc())
            {
                $user = new User($row['usr_id']);
                $arrayOfHoursOfWork = json_decode($row['hours_of_work'],0);
                if(empty($arrayOfHoursOfWork))
                {
                    //Trzeba stworzyć nowe godziny
                    $how = new HoursOfWork($user, $monthNumber, $yearNumber, $row['hours_per_shift']);
                }
                else
                {
                    $foundMonth = false;
                    //Trzeba zanaleźć czy jest bieżący miesiąc
                    foreach($arrayOfHoursOfWork as $hoursOfWorkForMonth)
                    {
                        if($hoursOfWorkForMonth->month == $monthNumber && $hoursOfWorkForMonth->year == $yearNumber)
                        {
                            $how = new HoursOfWork($user, $monthNumber, $yearNumber, $row['hours_per_shift']);
                            $how->ActualizeTimeAndHours($hoursOfWorkForMonth->hours);
                            $foundMonth = true;
                        }
                    }
                    if(!$foundMonth)
                    {
                        $how = new HoursOfWork($user, $monthNumber, $yearNumber, $row['hours_per_shift']);
                    }
                }
                array_push($finalHoursOfWork, $how);
            }
            $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($finalHoursOfWork,0);
            //print_r($finalHoursOfWork);
        }
        else
        {
            //print_r($_SESSION['arrayOfHoursOfWorkForCurrentMonth']);
            $arrayOfHoursOfWorkForCurrentMonth = json_decode($_SESSION['arrayOfHoursOfWorkForCurrentMonth'],0);
            //print_r($arrayOfHoursOfWorkForCurrentMonth);
            $finalHoursOfWork = array();
            foreach($arrayOfHoursOfWorkForCurrentMonth as $currentHoursOfWorkAsStdClass)
            {
                //print_r($currentHoursOfWorkAsStdClass);
        
                    $user = new User($currentHoursOfWorkAsStdClass->User->user_id);
                    $how = new HoursOfWork($user, $monthNumber, $yearNumber, $user->hours_per_shift);
                    $how->ActualizeTimeAndHours($currentHoursOfWorkAsStdClass->Hours);
                    array_push($finalHoursOfWork, $how);

               
            }
           // print_r(json_encode($finalHoursOfWork, 0));
            $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($finalHoursOfWork,0);
            //print_r(json_encode($finalHoursOfWork,0));
        }


        





        // if(!isset($_SESSION['arrOfHours']))
        // {
        //     $arrOfHours = array();
           
           
        //     $accessConnection = ConnectToDatabase::connAdminPass();
        //     $sql = "SELECT usr_id, hours_of_work FROM user_data WHERE dep_id = $dep_id";
        //     $result = $accessConnection->query($sql);
        //     if($result->num_rows > 0)
        //     {
        //         while($row = $result->fetch_assoc())
        //         {
        //             $user = new User($row['usr_id']);
        //             if(!empty($row['hours_of_work']))
        //             {
                    
        //                 $encodedUserHoursOfWork = $row['hours_of_work'];
        //                 $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork,0);
        //                 //print_r($decodedUserHoursOfWorkAsArrayOfStdClass);
        //                 foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
        //                 {
        //                     if($uh->month == $monthNumber && $uh->year == $yearNumber){
        //                         $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
        //                         $how->ActualizeTimeAndHours($uh->hours);
        //                         break;
        //                     }  
        //                 }
        //                 if(!isset($how))
        //                 {
        //                     $how = new HoursOfWork($user, $monthNumber, $yearNumber, $user->hours_per_shift);

        //                 }
        //             } else {
        //                 $how = new HoursOfWork($user, $monthNumber, $yearNumber, $user->hours_per_shift);
        //             }
                    
        //             array_push($arrOfHours, $how);      
        //         }
        //     }
        //     $_SESSION['arrOfHours'] = json_encode($arrOfHours);
        //     echo json_encode($arrOfHours);
        //     return $arrOfHours;   
        // } else {
        //     $c = array();
        //     $a = json_decode($_SESSION['arrOfHours'], 0);
        //     if (!empty($a)) {
        //         foreach ($a as $b) {
        //             $user = new User($b->User->user_id);
        //             if ($b->Month == $monthNumber && $b->Year == $yearNumber) {
        //                 $how = new HoursOfWork($user, $b->Month, $b->Year, $user->hours_per_shift);
        //                 $how->ActualizeTimeAndHours($b->Hours);

        //             }
        //             array_push($c, $how);
        //             // print_r($b);
        //             // print_r('<br>');
        //         }
        //         $_SESSION['arrOfHours'] = json_encode($c);
        //         //print_r(json_encode($c));
        //         return $c;
        //     } else {
        //         $currentShift = $_SESSION['Shift_Id'];
        //         $accessConnection = ConnectToDatabase::connAdminPass();
        //         $sql = "SELECT usr_id FROM users WHERE dep_id = $dep_id;";
        //         $result = $accessConnection->query($sql);
        //         if ($result->num_rows > 0) {
        //             while ($row = $result->fetch_assoc()) {
        //                 $user = new User($row['usr_id']);
        //                 if (!empty($row['hours_of_work'])) {

        //                     $encodedUserHoursOfWork = $row['hours_of_work'];
        //                     $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork, 0);
        //                     //print_r($decodedUserHoursOfWorkAsArrayOfStdClass);
        //                     foreach ($decodedUserHoursOfWorkAsArrayOfStdClass as $uh) {
        //                         if ($uh->month == $monthNumber && $uh->year == $yearNumber) {
        //                             $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
        //                             $how->ActualizeTimeAndHours($uh->hours);
        //                             break;
        //                         }
        //                     }
        //                 } else {
        //                     $how = new HoursOfWork($user, $monthNumber, $yearNumber, $user->hours_per_shift);
        //                 }

        //                 array_push($arrOfHours, $how);
        //             }
        //         }
        //         $_SESSION['arrOfHours'] = json_encode($arrOfHours);

        //     }

        // }
           
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
            $userCanHaveVacation = true;
            foreach($users as $user)
            {
                $user2 = new User($user);
                if($calend2->canVacationBeGrantToUserOnDay($user2,$dayId))
                {
                    $calend2->SignUserVacation($user2, $dayId, $shiftId);
                    // $c = array();
                    //     $a = json_decode($_SESSION['arrOfHours'],0);
                    //     foreach ($a as $b) {
                    //         $user3 = new User($b->User->user_id);
                    //         if ($b->Month == $month_Number && $b->Year == $year)
                    //         {
                    //             $how = new HoursOfWork($user3, $b->Month, $b->Year, $user3->hours_per_shift);
                            
                    //             $how->ActualizeTimeAndHours($b->Hours);
                    //             if($user3->user_id == $user2->user_id)
                    //             {
                    //                 $how->SubstractTimeOfWork();
                    //             }
                    //         }
                    //         array_push($c, $how);
                    //     }

                        
                    //     $_SESSION['arrOfHours'] = json_encode($c);
                }
                else
                {
                    echo '<script src="/../app/scripts/warningForUser.js"></script>';
                    echo "<script>Notification.createAndDisplayWarningAboutCantGrantVacationToUserOnThisDay();</script>";
                    $userCanHaveVacation = false;
                }
                

            }
            if($userCanHaveVacation == true)
            {
                $_SESSION['calendar'] = json_encode($calend2);
                //czyszczenie Get
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                header("Location: $actual_link");
            }

        
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
               // echo "<script>console.log(\"".$user2->user_id."\")</script>";
            //    $c = array();
            //    $a = json_decode($_SESSION['arrOfHours'],0);
            //    foreach ($a as $b) {
            //        $user3 = new User($b->User->user_id);
            //        if ($b->Month == $month_Number && $b->Year == $year)
            //        {
            //            $how = new HoursOfWork($user3, $b->Month, $b->Year, $user3->hours_per_shift);
                   
            //            $how->ActualizeTimeAndHours($b->Hours);
            //            if($user3->user_id == $user2->user_id)
            //            {
            //                $how->AddTimeOfWork();
            //            }
            //        }
            //        array_push($c, $how);
            //    }
   
               
            //    $_SESSION['arrOfHours'] = json_encode($c);
   
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
                //echo "<script>console.log(\"".$user2->user_id."\")</script>";
            //     $c = array();
            // $a = json_decode($_SESSION['arrOfHours'],0);
            // foreach ($a as $b) {
            //     $user3 = new User($b->User->user_id);
            //     if ($b->Month == $month_Number && $b->Year == $year)
            //     {
            //         $how = new HoursOfWork($user3, $b->Month, $b->Year, $user3->hours_per_shift);
                
            //         $how->ActualizeTimeAndHours($b->Hours);
            //         if($user3->user_id == $user2->user_id)
            //         {
            //             $how->AddTimeOfWork();
            //         }
            //     }
            //     array_push($c, $how);
            // }

            
            // $_SESSION['arrOfHours'] = json_encode($c);





                $calend2->UnsignVacationUserFormDay($user2, $dayId, $shiftId);

            }
            $_SESSION['calendar'] = json_encode($calend2);
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header("Location: $actual_link");
        }
    }

    public static function SAVE_IN_DATABASE()
    {
        if(isset($_POST["CALENDAR_SAVE"]))
        {
            if($_POST["CALENDAR_SAVE"] == "YES")
            {
                $departmentIdentifier = $_SESSION['Current_User_Department_Id'];
                $roleIdentifier = $_SESSION['Role_Id'];
                $monthNumber = $_SESSION['Month_Number'];
                $yearNumber = $_SESSION['Year_Number'];
                $encodedCalendar = json_decode($_SESSION['calendar']);
                $decodedCalendar = Calendar::DecodeJsonCalendar($monthNumber,$yearNumber,$departmentIdentifier,$encodedCalendar);
                $decodedCalendar->PushCalendarToDataBase($roleIdentifier);
                HoursOfWork::PushHoursOfWorkArrayIntoDatabase();
                $_POST["CALENDAR_SAVE"] = "none";
            }
            

        }
        
    }

    public static function CHANGE_MONTH()
    {
        if(isset($_GET["CHANGE_MONTH"]))
        {
            unset($_SESSION['arrayOfHoursOfWorkForCurrentMonth']);
            PHPScripts::CreateArrayOfHoursOfWorkForUsers();
            if($_GET["CHANGE_MONTH"] == "NEXT")
            {
                $currentMonthNumber = $_SESSION['Month_Number'];
                $currentYearNumber = $_SESSION['Year_Number'];
                $firstDayOfCurrentMonth = "$currentYearNumber-$currentMonthNumber-1";
                $nextMonthNumber = date("m", strtotime("+1 month", strtotime($firstDayOfCurrentMonth)));
                $nextYearNumber = date("Y", strtotime('+1 month', strtotime($firstDayOfCurrentMonth)));
                //echo $nextMonthNumber;
                //echo $nextYearNumber;

                //1 - arr of hours
                $arrOfHours = array();
           
                $dep_id = $_SESSION['Current_User_Department_Id'];
                $role_id = $_SESSION['Role_Id'];
                $accessConnection = ConnectToDatabase::connAdminPass();

                
                //echo json_encode($arrOfHours);
                //1 - arr of hours
                $cal = Calendar::CreateWorkingCalendar($dep_id, $role_id, $nextMonthNumber, $nextYearNumber);
              
                $_SESSION['calendar'] = json_encode($cal);
                $_SESSION['Month_Number'] = $nextMonthNumber;
                $_SESSION['Year_Number'] = $nextYearNumber;
                $_GET["CHANGE_MONTH"] = null;
            }
            if($_GET["CHANGE_MONTH"] == "BACK")
            {
                $currentMonthNumber = $_SESSION['Month_Number'];
                $currentYearNumber = $_SESSION['Year_Number'];
                $firstDayOfCurrentMonth = "$currentYearNumber-$currentMonthNumber-1";
                $previousMonthNumber = date("m", strtotime("-1 month", strtotime($firstDayOfCurrentMonth)));
                $previousYearNumber = date("Y", strtotime('-1 month', strtotime($firstDayOfCurrentMonth)));
                //echo $previousMonthNumber;
                //echo $previousYearNumber;

                                //1 - arr of hours
                                $arrOfHours = array();
           
                                $dep_id = $_SESSION['Current_User_Department_Id'];
                                $role_id = $_SESSION['Role_Id'];
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
                                            $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork,0);
                                            //print_r($decodedUserHoursOfWorkAsArrayOfStdClass);
                                            foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
                                            {
                                                if($uh->month == $previousMonthNumber && $uh->year == $previousYearNumber){
                                                    $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
                                                    $how->ActualizeTimeAndHours($uh->hours);
                                                    break;
                                                }  
                                            }
                                            
                                        } else {
                                            $how = new HoursOfWork($user, $previousMonthNumber, $previousYearNumber, $user->hours_per_shift);
                                        }
                                        if(isset($how))
                                        {
                                            array_push($arrOfHours, $how); 
                                        }
                                        else
                                        {
                                            $how = new HoursOfWork($user, $previousMonthNumber, $previousYearNumber, $user->hours_per_shift);
                                            array_push($arrOfHours, $how);
                                            unset($how);
                                        }
                                          
                                    }
                                
                                }
                                else
                                {
                                    $arrOfHours = array();
                                }
                
                                $_SESSION['arrOfHours'] = json_encode($arrOfHours);
                                //echo json_encode($arrOfHours);
                                //1 - arr of hours
                                $cal = Calendar::CreateWorkingCalendar($dep_id, $role_id, $previousMonthNumber, $previousYearNumber);
                              
                                $_SESSION['calendar'] = json_encode($cal);






                $_SESSION['Month_Number'] = $previousMonthNumber;
                $_SESSION['Year_Number'] = $previousYearNumber;
                $_GET["CHANGE_MONTH"] = null;
                
            }
            header("Location: \app");
        }
    }





}

       
?>