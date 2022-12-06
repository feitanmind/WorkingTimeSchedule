<?php
namespace App;
class HoursOfWork
{
    public $User;
    public int $Month;
    public int $Year;
    public $Hours;

    public function __construct($user, $month, $year, $hours)
    {
        $this->Hours = date('H:i',mktime(7, 35, 0, 1, 1, 2000));
    }
}



?>