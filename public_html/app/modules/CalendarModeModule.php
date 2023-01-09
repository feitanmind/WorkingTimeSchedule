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


    //print_r($arrOfHours);    
    if(!isset($_SESSION['calendar'])) {
        $monthNumber = $_SESSION['Month_Number'];
        $yearNumber = $_SESSION['Year_Number']; 
        $cal = Calendar::CreateWorkingCalendar($user->dep_id, $user->role_id, $monthNumber, $yearNumber);
        $_SESSION['calendar'] = json_encode($cal);
        $cal->DrawCalendar();
    }
    else
    {
    $calend = json_decode($_SESSION['calendar']);
    $cal4 = Calendar::DecodeJsonCalendar($_SESSION["Month_Number"], $_SESSION["Year_Number"], $_SESSION['Current_User_Department_Id'], $calend);
    $cal4->DrawCalendar();
    }
            

?>
            
            <!-- Przycisk który generuje wydruk -->
            <div class="calendarButtons no-print">
                <div class="button1 printCalendar" onclick="window.print();">Print</div>
                <div class="button1 saveCalendar" onclick="Calendar.saveCalendar()">Save</div>
            </div>