<?php
namespace App;

use PhpParser\Node\Stmt\Foreach_;
use PHPUnit\Util\Exception;

use function PHPUnit\Framework\returnSelf;
use function PHPUnit\Framework\throwException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Calendar
{
    public int $MonthNumber;
    public int $Year;
    public int $Department;
    public $Days = array();

    public function __construct($monthNumber, $year, $department)
    {
        $this->MonthNumber = $monthNumber;
        $this->Year = $year;
        $this->Department = $department;
        $this->CreateDaysInMonth($monthNumber, $year, $department);
    }
    public static function Get_Name_Of_Month($monthNumber, $year)
    {
        return date('F', mktime(0, 0, 0, $monthNumber, 1, $year));
        
    }
    private function CreateDaysInMonth($monthNumber, $year, $department)
    {
        $numberDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $year);
        for ($i = 1;$i <= $numberDaysInMonth;$i++)
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
        $shiftId = $_SESSION["Shift_Id"] - 1;
        $roleId = $_SESSION["Role_Id"];
        //Sprawdzamy ile dni jest w miesiącu
        $numberDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->MonthNumber, $this->Year);
        //Będziemy domyślnie rysować 42 krotki, Tabela 7X6
        $drawingFields = 42;
        //Sprawdzamy jakim dniem tygodnia jest pierwszy dzień miesiąca
        $dayOfTheWeek = date("w", mktime(0, 0, 0, $this->MonthNumber, 1, $this->Year));
        //Będziemy wyświeltać dni od poniedziałku więc liczymy odstęp pomiędzy poniedziałkiem a pierwszym dniem miesiąca
        $spaceFromMonday = $dayOfTheWeek != 0 ? $dayOfTheWeek - 1 : 6;
        //Sprawdzamy jaka odległość w dniach dzieli ostatni dzień miesiąca od końca naszej tabeli
        $daysOut = $drawingFields - ($spaceFromMonday + $numberDaysInMonth);
        //Ustawiamy dni tygodnia
        $namesDaysOfWeek = array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        );
        echo '<div class="headerMainCalendar">'.
                '<div class="monthArrow mArrowLeft no-print" onclick="Calendar.changeMonth(\'BACK\');">⤝</div>'.
                '<div id="nameOfMonth">' 
                    . $this::Get_Name_Of_Month($this->MonthNumber, $this->Year) . " " . $this->Year . 
                '</div>'.
                '<div class="monthArrow mArrowRight no-print" onclick="Calendar.changeMonth(\'NEXT\');">⤞</div>'.
            '</div>';
        //Rysujemy początek
        echo '<div id="calM" class="mainCalendar">';

        foreach ($namesDaysOfWeek as $nameOfDay)
        {
            echo "<div class=\"dayOfTheWeek nameDayOfTheWeek\">$nameOfDay</div>";
        }
        //Rysujemy odstęp który wcześniej wyliczylismy
        for ($i = $spaceFromMonday;$i > 0;$i--)
        {
            echo '<div class="dayOfTheWeek outDay"></div>';
        }

        //Rysujemy wszsytkie pola miesiąca
        for ($j = 1;$j <= $numberDaysInMonth;$j++)
        {
            $workingPeople = $this->Days[$j - 1]->Shifts[$shiftId]->EmployeesWorking;
            $vacationPeople = $this->Days[$j - 1]->Shifts[$shiftId]->EmployeesVacation;

            echo '<div class="dayOfTheWeek"  id="day' . $j . '" onmouseover="showAddAndRemoveControls(' . $j . ')" onmouseout="hideAddAndRemoveControls(' . $j . ')">' . '<div class="numberOfDay"><p><b>' . $j . '</b></p>
                        <div id="addP" class="windowButton" onclick="createFormToAddPersonToDay(this);">+</div>
                        <div id="remP" class="windowButton" onclick="createFormToRemovePersonFormShift(this);">-</div>
                        <div id="addV" class="windowButton" onclick="createFormToAddVacationToPerson(this);">v</div>
                        <div id="remV" class="windowButton" onclick="createFormToRevokePersonVacation(this);">ꝟ</div>
                     </div>' . "<div class=\"dayBody\">";
            echo "Working:<div id=\"working\">";
            foreach ($workingPeople as $workingPerson)
            {
                if($roleId == $workingPerson->role_id)
                {
                    echo "<div class=\"userW\" personID=\"$workingPerson->user_id\">$workingPerson->name</div>";
                }
                
            }
            echo "</div>Vacation:<div id=\"vacation\">";
            foreach ($vacationPeople as $vacationingPerson)
            {
                if ($roleId == $vacationingPerson->role_id) {
                    echo "<div class=\"userV\" personID=\"$vacationingPerson->user_id\" >$vacationingPerson->name</div>";
                }
            }

            echo "</div></div>
                             
                  </div>";
        }
        //Rysujemy dni po końcu miesiąca
        for ($l = $daysOut;$l > 0;$l--)
        {
            echo '<div class="dayOfTheWeek outDay"></div>';
        }
        //Koniec rysowania kalendarza
        echo '</div>';
    }

    public function SignUserToWorkInDay($user, $dayId, $shiftId)
    {
        if($this->CanUserBeSignOnDay($user,$dayId,$shiftId))
        {
            array_push($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesWorking, $user);
        }
        else
        {
            //throw new \Exception("You can't sign user. User wassigned on colliding shift");
            return false;
        }
        
    }
    public function SignUserVacation($user, $dayId, $shiftId)
    {
        array_push($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesVacation, $user);
    }
    public function UnsignWorkingUserFormDay($user, $dayId, $shiftId)
    {

        $indexUserToDel = 0;

        foreach ($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesWorking as $employee)
        {
            $in = null;
            if ($employee->user_id == $user->user_id)
            {
                $in = $indexUserToDel;
                break;
            }

            $indexUserToDel++;
        }
        array_splice($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesWorking, $in, 1);

    }
    public function UnsignVacationUserFormDay($user, $dayId, $shiftId)
    {

        $indexUserToDel = 0;

        foreach ($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesVacation as $employee)
        {
            $in = null;
            if ($employee->user_id == $user->user_id)
            {
                $in = $indexUserToDel;
                break;
            }

            $indexUserToDel++;
        }
        array_splice($this->Days[$dayId - 1]->Shifts[$shiftId - 1]->EmployeesVacation, $in, 1);
    }
    //Dodawanie nowego kalendarza do bazy danych
    public function PushCalendarToDataBase($role_Id)
    {
        $roleId = $_SESSION['Role_Id'];
        $calendarIsInDatabase = Calendar::ChceckCalendarInDb($this->Department,$roleId,$this->MonthNumber,$this->Year);                    
        $access_Connection = ConnectToDatabase::connAdminPass();
        $encoded_Calendar = json_encode($this);
        $monthDateInDatabase = date("Y-m-d", mktime(0, 0, 0, $this->MonthNumber, 1, $this->Year));

        if(!$calendarIsInDatabase)
        {
            $expire_Years = 2;
            //Miesiąc w formacie daty
            $month = date("Y-m-d", mktime(0, 0, 0, $this->MonthNumber, 1, $this->Year));
            //Data wygaśnięcia
            $expire = date("Y-m-d", mktime(0, 0, 0, $this->MonthNumber, 1, $this->Year + $expire_Years));
            //Identyfikator działu/oddziału
            $department_Id = $this->Department;
            //Dodawanie do bazy danych rekordu - do tabeli calendar dodawany jest nowy kalendarz
            $sql_Insertion_Query = "INSERT INTO calendar(monthDate,depId,roleId,days,expireDate) VALUES('$month',$department_Id,$role_Id,'$encoded_Calendar','$expire');";
            //Egzekwowanie powyższego polecenia sql
            $access_Connection->query($sql_Insertion_Query);
        }
        else
        {
            $sqlUpdateQueryd = "UPDATE calendar SET days='$encoded_Calendar' WHERE monthDate = '$monthDateInDatabase';";
            $access_Connection->query($sqlUpdateQueryd);
           
        }
        
    }
    public function UpdateCalendarInDatabase()
    {

    }
    public static function ChceckCalendarInDb($department_ID, $role_ID, $month_Number, $year)
    {
        //Data miesiąca
        $month_Date = date("Y-m-d", mktime(0, 0, 0, $month_Number, 1, $year));
        //Połączenie za pomocą poświadczeń Administracyjnych - Do zmiany na użytkownika
        $access_Connection = ConnectToDatabase::connAdminPass();
        //Znalezienie zserialiowanego kalendarza
        $sql = "SELECT days FROM calendar WHERE monthDate = '$month_Date' AND roleId = $role_ID AND depId = $department_ID";
        //Przypisanie wyniku zapytania do zmiennej
        $result_Of_Query = $access_Connection->query($sql);
        //Sprawdzenie czy istnieje podany wpis poprzez weryfikację rezultatu
        if ($result_Of_Query->num_rows <= 0)
        {
            return false;
        }
        else
        {
            return $result_Of_Query;
        }
    }
    public static function castObjToObj($instance, $className)
    {
        return unserialize(sprintf('O:%d:"%s"%s', strlen($className) , $className, strstr(strstr(serialize($instance) , '"') , ':')));
    }
    //Funkcja zwraca obiekt Calendar - gdy istnieje w bazie danych to z bazy -gdy nie istnieje w bazie danych to tworzy nowy obiekt
    public static function CreateWorkingCalendar($department_ID, $role_ID, $month_Number, $year)
    {

        
        //Data miesiąca
        $month_Date = date("Y-m-d", mktime(0, 0, 0, $month_Number, 1, $year));
        //Połączenie za pomocą poświadczeń Administracyjnych - Do zmiany na użytkownika
        $access_Connection = ConnectToDatabase::connAdminPass();
        $sql_SelectHours = "SELECT usr_id, hours_of_work FROM user_data";
        // $result_SelectHours = $access_Connection->query($sql_SelectHours);
        // if($result_SelectHours->num_rows > 0)
        // {
        //     while($row_of_SelectHours = $result_SelectHours->fetch_assoc())
        //     {
                
        //     }
        // }
      

        //Znalezienie zserialiowanego kalendarza
        $sql = "SELECT days FROM calendar WHERE monthDate = '$month_Date' AND roleId = $role_ID AND depId = $department_ID";
        //Przypisanie wyniku zapytania do zmiennej
        $result_Of_Query = $access_Connection->query($sql);
        //Sprawdzenie czy istnieje podany wpis poprzez weryfikację rezultatu
        if ($result_Of_Query->num_rows <= 0)
        {
            //Zwracanie nowostworzonego kalendarza
            return new Calendar($month_Number, $year, $department_ID);
        }
        else
        {
            //Przypisanie wyniku do postaci tablicy asocjacyjnej
            $row = $result_Of_Query->fetch_assoc();
            //Zwracanie zdeserializowanego obiektu typu Calendar
            $calendarAsObjectAStdClass = json_decode($row['days']);
            //_____

            return Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calendarAsObjectAStdClass);

            // foreach ()
            
        }
    }

    public static function DecodeJsonCalendar($month_Number, $year, $department_ID,$calendarAsObjectAStdClass)
    {
       
        $calenadarObjectAsCalendar = new Calendar($month_Number, $year, $department_ID);

        
        for ($i = 0;$i <= sizeof($calendarAsObjectAStdClass->Days) - 1;$i++)
        {
            $shifts = array();
            for ($j = 0;$j <= sizeof($calendarAsObjectAStdClass->Days[$i]->Shifts) - 1;$j++)
            {
                $eWorking = array();
                $eVacation = array();
                if (!empty($calendarAsObjectAStdClass->Days[$i]->Shifts[$j]->EmployeesWorking))
                {
                    for ($k = 0;$k <= sizeof($calendarAsObjectAStdClass->Days[$i]->Shifts[$j]->EmployeesWorking) - 1;$k++)
                    {
                        array_push($eWorking, new User($calendarAsObjectAStdClass->Days[$i]->Shifts[$j]->EmployeesWorking[$k]->user_id));
                    }
                    $calenadarObjectAsCalendar->Days[$i]->Shifts[$j]->EmployeesWorking = $eWorking;
                }
                if (!empty($calendarAsObjectAStdClass->Days[$i]->Shifts[$j]->EmployeesVacation))
                {
                    for ($k = 0;$k <= sizeof($calendarAsObjectAStdClass->Days[$i]->Shifts[$j]->EmployeesVacation) - 1;$k++)
                    {
                        array_push($eVacation, new User($calendarAsObjectAStdClass->Days[$i]->Shifts[$j]->EmployeesVacation[$k]->user_id));
                    }

                    $calenadarObjectAsCalendar->Days[$i]->Shifts[$j]->EmployeesVacation = $eVacation;
                }

            }
        }
        return $calenadarObjectAsCalendar;
    }

    public function RemoveMonth()
    {

        echo "
        <script>
        document.getElementById('calM').remove();
        </script>
        
        ";
    }

    public function CanUserBeSignOnDay($user, $day_id, $shift_id)
    {
        $dateString = $this->Year . '-' . $this->MonthNumber . '-1';
        $dateOfMonth = date("Y-m", strtotime($dateString));
        $enrolledShift = $this->Days[$day_id - 1]->Shifts[$shift_id - 1];
        $enroledHours = $enrolledShift->GetIntArray_HoursOfShift();

        $currentShifts = $this->Days[$day_id - 1]->Shifts;
        foreach ($currentShifts as $shift)
        {
            $shift->CompleteHours();
            if ($shift->EmployeesWorking != null && $shift->EmployeesVacation == null) {
                foreach ($shift->EmployeesWorking as $employee) {
                    if ($employee->user_id == $user->user_id) {
                        return false;
                    } else {
                        // ____1
                        if ($day_id != 1) {
                            $dayBeforeShifts = $this->Days[$day_id - 2]->Shifts;
                            //2
                            if (Calendar::checkDayBefore($dayBeforeShifts, $enroledHours, $user))
                            {
                                if(!$this->Days[$day_id - 1]->IsLastDayOfMonth($this->MonthNumber, $this->Year))
                                {
                                    $dayNextShifts = $this->Days[$day_id]->Shifts;
                                    if(Calendar::checkNextDay($dayNextShifts, $enroledHours,$user))
                                        continue;
                                    else
                                        return false;
                                } 
                                else
                                {
                                    $roleID = 1;
                            $yearOfNextMonth= intval(date('Y', strtotime("+1 months", strtotime($dateOfMonth))));
                            $numberNextOfMonth = intval(date('m', strtotime("+1 months", strtotime($dateOfMonth))));
                            $numberOfNextDay = 1;
                            $checkNextMonthIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                            if (!$checkNextMonthIsOnDb)
                                continue;
                            else
                            {
                                $calendarNextDay1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                $dayNextShifts = $calendarNextDay1->Days[0]->Shifts;
                                if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                    continue;
                                else
                                    return false;
                            }
                                }
                                    
                            }
                                
                            else
                                return false;
                            //2e
                        }
                        else
                        {
                            $roleID = 1;
                            $yearOfMonthBefore = intval(date('Y', strtotime("-1 months", strtotime($dateOfMonth))));
                            $numberOfMonthBefore = intval(date('m', strtotime("-1 months", strtotime($dateOfMonth))));
                            $numberOfDayBefore = cal_days_in_month(CAL_GREGORIAN, $numberOfMonthBefore, $yearOfMonthBefore);
                            $checkMonthBeforeIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberOfMonthBefore, $yearOfMonthBefore);
                            if (!$checkMonthBeforeIsOnDb)
                            {
                                //Będzie trzeba napisać gdy zmiana koliduje z dniem następnym
                                //narazie
                                if (!$this->Days[$day_id - 1]->IsLastDayOfMonth($this->MonthNumber, $this->Year)) {
                                    $dayNextShifts = $this->Days[$day_id]->Shifts;
                                    if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                        continue;
                                    else
                                        return false;
                                }
                                else
                                {
                                    $roleID = 1;
                                    $yearOfNextMonth= intval(date('Y', strtotime("+1 months", strtotime($dateOfMonth))));
                                    $numberNextOfMonth = intval(date('m', strtotime("+1 months", strtotime($dateOfMonth))));
                                    $numberOfNextDay = 1;
                                    $checkNextMonthIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                    if (!$checkNextMonthIsOnDb)
                                        continue;
                                    else
                                    {
                                        $calendarNextDay1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                        $dayNextShifts = $calendarNextDay1->Days[0]->Shifts;
                                        if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                            continue;
                                        else
                                            return false;
                                    }
                                }
                            }
                            else
                            {
                                //echo $numberOfDayBefore;
                                $calendarDayBefore1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberOfMonthBefore, $yearOfMonthBefore);
                                $dayBeforeShifts = $calendarDayBefore1->Days[$numberOfDayBefore - 1]->Shifts;
                                //print_r(get_class($calendarDayBefore1->Days[0]));
                                // ____2
                                if(Calendar::checkDayBefore($dayBeforeShifts, $enroledHours, $user)){
                                    if (!$this->Days[$day_id - 1]->IsLastDayOfMonth($this->MonthNumber, $this->Year)) {
                                        $dayNextShifts = $this->Days[$day_id]->Shifts;
                                        if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                            continue;
                                        else
                                            return false;
                                    }
                                    else
                                    {
                                        $roleID = 1;
                                        $yearOfNextMonth= intval(date('Y', strtotime("+1 months", strtotime($dateOfMonth))));
                                        $numberNextOfMonth = intval(date('m', strtotime("+1 months", strtotime($dateOfMonth))));
                                        $numberOfNextDay = 1;
                                        $checkNextMonthIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                        if (!$checkNextMonthIsOnDb)
                                            continue;
                                        else
                                        {
                                            $calendarNextDay1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                            $dayNextShifts = $calendarNextDay1->Days[0]->Shifts;
                                            if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                                continue;
                                            else
                                                return false;
                                        }
                                    }
                                }
                                    
                                else
                                    return false;
                                //End 2
                                
                            }
                        }
                        //_____1e
                    }
                }
            }     
            else if ($shift->EmployeesWorking == null && $shift->EmployeesVacation != null)
            {
                foreach ($shift->EmployeesVacation as $employee)
                {
                    if ($employee->user_id == $user->user_id)
                    {
                        return false;
                    }
                    else
                    {
                        continue 2;
                    }
                }
            }
            else
            //EmployeesWorking null and EmloyeesVacation null
            
            {
                // ____1
                
                if ($day_id != 1)
                {
                    $dayBeforeShifts = $this->Days[$day_id - 2]->Shifts;
                    // ____2
                    if (Calendar::checkDayBefore($dayBeforeShifts, $enroledHours, $user)) {
                        if (!$this->Days[$day_id - 1]->IsLastDayOfMonth($this->MonthNumber, $this->Year)) {
                            $dayNextShifts = $this->Days[$day_id]->Shifts;
                            if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                continue;
                            else
                                return false;
                        }
                        else
                        {
                            $roleID = 1;
                            $yearOfNextMonth= intval(date('Y', strtotime("+1 months", strtotime($dateOfMonth))));
                            $numberNextOfMonth = intval(date('m', strtotime("+1 months", strtotime($dateOfMonth))));
                            $numberOfNextDay = 1;
                            $checkNextMonthIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                            if (!$checkNextMonthIsOnDb)
                                continue;
                            else
                            {
                                $calendarNextDay1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                $dayNextShifts = $calendarNextDay1->Days[0]->Shifts;
                                if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                    continue;
                                else
                                    return false;
                            }
                        }
                    }
                    else
                        return false;
                    //End 2
                    
                }
                else
                {
                    $roleID = 1;
                    $yearOfMonthBefore = intval(date('Y', strtotime("-1 months", strtotime($dateOfMonth))));
                    $numberOfMonthBefore = intval(date('m', strtotime("-1 months", strtotime($dateOfMonth))));
                    $numberOfDayBefore = cal_days_in_month(CAL_GREGORIAN, $numberOfMonthBefore, $yearOfMonthBefore);
                    $checkMonthBeforeIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberOfMonthBefore, $yearOfMonthBefore);

                    if (!$checkMonthBeforeIsOnDb)
                    {
                        //Będzie trzeba napisać gdy zmiana koliduje z dniem następnym
                        //narazie
                        if (!$this->Days[$day_id - 1]->IsLastDayOfMonth($this->MonthNumber, $this->Year)) {
                            $dayNextShifts = $this->Days[$day_id]->Shifts;
                            if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                continue;
                            else
                                return false;
                        }
                        else
                        {
                            $roleID = 1;
                            $yearOfNextMonth= intval(date('Y', strtotime("+1 months", strtotime($dateOfMonth))));
                            $numberNextOfMonth = intval(date('m', strtotime("+1 months", strtotime($dateOfMonth))));
                            $numberOfNextDay = 1;
                            $checkNextMonthIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                            if (!$checkNextMonthIsOnDb)
                                continue;
                            else
                            {
                                $calendarNextDay1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                $dayNextShifts = $calendarNextDay1->Days[0]->Shifts;
                                if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                    continue;
                                else
                                    return false;
                            }
                        }
                    }
                    else
                    {
                        //echo $numberOfDayBefore;
                        $calendarDayBefore1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberOfMonthBefore, $yearOfMonthBefore);
                        $dayBeforeShifts = $calendarDayBefore1->Days[$numberOfDayBefore - 1]->Shifts;
                        //print_r(get_class($calendarDayBefore1->Days[0]));
                        // ____2
                        if(Calendar::checkDayBefore($dayBeforeShifts, $enroledHours, $user)){
                            if (!$this->Days[$day_id - 1]->IsLastDayOfMonth($this->MonthNumber, $this->Year)) {
                                $dayNextShifts = $this->Days[$day_id]->Shifts;
                                if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                    continue;
                                else
                                    return false;
                            }
                            else
                            {
                                $roleID = 1;
                                $yearOfNextMonth= intval(date('Y', strtotime("+1 months", strtotime($dateOfMonth))));
                                $numberNextOfMonth = intval(date('m', strtotime("+1 months", strtotime($dateOfMonth))));
                                $numberOfNextDay = 1;
                                $checkNextMonthIsOnDb = Calendar::ChceckCalendarInDb($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                if (!$checkNextMonthIsOnDb)
                                    continue;
                                else
                                {
                                    $calendarNextDay1 = Calendar::CreateWorkingCalendar($this->Department, $roleID, $numberNextOfMonth, $yearOfNextMonth);
                                    $dayNextShifts = $calendarNextDay1->Days[0]->Shifts;
                                    if (Calendar::checkNextDay($dayNextShifts, $enroledHours, $user))
                                        continue;
                                    else
                                        return false;
                                }
                            }
                        }
                            
                        else
                            return false;
                        //End 2
                        
                    }

                }

            }
            //_____1e
            
        }
        return true;

        // $currentDateString = "$_number_of_year-$_number_of_month-$currentDay 00:00:00";
        // $dateCurrentDay = new \DateTime($currentDateString);
        // $dateNextDay = clone $dateCurrentDay;
        // $dateNextDay->modify('+1 day');
        // $datePreviousDay = clone $dateCurrentDay;
        // $datePreviousDay->modify(('-1 day'));
        

        
    }


    function canVacationBeGrantToUserOnDay($user, $day_id)
    {
        //nie może gdy pracuje na danym dniu
        $currentShifts = $this->Days[$day_id - 1]->Shifts;
        foreach ($currentShifts as $shift) {
            $shift->CompleteHours();
            $employeesWorking = $shift->EmployeesWorking;
            $employeesVacationing = $shift->EmployeesVacation;
            foreach($employeesWorking as $em)
            {
                if($user->user_id == $em->user_id)
                {
                    return false;
                }
            }
            foreach($employeesVacationing as $em)
            {
                if($user->user_id == $em->user_id)
                {
                    return false;
                }
            }
            return true;
        }

    }
    function checkDayBefore($dayBeforeShifts,$enroledHours,$user,)
    {
        foreach ($dayBeforeShifts as $bshift)
                        {

                            $bshift->CompleteHours();
                            //DayBefore
                            $bHours = $bshift->GetIntArray_HoursOfShift();
                            //echo "[". 24-$enroledHours["StartHours"]."-". 24 -$bHours["EndHours"]."]";
                            //echo "(".$bHours["StartHours"]."<".$bHours["EndHours"].")";
                            //echo (24 / $enroledHours["StartHours"]) - (24 / $bHours["EndHours"]) > 11;
                            if ($bHours["StartHours"] < $bHours["EndHours"])
                            {
                                $x = 24 - $bHours["EndHours"];
                                $hourDifference = $enroledHours["StartHours"] + $x;
                            }
                            else
                            {
                                $hourDifference = $enroledHours["StartHours"] - $bHours["EndHours"];
                            }

                            if ($hourDifference > 11)
                            {
                                continue;
                            }
                            else if ($hourDifference == 11)
                            {
                                if ($enroledHours["StartMinutes"] >= $bHours["EndMinutes"])
                                {
                                    if (!empty($bshift->EmployeesWorking))
                                    {
                                        foreach ($bshift->EmployeesWorking as $empl)
                                        {
                                            if ($user->user_id == $empl->user_id)
                                            {
                                                return false;
                                            }
                                        }
                                    }
                                    continue;
                                }
                                else
                                {
                                    return false;
                                }
                            }
                            else
                            {
                                //echo "done";
                                //print_r($bshift);
                                if ($bshift->EmployeesWorking != null)
                                {
                                    foreach ($bshift->EmployeesWorking as $empl)
                                    {
                                        if ($user->user_id == $empl->user_id)
                                        {
                                            return false;
                                        }
                                    }
                                }
                                continue;
                            }
                        }
        return true;
                        //End 2
    }
    function checkNextDay($dayNextShifts,$enroledHours,$user)
    {
        foreach ($dayNextShifts as $nshift)
                        {

                            $nshift->CompleteHours();
                            //DayBefore
                            $nHours = $nshift->GetIntArray_HoursOfShift();
                            //echo "[". 24-$enroledHours["StartHours"]."-". 24 -$nHours["EndHours"]."]";
                            //echo "(".$nHours["StartHours"]."<".$nHours["EndHours"].")";
                            //echo (24 / $enroledHours["StartHours"]) - (24 / $nHours["EndHours"]) > 11;
                            if ($enroledHours["StartHours"] < $enroledHours["EndHours"])
                            {
                                $x = 24 - $enroledHours["EndHours"];
                                $hourDifference = $nHours["StartHours"] + $x;
                            }
                            else
                            {
                                $hourDifference = $nHours["StartHours"] - $enroledHours["EndHours"];
                            }

                            if ($hourDifference > 11)
                            {
                                continue;
                            }
                            else if ($hourDifference == 11)
                            {
                                if ($nHours["StartMinutes"] >= $enroledHours["EndMinutes"])
                                {
                                    if (!empty($nshift->EmployeesWorking))
                                    {
                                        foreach ($nshift->EmployeesWorking as $empl)
                                        {
                                            if ($user->user_id == $empl->user_id)
                                            {
                                                return false;
                                            }
                                        }
                                    }
                                    continue;
                                }
                                else
                                {
                                    return false;
                                }
                            }
                            else
                            {
                                //echo "done";
                                //print_r($nshift);
                                if ($nshift->EmployeesWorking != null)
                                {
                                    foreach ($nshift->EmployeesWorking as $empl)
                                    {
                                        if ($user->user_id == $empl->user_id)
                                        {
                                            return false;
                                        }
                                    }
                                }
                                continue;
                            }
                        }
        return true;
                        //End 2
    }
}



?>
