<?php
namespace App;
class Shift
{
    public int $Id;
    public string $Name;
    public string $Department;
    public string $HoursPerShift;
    public string $StartHour;
    public string $EndHour;
    public string $Color;
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
        $this->CompleteHours();
    }
    public function CompleteHours()
    {
        $department_ID = $_SESSION['Current_User_Department_Id'];
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
            $this->HoursPerShift = $row["hours_per_shift"];
            $this->Color = $row["color"];
        }
    }


    public function GetIntArray_HoursOfShift()
    {
        $hoursOfShift = array("StartHours"=>0,"StartMinutes"=>0,"EndHours"=>0,"EndMinutes"=>0);
        if (strpos($this->EndHour, ":")) {
            $hoursOfShift["EndHours"] = intval(substr($this->EndHour, 0, strpos($this->EndHour, ":")));
            $hoursOfShift["EndMinutes"] = intval(substr($this->EndHour, strpos($this->EndHour, ":")));
        }
        else
        {
            $hoursOfShift["EndHours"] = intval($this->EndHour);
        }
        if (strpos($this->StartHour, ":")) {
            $hoursOfShift["StartHours"] = intval(substr($this->StartHour, 0, strpos($this->StartHour, ":")));
            $hoursOfShift["EndMinutes"] = intval(substr($this->StartHour, strpos($this->StartHour, ":")));
        }
        else
        {
            $hoursOfShift["StartHours"] = intval($this->StartHour);
        }
        return $hoursOfShift;

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
        echo '<form class="selectShift" method="post">';
        echo "<p style=\"font-size: 0.7vw;\">Select shift:</p>";
        echo '<select class="calendarFilterSelect" name="shiftID">';
        echo '<option onclick="this.form.submit();" selected="selected" value="all">All</option>';
        while($row = $result_Of_Selection->fetch_assoc())
        {   
            if($_SESSION["Shift_Id"] == $row["id"])
            {
                echo '<option onclick="this.form.submit();" selected="selected" value='.$row['id'].'>'.$row['startHour'].'-'.$row['endHour'].'<i> ('.$row['name'].')</i></option>';
            }
            else
            {
                echo '<option onclick="this.form.submit();" value='.$row['id'].'>'.$row['startHour'].'-'.$row['endHour'].'<i> ('.$row['name'].')</i></option>';
            }
            //Stworzenie opcji które po wybraniu wysyłają cały formularz            
            
        }
        echo '</select>';
        echo '</form>';        
    }


}




?>