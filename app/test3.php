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
    width: 100%;
    height: 80vh;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.dayOfTheWeek
{
    width: 15%;
    height: 10vh;
    background-color: lightgrey;
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


    $md = new b_Month(1,2022,1);
    echo "Done";
    $md->DrawMonth();
    
    ?>
</body>
</html>