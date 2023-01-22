<?php
namespace App;
if(isset($_GET['usersToAdd']) && isset($_GET['dayId']))
{
    
    $dayId = $_GET['dayId'];
    $users = $_GET['usersToAdd'];
    $month_Number = $_SESSION['Month_Number'];
    $year = $_SESSION['Year_Number'];
    $department_ID = $_SESSION['Current_User_Department_Id'];
    $shiftId = $_SESSION['Shift_Id'];
        if ($shiftId == 'all'){
            $_SESSION['shiftIsAll'] = true;
            echo '<div id="selectShiftForAddingUser">';
            Shift::GenerateFormSelectForShifts($department_ID);
            echo '</div>';
            echo "<script>Notification.askUserAboutShiftForSingningUser();</script>";
            
        }
    else
    {
        
        $calend = json_decode($_SESSION['calendar']);
        $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);

        
        foreach($users as $user)
        {
            $user2 = new User($user);
                $canAdd = $calend2->CanUserBeSignOnDay($user2, $dayId, $shiftId);
                $ifHoursOfWorkLeft = HoursOfWork::IfUserHaveHoursToSign($user2, $shiftId );
            //throw new Exception("dfas");$department_ID
                if(!$ifHoursOfWorkLeft)
                {
                    
                    $xmlFile = fopen("templatesNotification.xml", "r");
                    $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
                    echo "<script>";
                        echo 'window.history.pushState({}, document.title, "/" + "app/");';
                        echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Warning,SubjectNotification.NoHoursLeft);";
                    echo "</script>";
                        $_SESSION['calendar'] = json_encode($calend2);
                }
                else
                {
                    if(!$canAdd)
                    {
                        
                        $xmlFile = fopen("templatesNotification.xml", "r");
                        $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
                        echo "<script>";
                            echo 'window.history.pushState({}, document.title, "/" + "app/");';
                            echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Warning,SubjectNotification.RefuseSignUser);";
                        echo "</script>";

                        $_SESSION['calendar'] = json_encode($calend2);

                    }
                    else
                    {
                        $calend2->SignUserToWorkInDay($user2, $dayId, $shiftId);
                        $c = array();
                        $a = json_decode($_SESSION['arrayOfHoursOfWorkForCurrentMonth'],0);
                        foreach ($a as $b)
                        {
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
                        $_SESSION['calendar'] = json_encode($calend2);
                        if(isset($_SESSION['shiftIsAll']))
                        {
                            unset($_SESSION['shiftIsAll']);
                            $_SESSION['Shift_Id'] = 'all';
                        }
                        //czyszczenie Get
                        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                        $_SESSION['Module'] = 1;
                        $_SESSION['IsCalendarSave'] = 'no';
                        header("Location: $actual_link");
                    }

                }
        }
        
    }
    $_SESSION['Module'] = 1;
}




?>