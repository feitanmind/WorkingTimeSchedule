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
    $arrOfHours = PHPSCripts::CreateArrayOfHoursOfWorkForUsers();
    //print_r($arrOfHours);    
    if(!isset($_SESSION['calendar']))
    {      
        $cal = Calendar::CreateWorkingCalendar($user->dep_id, $user->role_id, 1, 2022);
        $_SESSION['calendar'] = json_encode($cal);
        $cal->DrawCalendar();
    }
    else
    {
    $calend = json_decode($_SESSION['calendar']);
    $cal = Calendar::DecodeJsonCalendar(1, 2022, 1, $calend);
    $cal->DrawCalendar();
    }
            

?>
            
            <!-- Przycisk który generuje wydruk -->
            <div class="calendarButtons no-print">
                <div class="button1 printCalendar">Print</div>
                <div class="button1 saveCalendar" onclick="Calendar.saveCalendar()">Save</div>
            </div>