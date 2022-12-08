<?php
namespace App;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Calendar
{
    private int $MonthNumber;
    private int $Year;
    public int $Department;
    public $Days = array();

    public function __construct($monthNumber, $year, $department)
    {
        $this->MonthNumber = $monthNumber;
        $this->Year = $year;
        $this->Department = $department;
        $this->CreateDaysInMonth($monthNumber,$year,$department);
    }
    private function CreateDaysInMonth($monthNumber,$year,$department)
    {
        $numberDaysInMonth = cal_days_in_month(CAL_GREGORIAN,$monthNumber,$year);
        for($i = 1; $i <= $numberDaysInMonth; $i++)
        {
            array_push($this->Days, new Day($i, $department));
        }
    }
    public function ActualizeDays($_day)
    {
        
        foreach ($this->Days as $day)
        {
            if($day->NumberOfDay == $_day->NumberOfDay)
            {
                $day->Shifts = $_day->Shifts;
            }
        }
    }
    public function DrawCalendar()
    {
        //Sprawdzamy ile dni jest w miesiącu
        $numberDaysInMonth = cal_days_in_month(CAL_GREGORIAN,$this->MonthNumber,$this->Year);
        //Będziemy domyślnie rysować 42 krotki, Tabela 7X6
        $drawingFields = 42;
        //Sprawdzamy jakim dniem tygodnia jest pierwszy dzień miesiąca
        $dayOfTheWeek = date("w", mktime(0, 0, 0, $this->MonthNumber, 1, $this->Year));
        //Będziemy wyświeltać dni od poniedziałku więc liczymy odstęp pomiędzy poniedziałkiem a pierwszym dniem miesiąca
        $spaceFromMonday = $dayOfTheWeek != 0 ? $dayOfTheWeek-1 : 6;
        //Sprawdzamy jaka odległość w dniach dzieli ostatni dzień miesiąca od końca naszej tabeli
        $daysOut = $drawingFields - ($spaceFromMonday + $numberDaysInMonth);
        //Ustawiamy dni tygodnia
        $namesDaysOfWeek = array('Monday','Tuesday','Wednesday','Thursday','Saturday','Sunday');

        //Rysujemy początek
        echo '<div id="calM" class="mainCalendar">';
 
        foreach($namesDaysOfWeek as $nameOfDay)
        {
            echo "<div class=\"dayOfTheWeek\" name>$nameOfDay</div>";
        }
        //Rysujemy odstęp który wcześniej wyliczylismy
        for ($i = $spaceFromMonday; $i > 0; $i--)
        {
            echo '<div class="dayOfTheWeek outDay" style="background-color: grey;"></div>';
        }
        
        //Rysujemy wszsytkie pola miesiąca 
        for($j = 1; $j <= $numberDaysInMonth; $j++)
        {
            $workingPeople = $this->Days[$j-1]->Shifts[0]->EmployeesWorking;
            $vacationPeople = $this->Days[$j-1]->Shifts[0]->EmployeesWorking;

            echo '<div class="dayOfTheWeek"  id="day'.$j.'">'
                    .'<div class="numberOfDay"><p>'.$j.'</p>
                        <div id="addP" onclick="createFormToAddPersonToDay(this);">ADD</div>
                        <div id="remP" onclick="createFormToRemovePersonFormShift(this);">REMOVE</div>
                     </div>'
                    ."<div class=\"dayBody\">";
                    echo "Working:";
                    foreach($workingPeople as $workingPerson)
                    {
                        echo $workingPerson->name;
                    }
                    echo "<br>Vacation:";
                    foreach($vacationPeople as $vacationingPerson)
                    {
                        echo $vacationingPerson->name;
                    }
                    
                    echo "</div>
                             
                  </div>";
        }
        //Rysujemy dni po końcu miesiąca
        for($l = $daysOut; $l > 0; $l--)
        {
            echo '<div class="dayOfTheWeek outDay" style="background-color: grey;"></div>';
        }
        //Koniec rysowania kalendarza
        echo '</div>';
    }
    public function JsonEncodeCalendar()
    {
        $JSONMonthEncode = "";
        $JSONMonthEncode=$JSONMonthEncode. "{
                            \"MonthNumber\":$this->MonthNumber,
                            \"Year\":$this->Year,
                            \"Days\":[
                                ";
                            foreach($this->Days as $day)
                            {
                              $JSONMonthEncode=$JSONMonthEncode."
                                        {
                                            \"NumberOfDay\": $day->NumberOfDay,
                                            \"Shifts\": [
                                                ";
                                                foreach($day->Shifts as $shift)
                                                {
                                                    $JSONMonthEncode=$JSONMonthEncode. "{\"ShiftID\": $shift->Id,
                                                                        \"EmployeesWorking\": [";
                                                                        if(!empty($shift->EmployeesWorking))
                                                                        {
                                                                            foreach($shift->EmployeesWorking as $employee)
                                                                            {
                                                                                $JSONMonthEncode=$JSONMonthEncode."{UserId: $employee->user_id},"; 
                                                                            }
                                                                            $JSONMonthEncode = substr($JSONMonthEncode,0,-1);
                                                                        }
                                                                        
                                                                        $JSONMonthEncode=$JSONMonthEncode."],
                                                                        \"EmployeesVacation\" : [";
                                                                        if(!empty($shift->EmployeesVacation))
                                                                        {
                                                                            foreach($shift->EmployeesVacation as $employee)
                                                                            {
                                                                                $JSONMonthEncode=$JSONMonthEncode."{UserId: $employee->user_id},";
                                                                            }
                                                                            $JSONMonthEncode = substr($JSONMonthEncode,0,-1);
                                                                        }
                                                                        
                                                                        $JSONMonthEncode=$JSONMonthEncode."]
                                                                        },";    
                                                                        
                                                }
                                                $JSONMonthEncode = substr($JSONMonthEncode,0,-1);
                                                $JSONMonthEncode=$JSONMonthEncode."]
                                            },";
                                }
                                $JSONMonthEncode = substr($JSONMonthEncode,0,-1);
                                $JSONMonthEncode=$JSONMonthEncode."]}";
        return trim($JSONMonthEncode);
        
    }
    public static function JsonDecodeCalendar($json,$depId)
    {
        $position = strpos($json, "MonthNumber");
        //echo "Pos num: ". $posMonthNumber;
        $step = substr($json, $position+13);
        
        $_monthNumber = substr($step, 0, strpos($step, ","));
        //echo "Month number: ".$_monthNumber."<br>";
        
        $position = strpos($step, "Year");
        $step = substr($step, $position+6);
        $_year = substr($step, 0, strpos($step,","));
        //echo "Year: " .$_year. "<br>";
        //echo "____________<br>";
        //$JSONMonthDecode = new b_Month();
        $month = new Calendar($_monthNumber, $_year,$depId);
        
        while(strpos($step, "NumberOfDay") != false)
        {
            $position = strpos($step, "NumberOfDay");
            $step = substr($step, $position+14);
            $step3 = $step;
            if(strpos($step,"NumberOfDay"))
            {
                $positionNextNumberOfDay = strpos($step, "NumberOfDay");
                $step = substr($step,0, $positionNextNumberOfDay);
                $_numberOfDay = substr($step, 0, strpos($step,","));
            }
            else
            {

                $positionNextNumberOfDay = strpos($step, "}]");
                $step = substr($step,0, $positionNextNumberOfDay);
                $_numberOfDay = substr($step, 0, strpos($step,","));
            }
        
            //echo $step;
            // $step = substr($step,0, $positionNextNumberOfDay);
            // $_numberOfDay = substr($step, 0, strpos($step,","));
            //echo "NumberOfDay: $_numberOfDay <br>";
            $day = new Day($_numberOfDay, $depId);
            //echo $step;
            //while
            while(strpos($step, "ShiftID"))
            {
                $position = strpos($step, "ShiftID");
                $step = substr($step, $position+10);
                $step2 = $step;
                if(strpos($step,"ShiftID"))
                {
                    $positionNextShiftID = strpos($step,"ShiftID");
                }
                else
                {
                    $positionNextShiftID = strlen($step);
                }
                
                $step = substr($step,0, $positionNextShiftID);
                $_shiftId = substr($step,0,strpos($step,","));
                //echo "____Shift Id: ".$_shiftId ."<br>";
                $_shift = new Shift($_shiftId, $depId);
                $_arrayOfUsers = array();
                $position = strpos($step,"[");
                $step = substr($step,$position);
                $step4 = $step;
                $positionEndOfWorking = strpos($step,"EmployeesVacation");
                $step = substr($step,0,$positionEndOfWorking);
                    //while
                //echo "_____________WORKING<br>";
                    while(strpos($step,"UserId") != false)
                    {
                        $position = strpos($step,"UserId");
                        $step = substr($step, $position+8);
                        $_userId = substr($step,0, strpos($step,"}"));
                        //dodanie użytkownika do shiftu
                        array_push($_arrayOfUsers, new User($_userId));
                        //echo "______________________User-Id: $_userId <br>"; 

                    }
                $_shift->EmployeesWorking = $_arrayOfUsers;
                $_arrayOfUsers = array();

                $step = $step4;
                $step = substr($step, $positionEndOfWorking);
                //echo "_____________VACATION<br>";
                while(strpos($step,"UserId") != false)
                    {
                        $position = strpos($step,"UserId");
                        $step = substr($step, $position+8);
                        $_userId = substr($step,0, strpos($step,"}"));
                        //dodanie użytkownika do shiftu
                        array_push($_arrayOfUsers, new User($_userId));
                        //echo "______________________User-Id: $_userId <br>"; 

                    }
                $_shift->EmployeesVacation = $_arrayOfUsers;
                $day->ActualizeShifts($_shift);
                $step = $step2;
            }
        $month->ActualizeDays($day);
        $step = $step3;

        }

        echo "TEST<br>";
    //    echo $month->Days[0]->Shifts[0]->EmployeesWorking[0]->name;

        return $month;
    }
    public function SignUserToWorkInDay($user,$dayId, $shiftId)
    {
        array_push($this->Days[$dayId]->Shifts[$shiftId]->EmployeesWorking, $user);
    }
    public function SignUserVacation($user,$dayId, $shiftId)
    {
        array_push($this->Days[$dayId]->Shifts[$shiftId]->EmployeesVacation, $user);
    }
    public function UnsignWorkingUserFormDay($user,$dayId,$shiftId)
    {
        $keyToDelete = array_search($user,$this->Days[$dayId]->Shifts[$shiftId]->EmployeesWorking);
        array_splice($this->Days[$dayId]->Shifts[$shiftId]->EmployeesWorking,$keyToDelete);
    }
    public function UnsignVacationUserFormDay($user,$dayId,$shiftId)
    {
        $keyToDelete = array_search($user,$this->Days[$dayId]->Shifts[$shiftId]->EmployeesVacation);
        array_splice($this->Days[$dayId]->Shifts[$shiftId]->EmployeesWorking,$keyToDelete);
    }

    public function RemoveMonth()
    {

        echo "
        <script>
        document.getElementById('calM').remove();
        </script>
        
        ";
    }

}


?>