<?php
namespace App;
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
                        $how->AddTimeOfWork();
                    }
                }
                array_push($c, $how);
            }
            $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($c);
                $calend2->UnsignVacationUserFormDay($user2, $dayId, $shiftId);

            }
            $_SESSION['calendar'] = json_encode($calend2);
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header("Location: $actual_link");
        }

?>