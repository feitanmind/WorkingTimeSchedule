<?php
    class MonthTest extends \PHPUnit\Framework\TestCase
    {
        public function test_ReturnNameOfMonth()
        {
            $month = new App\Month(7);
            $seventhMonth = $month -> getName(7);
            $this -> assertEquals('July',$seventhMonth);
        }

        public function test_ShouldReturnFalseWhenMonthNotExstInDatabase()
        {
            $month = new App\Month(7,2000);
            $this->assertEquals(false,$month->checkMonthInDatabase());
        }
        public function test_ShouldReturnTrueWhenMonthExistsInDB()
        {
            $month = new App\Month(9,2012);
            $this->assertEquals(true,$month->checkMonthInDatabase());
        }

        public function test_ShouldAddMonthInDatabase()
        {
            $month = new App\Month(9,2021);
            $this->assertEquals(true,$month->createMonthInDatabase());
        }

    }




?>