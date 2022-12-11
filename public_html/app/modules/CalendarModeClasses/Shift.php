<?php
namespace App;
class Shift
{
    public int $Id;
    public string $Name;
    public string $Department;
    public float $HoursPerShift;
    public string $StartHour;
    public string $EndHour;
    public $EmployeesWorking = array();
    public $EmployeesVacation = array();

    //Shift($row['name'],$row['dep_id'],$row['hours_per_shift'],$row['startHour'],$row['endHour']))
    public function __construct($id, $depId)
    {
        $this->Id = $id;
        // $this->Name = $name;
        //echo "DEPS".$depId;
        $this->Department = $depId;
        // $this->HoursPerShift = $hoursPerShift;
        // $this->StartHour = $startHour;
        // $this->EndHour = $endHour;

    }
    public function CompleteHours()
    {
        $department_ID = 1;
        $shiftId = $this->Id;
        $access_Connection = ConnectToDatabase::connAdminPass();
        //Polecenie SQL do wybrania wszytskich zmian z tabeli shifts dla konkretnego działu
        $sql_Query_Selection = "SELECT * FROM shifts WHERE dep_id = $department_ID AND id = $shiftId;";
        //Przypisanie rezultatu wykonania zapytania do bazy danych
        $result_Of_Selection = $access_Connection->query($sql_Query_Selection);

        while($row = $result_Of_Selection->fetch_assoc())
        {
            $this->StartHour = $row['startHour'];
            $this->EndHour = $row['endHour'];
        }
    }
    public function AddUserToWork(User $user)
    {
        array_push($this->EmployeesWorking, $user); 
    }
    public function AddUserVacation(User $user)
    {
        array_push($this->EmployeesVacation, $user);
    }

    //Funcja Generuje formularz dzięki któremu będzie można wybrać konkretną zmianę dla której ustalamy grafik
    public static function GenerateFormSelectForShifts(string $department_ID)
    {
        //Połączenie z bazą danych
        $access_Connection = ConnectToDatabase::connAdminPass();
        //Polecenie SQL do wybrania wszytskich zmian z tabeli shifts dla konkretnego działu
        $sql_Query_Selection = "SELECT * FROM shifts WHERE dep_id = $department_ID;";
        //Przypisanie rezultatu wykonania zapytania do bazy danych
        $result_Of_Selection = $access_Connection->query($sql_Query_Selection);
        //Generowanie formularza w HTMLu
        echo '<form method="post">';
        echo '<select font-size: 1vw" name="shiftID">';
        while($row = $result_Of_Selection->fetch_assoc())
        {   
            //Stworzenie opcji które po wybraniu wysyłają cały formularz            
            echo '<option onclick="this.form.submit();" value='.$row['id'].'>'.$row['startHour'].'-'.$row['endHour'].'<i> ('.$row['name'].')</i></option>';
        }
        echo '</select>';
        echo '</form>';        
    }


}




?>