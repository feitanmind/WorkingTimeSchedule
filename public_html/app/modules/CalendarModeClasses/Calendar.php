<?php
namespace App;

use PhpParser\Node\Stmt\Foreach_;
use PHPUnit\Util\Exception;
use function PHPUnit\Framework\throwException;

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
    public static function Get_Name_Of_Month($monthNumber,$year)
    {
        return date('F', mktime(0, 0, 0, 1, $monthNumber, $year));
    }
    private function CreateDaysInMonth($monthNumber,$year,$department)
    {
        $numberDaysInMonth = cal_days_in_month(CAL_GREGORIAN,$monthNumber,$year);
        for($i = 1; $i <= $numberDaysInMonth; $i++)
        {
            array_push($this->Days, new Day($i, $department));
        }
    }
    // public function ActualizeDays($_day)
    // {
        
    //     foreach ($this->Days as $day)
    //     {
    //         if($day->NumberOfDay == $_day->NumberOfDay)
    //         {
    //             $day->Shifts = $_day->Shifts;
    //         }
    //     }
    // }
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
        $namesDaysOfWeek = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        echo '<div class="headerMainCalendar"><div class="monthArrow mArrowLeft">⤝</div><div id="nameOfMonth">' . $this::Get_Name_Of_Month($this->MonthNumber, $this->Year) . " ".$this->Year.'</div><div class="monthArrow mArrowRight";>⤞</div></div>';
        //Rysujemy początek
        echo '<div id="calM" class="mainCalendar">';
 
        foreach($namesDaysOfWeek as $nameOfDay)
        {
            echo "<div class=\"dayOfTheWeek nameDayOfTheWeek\">$nameOfDay</div>";
        }
        //Rysujemy odstęp który wcześniej wyliczylismy
        for ($i = $spaceFromMonday; $i > 0; $i--)
        {
            echo '<div class="dayOfTheWeek outDay"></div>';
        }
        
        //Rysujemy wszsytkie pola miesiąca 
        for($j = 1; $j <= $numberDaysInMonth; $j++)
        {
            $workingPeople = $this->Days[$j-1]->Shifts[0]->EmployeesWorking;
            $vacationPeople = $this->Days[$j-1]->Shifts[0]->EmployeesVacation;

            echo '<div class="dayOfTheWeek"  id="day'.$j.'" onmouseover="showAddAndRemoveControls('.$j.')" onmouseout="hideAddAndRemoveControls('.$j.')">'
                    .'<div class="numberOfDay"><p><b>'.$j.'</b></p>
                        <div id="addP" class="windowButton" onclick="createFormToAddPersonToDay(this);">+</div>
                        <div id="remP" class="windowButton" onclick="createFormToRemovePersonFormShift(this);">-</div>
                        <div id="addV" class="windowButton" onclick="createFormToAddVacationToPerson(this);">v</div>
                        <div id="remV" class="windowButton" onclick="createFormToRevokePersonVacation(this);">ꝟ</div>
                     </div>'
                    ."<div class=\"dayBody\">";
                    echo "Working:<div id=\"working\">";
                    foreach($workingPeople as $workingPerson)
                    {
                        echo  "<div class=\"userW\" personID=\"$workingPerson->user_id\">$workingPerson->name</div>";
                    }
                    echo "</div>Vacation:<div id=\"vacation\">";
                    foreach($vacationPeople as $vacationingPerson)
                    {
                        echo "<div class=\"userV\" personID=\"$vacationingPerson->user_id\" >$vacationingPerson->name</div>";
                    }
                    
                    echo "</div></div>
                             
                  </div>";
        }
        //Rysujemy dni po końcu miesiąca
        for($l = $daysOut; $l > 0; $l--)
        {
            echo '<div class="dayOfTheWeek outDay"></div>';
        }
        //Koniec rysowania kalendarza
        echo '</div>';
    }

    public function SignUserToWorkInDay($user,$dayId, $shiftId)
    {
        array_push($this->Days[$dayId-1]->Shifts[$shiftId-1]->EmployeesWorking, $user);
    }
    public function SignUserVacation($user,$dayId, $shiftId)
    {
        array_push($this->Days[$dayId-1]->Shifts[$shiftId-1]->EmployeesVacation, $user);
    }
    public function UnsignWorkingUserFormDay($user,$dayId,$shiftId)
    {
        
        $indexUserToDel = 0;
        
        foreach($this->Days[$dayId-1]->Shifts[$shiftId-1]->EmployeesWorking as $employee)
        {
            $in = null;
            if($employee->user_id == $user->user_id)
            {
                $in = $indexUserToDel;
                break;
            } 

            $indexUserToDel++;
        }
        array_splice($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesWorking, $in,1);

    }
    public function UnsignVacationUserFormDay($user,$dayId,$shiftId)
    {
        
        $indexUserToDel = 0;
        
        foreach($this->Days[$dayId-1]->Shifts[$shiftId-1]->EmployeesVacation as $employee)
        {
            $in = null;
            if($employee->user_id == $user->user_id)
            {
                $in = $indexUserToDel;
                break;
            } 

            $indexUserToDel++;
        }
        array_splice($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesVacation, $in,1);
    }
    //Dodawanie nowego kalendarza do bazy danych
    public function PushCalendarToDataBase($role_Id,$calendar)
    {
        //Połączenie się do bazy danych
        $access_Connection = ConnectToDatabase::connAdminPass();
        //Serializacja obiektu Calendar do formatu JSON
        $encoded_Calendar = json_encode($calendar);
        //Liczba lat ma być przetrzymywany kalendarz
        $expire_Years = 2;
        //Miesiąc w formacie daty
        $month = date("Y-m-d", mktime(0,0,0,$this->MonthNumber,1,$this->Year));
        //Data wygaśnięcia
        $expire = date("Y-m-d", mktime(0,0,0,$this->MonthNumber,1,$this->Year + $expire_Years));
        //Identyfikator działu/oddziału
        $department_Id = $this->Department;
        //Dodawanie do bazy danych rekordu - do tabeli calendar dodawany jest nowy kalendarz
        $sql_Insertion_Query = "INSERT INTO calendar(monthDate,depId,roleId,days,expireDate) VALUES('$month',$department_Id,$role_Id,'$encoded_Calendar','$expire');";
        //Egzekwowanie powyższego polecenia sql
        $access_Connection->query($sql_Insertion_Query);
    }
    public function UpdateCalendarInDatabase()
    {

    }
    //Funkcja zwraca obiekt Calendar - gdy istnieje w bazie danych to z bazy -gdy nie istnieje w bazie danych to tworzy nowy obiekt
    public static function CreateWorkingCalendar($department_ID, $role_ID,$month_Number,$year)
    {
        //Data miesiąca
        $month_Date = date("Y-m-d",mktime(0,0,0,$month_Number,1,$year));
        //Połączenie za pomocą poświadczeń Administracyjnych - Do zmiany na użytkownika
        $access_Connection= ConnectToDatabase::connAdminPass();
        //Znalezienie zserialiowanego kalendarza
        $sql = "SELECT days FROM calendar WHERE monthDate = '$month_Date' AND roleId = $role_ID AND depId = $department_ID";
        //Przypisanie wyniku zapytania do zmiennej
        $result_Of_Query = $access_Connection ->query($sql);
        //Sprawdzenie czy istnieje podany wpis poprzez weryfikację rezultatu
        if ($result_Of_Query->num_rows <= 0)
        {
            //Zwracanie nowostworzonego kalendarza
            return new Calendar($month_Number, $year, $department_ID);
        }
        else
        {
            echo "hope";
            //Przypisanie wyniku do postaci tablicy asocjacyjnej
            $row = $result_Of_Query->fetch_assoc();
            //Zwracanie zdeserializowanego obiektu typu Calendar
            $calend = json_decode($row['days']);
            $calend2 = new Calendar($month_Number, $year, $department_ID);
            foreach ($calend as $key => $value) $calend2->{$key} = $value;
            return $calend2;
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