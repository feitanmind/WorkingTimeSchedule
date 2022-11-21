<?php
namespace App;
class Shift
{


    public static function displaySelectShiftsForUser(string $depID)
    {
        $shiftsArray = array();

        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $sqlSelectAllRoles = "SELECT * FROM shifts WHERE dep_id = $depID;";
        $result = $mysqliAdm->query($sqlSelectAllRoles);
        
        echo '<select name="shiftID">';
        while($row = $result->fetch_assoc())
        {               
            echo '<option onclick="this.form.submit();" value='.$row['id'].'>'.$row['startHour'].'-'.$row['endHour'].'<i> ('.$row['name'].')</i></option>';
        }
        echo '</select>';
        $result->free();
        unset($conn);
        
    }


}




?>