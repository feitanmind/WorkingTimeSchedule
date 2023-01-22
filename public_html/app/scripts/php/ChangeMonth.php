<?php
namespace App;
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

                $arrOfHours = array();

                $dep_id = $_SESSION['Current_User_Department_Id'];
                $role_id = $_SESSION['Role_Id'];
                $accessConnection = ConnectToDatabase::connUserPass();
                $sql = "SELECT usr_id, hours_of_work FROM user_data WHERE dep_id = $dep_id";
                $result = $accessConnection->query($sql);
                if($result->num_rows > 0)
                {
                    $utrue = 0;
                    while($row = $result->fetch_assoc())
                    {
                        $sethow = false;
                        $user = new User($row['usr_id']);
                        if(!empty($row['hours_of_work']))
                        {

                            $encodedUserHoursOfWork = $row['hours_of_work'];
                            $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork,0);
                            //print_r($decodedUserHoursOfWorkAsArrayOfStdClass);
                            foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
                            {
                                if($uh->month == $nextMonthNumber && $uh->year == $nextYearNumber){
                                    $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
                                    $how->ActualizeTimeAndHours($uh->hours);
                                    $sethow = true;
                                    break;
                                }
                            }



                        } else {
                            $how = new HoursOfWork($user, $nextMonthNumber, $nextYearNumber, $user->hours_per_shift);
                        }


                        if($sethow == false)
                        {
                            $how = new HoursOfWork($user, $nextMonthNumber, $nextYearNumber, $user->hours_per_shift);

                        }
                        else
                        {
                            $sethow = false;
                        }
                        array_push($arrOfHours, $how);
                    }
                }
                else
                {
                    $arrOfHours = array();
                }
                $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($arrOfHours);
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
                                $arrOfHours = array();
                                $dep_id = $_SESSION['Current_User_Department_Id'];
                                $role_id = $_SESSION['Role_Id'];
                                $accessConnection = ConnectToDatabase::connUserPass();
                                $sql = "SELECT usr_id, hours_of_work FROM user_data WHERE dep_id = $dep_id";
                                $result = $accessConnection->query($sql);
                                if($result->num_rows > 0)
                                {
                                    while($row = $result->fetch_assoc())
                                    {
                                        $sethow = false;
                                        $user = new User($row['usr_id']);
                        $f = $row['usr_id'];

                                        if(!empty($row['hours_of_work']))
                                        {

                                            $encodedUserHoursOfWork = $row['hours_of_work'];
                                            $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork,0);
                                            //print_r($decodedUserHoursOfWorkAsArrayOfStdClass);
                                            foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
                                            {
                                                if($uh->month == $previousMonthNumber && $uh->year == $previousYearNumber){
                                                    $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
                                                    $sethow = true;
                                                    $how->ActualizeTimeAndHours($uh->hours);
                                                    break;
                                                }

                                            }
                                            $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);

                                        } else {
                                            $how = new HoursOfWork($user, $previousMonthNumber, $previousYearNumber, $user->hours_per_shift);

                                        }

                                        if($sethow == false)
                                        {
                                            $how = new HoursOfWork($user, $previousMonthNumber, $previousYearNumber, $user->hours_per_shift);

                                        }
                                        else
                                        {
                                            $sethow = false;
                                        }


                                        array_push($arrOfHours, $how);

                                    }

                                }
                                else
                                {
                                    $arrOfHours = array();
                                }

                                $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($arrOfHours);
                                $cal = Calendar::CreateWorkingCalendar($dep_id, $role_id, $previousMonthNumber, $previousYearNumber);
                                $_SESSION['calendar'] = json_encode($cal);
                $_SESSION['Month_Number'] = $previousMonthNumber;
                $_SESSION['Year_Number'] = $previousYearNumber;
                $_GET["CHANGE_MONTH"] = null;
            }
            $_SESSION['Module'] = 1;
            header("Location: \app");
        }



?>