<?php
use App\ConnectToDatabase;
use App\User;
use App\Shift;
use App\Role;
use App\Calendar;
use PhpParser\JsonDecoder;


?>
<script src="scripts/calendarModeForm.js"></script>
<?php

if($_SESSION['Shift_Id'] != 0)
{
    if(!isset($_SESSION['calendar'])) {
        $monthNumber = $_SESSION['Month_Number'];
        $yearNumber = $_SESSION['Year_Number']; 
        $cal = Calendar::CreateWorkingCalendar($user->dep_id, $user->role_id, $monthNumber, $yearNumber);
        $_SESSION['calendar'] = json_encode($cal);
        //print_r($_SESSION['calendar']);
        $cal->DrawCalendar();
    }
    else
    {
    $calend = json_decode($_SESSION['calendar']);
    //print_r($_SESSION['calendar']);
    $cal4 = Calendar::DecodeJsonCalendar($_SESSION["Month_Number"], $_SESSION["Year_Number"], $_SESSION['Current_User_Department_Id'], $calend);
    $cal4->DrawCalendar();
    }

    //Przycisk który generuje wydruk
    echo '<div class="calendarButtons no-print">';
        echo '<div class="button1 printCalendar" onclick="window.print();">Print</div>';
        echo '<div class="button1 saveCalendar" onclick="Calendar.saveCalendar()">Save</div>';
    echo '</div>';
}
else
{
    echo "No shifts detected in this department.";

}
    //print_r($arrOfHours);    
    
            

?>
            
