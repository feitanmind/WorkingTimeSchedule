<?php
namespace App;
use \Exception as Ex;
if(isset($_POST['nameOfShift_addShift']))
{
    
    $name = $_POST['nameOfShift_addShift'];
    $startTime = $_POST['startHour_addShift'];
    $endTime = $_POST['endHour_addShift'];

    $startHoursNoFormat = substr($startTime,0,strpos($startTime, ":"));
    $startHours = str_starts_with($startHoursNoFormat,"0") ? substr($startHoursNoFormat,1) : $startHoursNoFormat;

    $startMinutesNoFormat = substr($startTime,strpos($startTime, ":")+1);
    $startMinutes = str_starts_with($startMinutesNoFormat,"0") ? substr($startMinutesNoFormat,1) : $startMinutesNoFormat;

    $endHoursNoFormat = substr($endTime,0,strpos($endTime, ":"));
    $endHours = str_starts_with($endHoursNoFormat,"0") ? substr($endHoursNoFormat,1) : $endHoursNoFormat;

    $endMinutesNoFormat = substr($endTime,strpos($endTime, ":")+1);
    $endMinutes = str_starts_with($endMinutesNoFormat,"0") ? substr($endMinutesNoFormat,1) : $endMinutesNoFormat;

    if($endHours < $startHours)
    {
        $finalHours = 24 - $startHours + $endHours;
        if($endMinutes < $startMinutes)
        { 
            $finalMinutes = 60 - ($startMinutes - $endMinutes);
            $finalHours--;
        }
        else
        {
            $finalMinutes = $endMinutes - $startMinutes;
        }
    }
    else
    {
        $finalHours = $endHours - $startHours;
        if($endMinutes < $startMinutes)
        { 
            $finalMinutes = 60 - ($startMinutes - $endMinutes);
            $finalHours--;
        }
        else
        {
            $finalMinutes = $endMinutes - $startMinutes;
        }
    }

    $finalHoursTime = strlen($finalHours) == 1 ? "0".$finalHours : $finalHours;
    $finalMinutesTime = strlen($finalMinutes) == 1 ? "0".$finalMinutes : $finalMinutes;

    $finalTime = $finalHoursTime . ":" . $finalMinutesTime . ":00"; 
    $colors = array("#e60049", "#0bb4ff", "#50e991", "#e6d800", "#9b19f5", "#ffa300", "#dc0ab4", "#b3d4ff", "#00bfa0");
    try
    {
        $authorizedConnection = ConnectToDatabase::connAdminPass();
        $depId = isset($_POST['departmentId_addShift']) ? $_POST['departmentId_addShift'] : $_SESSION['Current_User_Department_Id'];
        $sqlCol = "SELECT COUNT(id) AS colorIndex FROM shifts WHERE dep_id = $depId;";
        $result = $authorizedConnection->query($sqlCol);
        $row = $result->fetch_assoc();
        $colorIndex = $row['colorIndex'];
        $color = $colors[$colorIndex];
   
        $sql = "INSERT INTO shifts(name, dep_id, hours_per_shift, startHour, endHour, color) VALUES('$name',$depId, '$finalTime', '$startTime', '$endTime','$color');";
        $authorizedConnection->query($sql);
        $selectShift = "SELECT id FROM shifts WHERE dep_id = $depId;";
        $resShifts = $authorizedConnection->query($selectShift);
        $rowShifts = $resShifts->fetch_assoc();
        $_SESSION['Shift_Id'] = $rowShifts['id'];
        $_SESSION['Module'] = 1;
    }
    catch (Ex $e)
    {
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Error,SubjectNotification.AddShiftFailed);";
        echo "</script>";

        $_SESSION['Module'] = 4;
    }
   
}

?>