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
            array_push($this->Shifts, new Shift($row['id'],$row['name'],$row['dep_id'],$row['hours_per_shift'],$row['startHour'],$row['endHour']));
        }
        $result->free();
        unset($conn);
    }
}