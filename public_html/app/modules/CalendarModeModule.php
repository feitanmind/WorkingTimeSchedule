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
    if (isset($_GET['usersToAdd']) && isset($_GET['dayId']))
    {
        $cal2 = json_decode($_SESSION['calendar']);
        $dayId = $_GET['dayId'];
        $uss = $_GET['usersToAdd'];
        foreach($uss as $us)
        {
            $user2 = new User($us);
            echo "uid:". $user2->user_id."<br>";
                    //     $cal2->SignUserToWorkInDay($user2, 0, 0);
                    
        }
                   
        $_SESSION['calendar'] = json_encode($cal2);
    }
                
    if(!isset($_SESSION['calendar']))
    {
        $cal = Calendar::CreateWorkingCalendar($user->dep_id, $user->role_id, 1, 2022);
        //echo $cal->Days[0]->Shifts[0]->EmployeesVacation[0]->name;
        $_SESSION['calendar'] = json_encode($cal);
        //$cal->SignUserToWorkInDay($user, 0, 0);
        $cal->DrawCalendar();
    }
    else
    {
    echo $_SESSION['calendar'];
    $cal23 = json_decode($_SESSION['calendar']);
    $cal = new Calendar(1,2022,1);
    foreach ($cal23 as $key => $value) $class->{$key} = $value;
    // $cal = $jsonDecoder->decode($cal23);
    $cal->DrawCalendar();
    }
            

?>
            <!-- Dodatkowe style zawierające wygląd formularzy i niektóre elementy CalendarMode -->
            <link rel="stylesheet" type="text/css" href="style/calendarModeAdditionalStyles.css"/>
            <!-- Przycisk który generuje wydruk -->
            <div class="button1 printCalendar">Print</div>