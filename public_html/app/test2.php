<?php

use App\Calendar;
use App\User;

include "ConnectToDatabase.php";
include "Calendar.php";
include "Day.php";
include "Shift.php";
include "User.php";


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$u = new User(1);
$mth = new Calendar(1, 2022, 1);

echo "Hello";
$s = json_encode($mth);
echo $s;



?>
