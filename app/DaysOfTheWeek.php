<?php
namespace App;
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    
    class DaysOfTheWeek
    {
        public const NUMBER_DAYS_IN_THE_WEEK = 7;
        public $days = array(
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Staurday",
            "Sunday"
        );
        //Wylicza dni które dzielą poniedziałek od pierwszego dnia miesiąca
        public function calculateDaysFromMonday($nameFirstDayOfMonth)
        {
            return array_search($nameFirstDayOfMonth, $this->days);
        }
    }
    ?>