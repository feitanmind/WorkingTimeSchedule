<?php
namespace App;
class Statistics
{
    public User $user;
    public Array $ShiftsForUserInCurrentMonth;
    public $HoursLeftToSign;
    
    public function __construct($userId)
    {
        $user = new User($userId);
        $encodedArrayOfHoursOfWork = $_SESSION['arrayOfHoursOfWorkForCurrentMonth'];
        $hoursOfWork = HoursOfWork::decodeArrayOfHoursOfWork($encodedArrayOfHoursOfWork);
        foreach($hoursOfWork as $how)
        {
            if($how->User->user_id == $userId)
            {
                $this->HoursLeftToSign = $how->Hours;
                break;
            }
        }
        $monthNumber = $_SESSION['Month_Number'];
        $yearNumber = $_SESSION['Year_Number'];
        $departmentId =  $user->dep_id;
        $encodedCalendar = $_SESSION['calendar'];
        $decodedCalendarAsStd = json_decode($encodedCalendar);
        $calendar = $encodedCalendar->DecodeJsonCalendar($monthNumber, $yearNumber, $departmentId, $decodedCalendarAsStd);
    }


    public static function DrawMiniCalendarForUserStatistics($user)
    {
        $month_Number = $_SESSION['Month_Number'];
        $year = $_SESSION['Year_Number'];
        $department_ID = $user->dep_id;
        $decodedCalendarStd = json_decode($_SESSION['calendar']);
        $calendar = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $decodedCalendarStd);
        $daysInUserWorked = array();
        
        foreach($calendar->Days as $days)
        {
            $shiftsInUserWorked = array();
            $working = false;
            foreach($days->Shifts as $shift)
            {
                foreach($shift->EmployeesWorking as $emp)
                {
                    if($emp->user_id == $user->user_id)
                    {
                        return [true, $shift->Id];
                    }
                    
                }
                array_push($shiftsInUserWorked, [$shift->Id, $working]);
                $working = false;
               
            }
            array_push($daysInUserWorked, $shiftsInUserWorked);
            
        }

    }


}
?>