<?php namespace App;

use PHPScripts;

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
.mainCalendar
{
    width: 80%;
    height: 77vh;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.dayOfTheWeek
{
    width: 13%;
    height: 11vh;
    background-color: lightgrey;
    display: flex;
    flex-direction: column;
    gap:0;
}
.dayOfTheWeek p
{
    margin: 0;
    float: left;
    margin-right: 10%;
}
#addP
{
    font-size: 1vw;
    float: left;
    margin-right: 5%;

}
#remP
{
    font-size: 1vw;
    float: left;
}
</style>
<body>
    <?php
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require "scripts/php/PHPScripts.php";
    require "modules/GeneralClasses/ConnectToDatabase.php";
    require "modules/CalendarModeClasses/User.php";
    require "modules/CalendarModeClasses/Shift.php";
    require "modules/CalendarModeClasses/Role.php";
    require "modules/CalendarModeClasses/Calendar.php";
    require "modules/CalendarModeClasses/Day.php";
    require "modules/CalendarModeClasses/HoursOfWork.php";
    require "modules/CalendarModeClasses/Statistics.php";

  

   
    // Warning: Undefined property: App\User::$Name in /var/www/html/wokingTimeSchedule/app/b_Month.php on line 70

//     // $md = new Calendar(1,2022,1);
//     // $md2 = new Calendar(12, 2021, 1);
//      $md3 = new Calendar(2, 2022, 1);

//     // //echo "jsonenc1: ".json_encode($md)."<br>";
//     // //echo "Done";
//     // $md->DrawCalendar();
//     //adding user
//     $user2 = new User(1);
//     $user = new User(2);
//     // $arr = (array) $md;
//     // $t = (array)$arr['Days'][0];
//     // $t2 = (array)$t['Shifts'][0];
//     // $t3 = (array) $t2['EmployeesWorking'];
//     // array_push($t3, $user);

//     //array_push($md->Days[30]->Shifts[0]->EmployeesWorking, $user);
// //print_r(json_encode($md));
//     //echo "jsonenc1: ".json_encode($md)."<br>";
// //$md3->SignUserToWorkInDay($user2, 1, 1);
//     //$md->SignUserVacation($user, 0, 0);
//     //array_push($md->Days[0]->Shifts[0]->EmployeesVacation, $user2);
//     //echo "hh";
//    // $md->RemoveMonth();
//    // $md->DrawCalendar();

//    // $tyyy = true;
    
//     //removing user 
//     //$keyToDelete = array_search($user2,$md->Days[0]->Shifts[0]->EmployeesVacation);
//     //array_splice($md->Days[0]->Shifts[0]->EmployeesWorking,$keyToDelete);

//     $b = array();
//      $how = new HoursOfWork($user2,1,2022,'07:35:00');
//      $how2 = new HoursOfWork($user2,2,2022,'07:35:00');
//     array_push($b, $how);
//     array_push($b, $how2);
//     $_SESSION['arrOfHours'] = json_encode($b);
//     // //substractiong hours
//     // //$how->SubstractTimeOfWork(8);
//     // //$how->ActualizeTimeAndHours("133:05");

//     // $how2 = new HoursOfWork($user2, 2, 2022, '07:35:00');
//     $a = array();
// foreach($b as $k)
// {
//     array_push($a,array("month" => $k->Month, "year"=>$k->Year, "time"=>$k->Time, "hours"=>$k->Hours));
// }
//     $fi = json_encode($a);
//    // $arf2 = array("month" => $how2->Month, "year"=>$how2->Year, "time"=>$how2->Time, "hours"=>$how2->Hours);
//     // array_push($a, $arf);
//     // array_push($a, $arf2);
//     // $encodedUserHoursOfWork = json_encode($a);
//     // //echo $encodedUserHoursOfWork .'<br><br>';


//     // $decodedUserHoursOfWorkAsArrayOfStdClass = json_decode($encodedUserHoursOfWork);
//     // //print_r($encodedUserHoursOfWork);
//     // if(!empty($decodedUserHoursOfWorkAsArrayOfStdClass))
//     // {
//     //     foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
//     //     {
//     //         if($uh->month == 1 && $uh->year == 2022){
//     //             $how = new HoursOfWork($user2, $uh->month, $uh->year, $user2->hours_per_shift);
//     //             $how->ActualizeTimeAndHours($uh->hours);
                
//     //         }
            
//     //         echo '<br><br>';
//     //     }
//     // }
//     // else
//     // {
//     //     $how = new HoursOfWork($user2, 1, 2022, $user2->hours_per_shift);
//     // }

//     print_r($fi);
//    // HoursOfWork::PushHoursOfWorkArrayIntoDatabase();
//     // foreach($decodedUserHoursOfWorkAsArrayOfStdClass as $uh)
//     // {
//     //     if($uh->month == 1 && $uh->year == 2022){
//     //         $how = new HoursOfWork($user2, $uh->month, $uh->year, $user2->hours_per_shift);
//     //         $how->ActualizeTimeAndHours($uh->hours);
            
//     //     }
        
//     //     echo '<br><br>';
//     // }
//     //echo "working days" . $how->GetWorkingDaysInMonth(2022, 1) . "<br>";
//     //echo "Hours - $how->Hours";
//     //echo $f;
//     // $md->Department = 1;

//     // $dep_id = 1;
//     // $role_id = 1;
//     //$mdd =  $md->JsonEncodeCalendar();
//     //echo $mdd;
//     //alendar::JsonDecodeCalendar($mdd,1);
//     ////PushCalendar 
//     //$md3->PushCalendarToDataBase(1);
//     //$cal2 = Calendar::CreateWorkingCalendar($dep_id, $role_id, 1, 2022);
//     //$md->RemoveMonth();
//     //$cal2->DrawCalendar();
    
//     //$cal3 = Calendar::CreateWorkingCalendar($dep_id, $role_id, 2, 2022);
//     //$cal3->DrawCalendar();
$_SESSION['Month_Number'] = 3;
$_SESSION['Year_Number'] = 2022;
$_SESSION['Current_User_Department_Id'] = 1;
    PHPScripts::CreateArrayOfHoursOfWorkForUsers();

    $_SESSION['calendar'] = json_encode(new Calendar(1,2022,1));
    print_r($_SESSION['arrayOfHoursOfWorkForCurrentMonth']);
    $a = HoursOfWork::decodeArrayOfHoursOfWork($_SESSION['arrayOfHoursOfWorkForCurrentMonth']);
    echo "<br><br>";
    print_r($a);
    echo "<br><br>";
    $_SESSION['id_stat'] = 1;
    Statistics::DrawStatisticsChartForUser();

     ?>
</body>
</html>