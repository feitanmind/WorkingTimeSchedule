<?php
namespace App;
use \Exception as Ex;
        if(isset($_POST["CALENDAR_SAVE"]))
        {
            if($_POST["CALENDAR_SAVE"] == "YES")
            {
                try
                {
                    $departmentIdentifier = $_SESSION['Current_User_Department_Id'];
                    $roleIdentifier = $_SESSION['Role_Id'];
                    $monthNumber = $_SESSION['Month_Number'];
                    $yearNumber = $_SESSION['Year_Number'];
                    $encodedCalendar = json_decode($_SESSION['calendar']);
                    $decodedCalendar = Calendar::DecodeJsonCalendar($monthNumber,$yearNumber,$departmentIdentifier,$encodedCalendar);
                    $decodedCalendar->PushCalendarToDataBase($roleIdentifier);
                    HoursOfWork::PushHoursOfWorkArrayIntoDatabase();
                    $_SESSION['Module'] = 1;
                    $_SESSION['IsCalendarSave'] = 'yes';
                    $_POST["CALENDAR_SAVE"] = "none";
                }
                catch(Ex $e)
                {
                    $xmlFile = fopen("templatesNotification.xml", "r");
                    $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
                    echo "<script>";
                        echo 'window.history.pushState({}, document.title, "/" + "app/");';
                        echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Error,SubjectNotification.CantSaveCalendar);";
                    echo "</script>";
                }

            }


        }
?>