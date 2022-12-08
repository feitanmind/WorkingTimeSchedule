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
        $this->CreateShiftsInDay($department);

    }

    private function CreateShiftsInDay($depID)
    {
       
            $conn = new ConnectToDatabase;
            $mysqliAdm = $conn -> connAdminPass();
            $sql_SelectAllShifts = "SELECT * FROM shifts WHERE dep_id = $depID;";
            $result = $mysqliAdm->query($sql_SelectAllShifts);
            while($row = $result->fetch_assoc()) 
            {
                $shift = new Shift($row['id'], $row['dep_id']);
                $shift->Name = $row['name'];
                $shift->Department =$row['dep_id'];
                $shift->HoursPerShift = $row['hours_per_shift'];
                $shift->StartHour =$row['startHour'];
                $shift->EndHour = $row['endHour'];
                array_push($this->Shifts,$shift);
            }
            $result->free();
            unset($conn);
        
        
    }
    public function ActualizeShifts($sh)
    {
        foreach ($this->Shifts as $shift)
        {
            if($shift->Id == $sh->Id)
            {
                $shift->EmployeesWorking = $sh->EmployeesWorking;
                $shift->EmployeesVacation = $sh->EmployeesWorking;
            }
        }
    }
}