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
    height: 80vh;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.dayOfTheWeek
{
    width: 15%;
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
    include("b_Month.php");
    include("HoursOfWork.php");
    // Warning: Undefined property: App\User::$Name in /var/www/html/wokingTimeSchedule/app/b_Month.php on line 70

    $md = new b_Month(1,2022,1);
    echo "Done";
    $md->DrawMonth();
    //adding user
    $user = new User(1);
    $user2 = new User(2);
    array_push($md->Days[0]->Shifts[0]->EmployeesWorking, $user);
    array_push($md->Days[0]->Shifts[0]->EmployeesWorking, $user2);
    echo "hh";
    //$md->DrawMonth();

    
    
    //removing user 
    $keyToDelete = array_search($user2,$md->Days[0]->Shifts[0]->EmployeesWorking);
    //array_splice($md->Days[0]->Shifts[0]->EmployeesWorking,$keyToDelete);


    $how = new HoursOfWork($user,5,2022,'08:00:00');


    //substractiong hours
    $how->SubstractTimeOfWork(8);
    echo "Hours - $how->Hours";
    $md->Department = 1;

    
    echo $md->JsonEncodeMonth();
    ?>
</body>
</html>