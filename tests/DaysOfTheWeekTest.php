<?php

class DaysOfTheWeekTest extends \PHPUnit\Framework\TestCase {
    // Test czy dobrze wylicza odstęp między poniedziałkiem a pierwszym dniem miesiąca 
    public function testCalculateDaysFromMondayInJuly2022(){
        $calculator = new App\DaysOfTheWeek;
        $test = date("l",mktime(0,0,0,7,1,2022));
        $result = $calculator->calculateDaysFromMonday($test);
        $this->assertEquals(4, $result);
    }
}