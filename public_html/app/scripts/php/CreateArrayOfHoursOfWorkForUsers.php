<?php
namespace App;
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
                    $how = new HoursOfWork($user, $monthNumber, $yearNumber, $row['hours_per_shift']);
                }
                else
                {
                    $foundMonth = false;
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
        }
        else
        {
            $arrayOfHoursOfWorkForCurrentMonth = json_decode($_SESSION['arrayOfHoursOfWorkForCurrentMonth'],0);
            $finalHoursOfWork = array();
            foreach($arrayOfHoursOfWorkForCurrentMonth as $currentHoursOfWorkAsStdClass)
            {
                    $user = new User($currentHoursOfWorkAsStdClass->User->user_id);
                    $how = new HoursOfWork($user, $monthNumber, $yearNumber, $user->hours_per_shift);
                    $how->ActualizeTimeAndHours($currentHoursOfWorkAsStdClass->Hours);
                    array_push($finalHoursOfWork, $how);
            }
            $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($finalHoursOfWork,0);
        }

?>