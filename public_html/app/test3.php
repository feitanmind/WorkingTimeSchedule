<?php namespace App; ?>
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
    include("ConnectToDatabase.php");
    include("User.php");
    include("Shift.php");
    include("Day.php");
    include("Calendar.php");
    include("HoursOfWork.php");
    // Warning: Undefined property: App\User::$Name in /var/www/html/wokingTimeSchedule/app/b_Month.php on line 70

    $md = new Calendar(1,2022,1);

    //echo "jsonenc1: ".json_encode($md)."<br>";
    //echo "Done";
    $md->DrawCalendar();
    //adding user
    $user2 = new User(1);
    $user = new User(2);
    // $arr = (array) $md;
    // $t = (array)$arr['Days'][0];
    // $t2 = (array)$t['Shifts'][0];
    // $t3 = (array) $t2['EmployeesWorking'];
    // array_push($t3, $user);

    array_push($md->Days[0]->Shifts[0]->EmployeesWorking, $user);
print_r(json_encode($md));
    //echo "jsonenc1: ".json_encode($md)."<br>";
    //$md->SignUserToWorkInDay($user2, 0, 0);
    //$md->SignUserVacation($user, 0, 0);
    //array_push($md->Days[0]->Shifts[0]->EmployeesVacation, $user2);
    //echo "hh";
    $md->RemoveMonth();
    $md->DrawCalendar();

    $tyyy = true;
    
    //removing user 
    //$keyToDelete = array_search($user2,$md->Days[0]->Shifts[0]->EmployeesVacation);
    //array_splice($md->Days[0]->Shifts[0]->EmployeesWorking,$keyToDelete);


    $how = new HoursOfWork($user,5,2022,'08:00:00');


    //substractiong hours
    $how->SubstractTimeOfWork(8);
    //echo "Hours - $how->Hours";
    $md->Department = 1;

    $dep_id = 1;
    $role_id = 1;
    //$mdd =  $md->JsonEncodeCalendar();
    //echo $mdd;
    //alendar::JsonDecodeCalendar($mdd,1);
    ////PushCalendar 
    $md->PushCalendarToDataBase(1,$md);
    //$cal2 = Calendar::CreateWorkingCalendar($dep_id, $role_id, 1, 2022);
    //$md->RemoveMonth();
    //$cal2->DrawCalendar();
    
    //$cal3 = Calendar::CreateWorkingCalendar($dep_id, $role_id, 2, 2022);
    //$cal3->DrawCalendar();
    ?>
</body>
</html>