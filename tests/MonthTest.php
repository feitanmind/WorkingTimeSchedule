<?php
    class MonthTest extends \PHPUnit\Framework\TestCase
    {
        public function testReturnNameOfMonth()
        {
            $month = new App\Month(7);
            $seventhMonth = $month -> getName(7);
            $this -> assertEquals('July',$seventhMonth);
        }
    }




?>