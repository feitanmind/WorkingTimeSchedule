<?php
namespace App;
$currentMonthNumber = $_SESSION['Month_Number'];
$currentYearNumber = $_SESSION['Year_Number'];
$firstDayOfCurrentMonth = "$currentYearNumber-$currentMonthNumber-1";
$currentMonthNumber = date("m", strtotime($firstDayOfCurrentMonth));
$currentYearNumber  = date("Y", strtotime($firstDayOfCurrentMonth));
//echo $currentMonthNumber;
//echo $currentYearNumber ;

$arrOfHours = array();

$dep_id = $_SESSION['Current_User_Department_Id'];
$role_id = $_SESSION['Role_Id'];
$accessConnection = ConnectToDatabase::connAdminPass();
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
                if($uh->month == $currentMonthNumber && $uh->year == $currentYearNumber ){
                    $how = new HoursOfWork($user, $uh->month, $uh->year, $user->hours_per_shift);
                    $how->ActualizeTimeAndHours($uh->hours);
                    $sethow = true;
                    break;
                }
            }



        } else {
            $how = new HoursOfWork($user, $currentMonthNumber, $currentYearNumber , $user->hours_per_shift);
        }


        if($sethow == false)
        {
            $how = new HoursOfWork($user, $currentMonthNumber, $currentYearNumber , $user->hours_per_shift);

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
$cal = Calendar::CreateWorkingCalendar($dep_id, $role_id, $currentMonthNumber, $currentYearNumber );

?>