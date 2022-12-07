<?php
namespace App;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class b_Month
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
    public function DrawMonth()
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

            echo '<div class="dayOfTheWeek"  id="day'.$j.'">'
                    .'<div class="numberOfDay"><p>'.$j.'</p>
                        <div id="addP" onclick="createFormToAddPersonToDay(this);">ADD</div>
                        <div id="remP" onclick="createFormToRemovePersonFormShift(this);">REMOVE</div>
                     </div>'
                    ."<div class=\"dayBody\">";
                    foreach($workingPeople as $workingPerson)
                    {
                        echo $workingPerson->name;
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
    public function JsonEncodeMonth()
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
    public static function JsonDecodeMonth($json)
    {
        $position = strpos($json, "MonthNumber");
        //echo "Pos num: ". $posMonthNumber;
        $step = substr($json, $position+13);
        
        $_monthNumber = substr($step, 0, strpos($step, ","));
        echo "Month number: ".$_monthNumber."<br>";
        
        $position = strpos($step, "Year");
        $step = substr($step, $position+6);
        $_year = substr($step, 0, strpos($step,","));
        echo "Year: " .$_year. "<br>";
        echo "____________<br>";
        //$JSONMonthDecode = new b_Month();

        
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
            echo "NumberOfDay: $_numberOfDay <br>";
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
                echo "____Shift Id: ".$_shiftId ."<br>";
                $position = strpos($step,"[");
                $step = substr($step,$position);
                $positionEndOfWorking = strpos($step,"EmployeesVacation");
                $step = substr($step,0,$positionEndOfWorking);
                    //while
                    
                    while(strpos($step,"UserId") != false)
                    {
                        $position = strpos($step,"UserId");
                        $step = substr($step, $position+8);
                        $_userId = substr($step,0, strpos($step,"}"));
                        echo "_______User-Id: $_userId <br>";                
                    }
                $step = $step2;
            }
        $step = $step3;
        }
        
        
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