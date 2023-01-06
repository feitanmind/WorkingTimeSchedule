<?php
namespace App;
        if(isset($_POST["CALENDAR_SAVE"]))
        {
            if($_POST["CALENDAR_SAVE"] == "YES")
            {
                $departmentIdentifier = $_SESSION['Current_User_Department_Id'];
                $roleIdentifier = $_SESSION['Role_Id'];
                $monthNumber = $_SESSION['Month_Number'];
                $yearNumber = $_SESSION['Year_Number'];
                $encodedCalendar = json_decode($_SESSION['calendar']);
                $decodedCalendar = Calendar::DecodeJsonCalendar($monthNumber,$yearNumber,$departmentIdentifier,$encodedCalendar);
                $decodedCalendar->PushCalendarToDataBase($roleIdentifier);
                HoursOfWork::PushHoursOfWorkArrayIntoDatabase();
                $_POST["CALENDAR_SAVE"] = "none";
            }


        }
?>