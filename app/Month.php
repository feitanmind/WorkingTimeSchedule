<?php
namespace App;
 class Month
 {
    private string $name;
    private int $numberInYear;
    private int $numberOfDays;
    private int $workingDays;
    private float $hoursToBeWorked;
    private $nameOfMonths = array('January',
                                    'February',
                                    'March',
                                    'April',
                                    'May',
                                    'June',
                                    'July',
                                    'August',
                                    'September',
                                    'October',
                                    'November',
                                    'December');
    
    function getName()
    {
        $numberInYear = $this->numberInYear;
        if($numberInYear > 12 || $numberInYear < 1){
            throw new Exception('Invalid Month Number');
        }else{
            return $this -> nameOfMonths[$numberInYear-1];
        }
    }
    function __construct(int $numberInYear, int $year = 1990) 
    {
        $this-> numberInYear = $numberInYear;
    }

 }

?>