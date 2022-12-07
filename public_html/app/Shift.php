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
    public function __construct($id, $name, $depId, $hoursPerShift, $startHour, $endHour)
    {
        $this->Id = $id;
        $this->Name = $name;
        $this->Department = $depId;
        $this->HoursPerShift = $hoursPerShift;
        $this->StartHour = $startHour;
        $this->EndHour = $endHour;
    }
    public function AddUserToWork(User $user)
    {
        array_push($this->EmployeesWorking, $user); 
    }
    public function AddUserVacation(User $user)
    {
        array_push($this->EmployeesVacation, $user);
    }

    public static function displaySelectShiftsForUser(string $depID)
    {
        $shiftsArray = array();

        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $sqlSelectAllRoles = "SELECT * FROM shifts WHERE dep_id = $depID;";
        $result = $mysqliAdm->query($sqlSelectAllRoles);
        echo '<form method="post">';
        echo '<select font-size: 1vw" name="shiftID">';
        while($row = $result->fetch_assoc())
        {               
            echo '<option onclick="this.form.submit();" value='.$row['id'].'>'.$row['startHour'].'-'.$row['endHour'].'<i> ('.$row['name'].')</i></option>';
        }
        echo '</select>';
        echo '</form>';
        $result->free();
        unset($conn);
        
    }


}




?>