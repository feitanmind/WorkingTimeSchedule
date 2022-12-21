<?php
namespace App;
class Statistics
{
    public User $user;
    public Array $ShiftsForUserInCurrentMonth;
    public $HoursLeftToSign;
    
    public function __construct($userId)
    {
        $user = new User($userId);
        $encodedArrayOfHoursOfWork = $_SESSION['arrayOfHoursOfWorkForCurrentMonth'];
        $hoursOfWork = HoursOfWork::decodeArrayOfHoursOfWork($encodedArrayOfHoursOfWork);
        foreach($hoursOfWork as $how)
        {
            if($how->User->user_id == $userId)
            {
                $this->HoursLeftToSign = $how->Hours;
                break;
            }
        }
        $monthNumber = $_SESSION['Month_Number'];
        $yearNumber = $_SESSION['Year_Number'];
        $departmentId =  $user->dep_id;
        $encodedCalendar = $_SESSION['calendar'];
        $decodedCalendarAsStd = json_decode($encodedCalendar);
        $calendar = $encodedCalendar->DecodeJsonCalendar($monthNumber, $yearNumber, $departmentId, $decodedCalendarAsStd);
    }

    public static function DrawStatisticsChartForUser($user)
    {
        // <div class="StatisticGraph">
        //     <div class="StatisticGraph_mask">
        //     <p class="StatisticLeftHours_name">Left:</p>
        //     <p class="StatisticLeftHours">123:05</p>
        //     </div>
        // </div>
        $month_Number = $_SESSION['Month_Number'];
        $year = $_SESSION['Year_Number'];
        $department_ID = $user->dep_id;
        $decodedCalendarStd = json_decode($_SESSION['calendar']);
        $calendar = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $decodedCalendarStd);
        $colorsChart = array("#E65F8E","#A86BD1","#3AA5D1","#3BB58F","#3A63AD","#F7EA4A", "#FBC543", "#FFC9ED", "#E6696E");
        $countSignOnShift = [];
        foreach ($calendar->Days as $day) {
            $isWorking = $day->IfUserWorkingOnThisDay($user->user_id);
            if ($isWorking != false) {
                $shiftId = $isWorking[1];
                if(array_key_exists($shiftId,$countSignOnShift))
                {
                    $countSignOnShift[$shiftId]++;
                }
                else
                {
                    $countSignOnShift += [$shiftId => 1];
                }
            }
        }
        $i = 0;
        echo "<div class=\"StatisticShiftsForUser\">";
        $accessConnection = ConnectToDatabase::connAdminPass();
        ksort($countSignOnShift);
        foreach(array_keys($countSignOnShift) as $shift)
        {
            $sql = "SELECT name FROM shifts WHERE dep_id = $department_ID AND id = $shift;";
            $result = $accessConnection->query($sql);
            $row = $result->fetch_assoc();
            $nameOfShift = $row['name'];
            $s = new Shift($shift, $department_ID);
            $s->CompleteHours();
            $color = $s->Color;
            echo      "<div class=\"StatisticFlagForShift\" style=\"background-color:$color;\"></div>";
            echo      "<div class=\"StatisticNameOfShift\">$nameOfShift</div>";
            
            $i++;
        }
        echo "</div>";


        $hours_per_shift = $user->hours_per_shift;
        $hours = substr($hours_per_shift, 0, strpos($hours_per_shift, ":"));
        $step = substr($hours_per_shift, strpos($hours_per_shift, ":") + 1);
        $minutes = substr($step, strpos($hours_per_shift, ":"));
        if (str_starts_with($hours, "0"))$hours = substr($hours, 1);
        if (str_starts_with($minutes, "0"))$minutes = substr($minutes, 1);
        $minutesFraction = intval($minutes) / 60;
        $timeForDay = intval($hours) + $minutesFraction;
        echo print_r($countSignOnShift);
        echo "<style>";
        echo '#StatisticChart{';
        echo "background: repeating-conic-gradient(
                from 0deg,";
                $b = 0;
                $c = 0;
                
                foreach ($countSignOnShift as $shift => $val)
                {
                    
                    $sh = new Shift($shift, $department_ID);
                    $sh->CompleteHours();
                    $how = new HoursOfWork($user, $month_Number, $year,$user->hours_per_shift);
                    $hoursPerShift = $sh->HoursPerShift;


            $oneDyayDegree = 360 / $how->GetPartOfWorkingDaysOnShift($shift, $department_ID);

            $a = $oneDyayDegree * $val;
                   $a = floatval(substr($a, 0, 10));
                   $a = round($a, 2);
   
                    echo $sh->Color . " ".$b . "deg " . ($b + $a) . "deg, ";
                    $c++;
                    $b = $b+$a;
                }
                echo '#c0c0c0' ." ". $b . "deg " . 360 . "deg";
       echo " )!important }</style>";

        echo '<div class="StatisticGraphCircleRepresentation">';
        echo ' <div id="StatisticChart"class="StatisticGraph">';
        echo '    <div class="StatisticGraph_mask">';
        echo '    <p class="StatisticLeftHours_name">Left:</p>';
        echo '     <p class="StatisticLeftHours">123:05</p>';
        echo '   </div>';
        echo '</div>';
        echo '</div>';

      
    }
    public static function DrawMiniCalendarForUserStatistics($user)
    {
        $month_Number = $_SESSION['Month_Number'];
        $year = $_SESSION['Year_Number'];
        $department_ID = $user->dep_id;
        $decodedCalendarStd = json_decode($_SESSION['calendar']);
        $calendar = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $decodedCalendarStd);
        $dayOfTheWeek = date("w", mktime(0, 0, 0, $month_Number, 1, $year));
        $spaceFromMonday = $dayOfTheWeek != 0 ? $dayOfTheWeek - 1 : 6;
        $colorsChart = array("#E65F8E","#A86BD1","#3AA5D1","#3BB58F","#3A63AD","#F7EA4A", "#FBC543", "#FFC9ED", "#E6696E");
        echo "<table>";
        echo "<tr>";
        $numberDay = 1;
        $i = 1 + $spaceFromMonday;
        $shortMonthNames = array("Mo", "Tu", "We", "Th", "Fr", "Sa", "Su");
        for ($k = 0; $k < 7; $k++)
        {
            echo "<td><strong>$shortMonthNames[$k]</strong></td>";
        }
        echo "</tr><tr>";
        for ($j = 1; $j <= $spaceFromMonday; $j++)
        {
            echo "<td></td>";
        }
        foreach($calendar->Days as $day)
        {
            $isWorking = $day->IfUserWorkingOnThisDay($user->user_id);
            if($isWorking != false)
            {
                $color = $colorsChart[$isWorking[1]-1];
                echo "<td style=\"background-color: $color\">$numberDay</td>";
            }
            else
            {
                echo "<td>$numberDay</td>";
            }
            if($i % 7 == 0)
            {
                echo "</tr><tr>";
            }
            $numberDay++;
            $i++;
        }
        echo "</tr>";
        echo "</table>";

    }


}
?>