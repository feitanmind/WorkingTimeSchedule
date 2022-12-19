<?php
namespace App;
class Day
{
    public int $NumberOfDay;
    public int $Department;
    public $Shifts = array();

    function __construct($numberOfDay,$department)
    {
        $this->NumberOfDay = $numberOfDay;
        $this->Department = $department;
        //echo "DEPU".$department;
        $this->InitializeShifts($department);

    }
    //Tworzy zmiany ( w pracy ) w obiekcie Day na podstawie zmian zawartych w bazie danych
    private function InitializeShifts($depID)
    {
        //Połączenie z bazą danych
        $access_Connection = ConnectToDatabase::connAdminPass();
        //Polecenie SQL - pobranie z tabeli shifts wszystkich zmian dla danego działu / oddziału
        $sql_Query_Selection = "SELECT * FROM shifts WHERE dep_id = $depID;";
        //Przypisanie rezultatu z egzekucji zapytania do bazy danych
        $result_Of_Selection = $access_Connection ->query($sql_Query_Selection);
        //Tworzenie obiektów typu Shift i dodawanie ich do właściwości Shifts w obiekcie Day
        while($shift_From_Db = $result_Of_Selection ->fetch_assoc()) 
        {
            //Stworzenie obiektu
            $new_Shift = new Shift($shift_From_Db['id'], $shift_From_Db['dep_id']);
            //Dodawanie własciwości obiektu
            $new_Shift->Name = $shift_From_Db['name'];
            $new_Shift->Department = $shift_From_Db['dep_id'];
            $new_Shift->HoursPerShift = $shift_From_Db['hours_per_shift'];
            $new_Shift->StartHour = $shift_From_Db['startHour'];
            $new_Shift->EndHour = $shift_From_Db['endHour'];
            array_push($this->Shifts,$new_Shift);
        }

    }
    public  function IfUserWorkingOnThisDay($userId)
    {
        foreach($this->Shifts as $shift)
            {
                foreach($shift->EmployeesWorking as $emp)
                {
                    if($emp->user_id == $userId)
                    {
                        return [true, $shift->Id];
                    }
                    
                }
            }
        return false;
    }
    public function ActualizeShifts($sh)
    {
        foreach ($this->Shifts as $shift)
        {
            if($shift->Id == $sh->Id)
            {
                $shift->EmployeesWorking = $sh->EmployeesWorking;
                $shift->EmployeesVacation = $sh->EmployeesVacation;
            }
        }
    }
    public function IsFirstDayOfMonth()
    {
        if ($this->NumberOfDay == 1)
            return true;
        return false;
    }
    public function IsLastDayOfMonth($month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if ($this->NumberOfDay == $daysInMonth)
            return true;
        return false;
    }
}