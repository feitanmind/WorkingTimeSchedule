<?php
namespace App;
use DateTIme;
use DatePeriod;
use DateInterval;
class HoursOfWork
{
    public $User;
    public int $Month;
    public int $Year;
    public $Time;
    public $Hours;

    public function __construct($user, $month, $year, $jobHours)
    {
        $this->User = $user;
        $this->Month = $month;
        $this->Year = $year;
        $resTime = $this->SetNewTimeOfWork($month,$year,$jobHours);
        $this->Time = $resTime[1];
        //echo "TimeOfWork: $resTime[0]";
        $this->Hours = $resTime [0];

    }
    //Funkcja odejmująca konkretną ilość godzin od czasu pracy
    // public function SubstractTimeOfWork($hours)
    // {
    //     $this->Time->modify("-$hours hours");
    //     $this->Hours = $this->ChangeTimeToHours($this->Time);

    // }
    public function SubstractTimeOfWork()
    {
        $currentShift = $_SESSION['Shift_Id'];
        $accessConnection = ConnectToDatabase::connAdminPass();
        $sql = "SELECT hours_per_shift FROM shifts WHERE id = $currentShift";
        $result = $accessConnection->query($sql);
        $row = $result->fetch_assoc();
        $hoursPerShift = $row['hours_per_shift'];
        $hoursToSubstract = substr($hoursPerShift, 0, strpos($hoursPerShift, ":"));
        if (str_starts_with($hoursToSubstract, 0))
            $hoursToSubstract = substr($hoursToSubstract, 1);
        $minutesToSubstract = substr($hoursPerShift, strpos($hoursPerShift, ":") + 1);
        if (str_starts_with($minutesToSubstract,0))
            $minutesToSubstract = substr($minutesToSubstract,1);
        $minutesToSubstract = substr($minutesToSubstract, 0, strpos($minutesToSubstract, ":"));


        $this->Time->modify("-$minutesToSubstract minutes");
        $this->Time->modify("-$hoursToSubstract hours");
        $this->Hours = $this->ChangeTimeToHours($this->Time);

    }
    //Funkcja dodająca konkretną ilośc do czasu pracy
    public function AddTimeOfWork()
    {
        $currentShift = $_SESSION['Shift_Id'];
        $accessConnection = ConnectToDatabase::connAdminPass();
        $sql = "SELECT hours_per_shift FROM shifts WHERE id = $currentShift";
        $result = $accessConnection->query($sql);
        $row = $result->fetch_assoc();
        $hoursPerShift = $row['hours_per_shift'];
        $hoursToAdd = substr($hoursPerShift, 0, strpos($hoursPerShift, ":"));
        if (str_starts_with($hoursToAdd, 0))
            $hoursToAdd = substr($hoursToAdd, 1);
        $minutesToAdd = substr($hoursPerShift, strpos($hoursPerShift, ":") + 1);
        if (str_starts_with($minutesToAdd,0))
            $minutesToAdd = substr($minutesToAdd,1);
        $minutesToAdd = substr($minutesToAdd, 0, strpos($minutesToAdd, ":"));


        $this->Time->modify("+$minutesToAdd minutes");
        $this->Time->modify("+$hoursToAdd hours");
        $this->Hours = $this->ChangeTimeToHours($this->Time);

    }
    public function ChangeTimeToHours($time)
    {
        $zero    = new DateTime("@0");
        $offset  = $time;
        $diff    = $zero->diff($offset);
        $diffHours = $diff->days * 24 + $diff->h;
        $diffMinutes = $diff->i;
        if ($diffHours < 10) $diffHours = "0" . $diffHours;
        if ($diffMinutes < 10) $diffMinutes = "0" . $diffMinutes;
        $resTime = $diffHours . ":". $diffMinutes;
        return $resTime;
    }
    public function ActualizeTimeAndHours($hoursOfWork)
    {
       // echo $hoursOfWork . "<br>";
        $newHours = substr($hoursOfWork,0, strpos($hoursOfWork, ":"));
        $newMinutes = substr($hoursOfWork, strpos($hoursOfWork, ":")+1);
        //echo $newMinutes . '<br>';
        if (str_starts_with($newHours, "0"))$newHours = substr($newHours, 1);
        if (str_starts_with($newMinutes, "0"))$newMinutes = substr($newMinutes, 1);
        //echo $newMinutes . '<br>';
        $allOfSeconds = intval($newHours) * 3600 + intval($newMinutes) * 60;
       // echo intval($newHours) . "* 3600 +" . intval($newMinutes) . "*60";
        $time = new DateTime("@$allOfSeconds");
        $hoursOfWork = HoursOfWork::ChangeTimeToHours($time);
        $this->Hours = $hoursOfWork;
        $this->Time = $time;

    }
    
    public function SetNewTimeOfWork($month,$year,$jobHours)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $sec = strtotime("1970-01-01 $jobHours UTC");
        $timeOfWork = $sec * HoursOfWork::GetWorkingDaysInMonth($year, $month);
        $zero    = new DateTime("@0");
        $offset  = new DateTime("@$timeOfWork");
        $diff    = $zero->diff($offset);
        $diffHours = $diff->days * 24 + $diff->h;
        $diffMinutes = $diff->i;
        if ($diffHours < 10) $diffHours = "0" . $diffHours;
        if ($diffMinutes < 10) $diffMinutes = "0" . $diffMinutes;
        $resTime = $diffHours . ":". $diffMinutes;
        return [$resTime, $offset];
    }
    public static function GetWorkingDaysInMonth($year,$month)
    {
        //dni pracujace
        $buisnessDays = [1,2,3,4,5];
        $saturdays = [6];
        //<region>Define Holidays Off Work
            $easter = HoursOfWork::GetEasterDate($year);
            // pierwszy dzień wielkanocy = wielkanoc + 1
            $easterSec = date('Y-m-d', strtotime($easter . '+ 1 days'));
            //zielone świątki = wielkanoc + 50
            $zieloneSwiatki = date('Y-m-d', strtotime($easter . '+ 50 days'));
            //boże ciało = wielkanoc + 60
            $bozeCialo = date('Y-m-d', strtotime($easter . '+ 60 days'));
        //</region>
        $exceptDays = ['*-01-01','*-01-06','*-05-01','*-05-03','*-08-15','*-11-01','*-11-11','*-12-25','*-12-26', $easter, $easterSec, $zieloneSwiatki, $bozeCialo];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        //Chcemy sprawdzić od pierwszego dnia miesiąca
        $dateFrom = new DateTime("01-$month-$year");
        //Do ostatniego dnia miesiąca
        $dateTo = new DateTime("$daysInMonth-$month-$year");
        //Dodajemy jeden dzień by ustawić warunek końcowy dla pętli foreach
        $dateTo->modify('+1 day');
        //Ustawiamy interwał liczenia - chcemy liczyć po jednym dniu
        $countInterval = new DateInterval('P1D');
        //Ustawiamy przedział po których chcemy iterować 
        $monthPeriod = new DatePeriod($dateFrom,$countInterval,$dateTo);

        //Ustawiamy zmienną zliczajacą dni
        $count = 0;
        //Zliczamy dni
        foreach ($monthPeriod as $day)
        {

            if (in_array($day->format('Y-m-d'), $exceptDays) && $day->format('N') == 6 || in_array($day->format('*-m-d'),$exceptDays) && $day->format('N') == 6) {
                $count--;
                continue;       
            }    
            if(!in_array($day->format('N'),$buisnessDays))  continue;

            if (in_array($day->format('Y-m-d'), $exceptDays) || in_array($day->format('*-m-d'), $exceptDays)) continue;
                
            
            $count++;
        }
        return $count;


    }

    public static function GetEasterDate($year)
    {
       //Metoda Meeusa/Jonesa/Butchera
       $a = $year % 19;
       $b = floor($year / 100);
       $c = $year % 100;
       $d = floor($b / 4);
       $e = $b % 4;
       $f = floor(($b+8)/25);
       $g = floor(($b - $f + 1) / 3);
       $h = (19 * $a + $b - $d - $g + 15) % 30;
       $i = floor($c / 4);
       $k = $c % 4;
       $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
       $m = floor(($a + 11 * $h + 22 * $l) / 451);
       $p = ($h + $l - 7 * $m + 114) % 31;
       $dayOfEastern = $p + 1;
       $monthOfEastern = floor(($h + $l -7 * $m + 114) / 31);
       $re = "$year-$monthOfEastern-$dayOfEastern";
       return $re;
    }
}



?>