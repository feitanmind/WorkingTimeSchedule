<?php
namespace App;
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
            $ifHoursOfWorkLeft = HoursOfWork::IfUserHaveHoursToSign($user2, $department_ID);
            if(!$ifHoursOfWorkLeft)
            {
                
                echo "<script>Notification.createAndDisplayWarningAboutNoHoursLeft();</script>";
                $userCanHaveVacation = false;
            }
            else
            {
                if($calend2->canVacationBeGrantToUserOnDay($user2,$dayId))
            {
                $calend2->SignUserVacation($user2, $dayId, $shiftId);
                $_SESSION['IsCalendarSave'] = 'no';
                $c = array();
                    $a = json_decode($_SESSION['arrayOfHoursOfWorkForCurrentMonth'],0);
                    foreach ($a as $b) {
                        $user3 = new User($b->User->user_id);
                        if ($b->Month == $month_Number && $b->Year == $year)
                        {
                            $how = new HoursOfWork($user3, $b->Month, $b->Year, $user3->hours_per_shift);

                            $how->ActualizeTimeAndHours($b->Hours);
                            if($user3->user_id == $user2->user_id)
                            {
                                $how->SubstractTimeOfWork($user3);
                            }
                        }
                        array_push($c, $how);
                    }


                    $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($c);
            }
            else
            {
                
                echo "<script>Notification.createAndDisplayWarningAboutCantGrantVacationToUserOnThisDay();</script>";
                $userCanHaveVacation = false;
            }
            }



        }
        if($userCanHaveVacation == true)
        {
            $_SESSION['calendar'] = json_encode($calend2);
            //czyszczenie Get
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $_SESSION['Module'] = 1;
            header("Location: $actual_link");
        }


}


?>