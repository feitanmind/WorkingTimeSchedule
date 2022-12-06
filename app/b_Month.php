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
        echo '<div class="mainCalendar">';
 
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

}


?>