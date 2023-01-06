<?php
class DayTest extends \PHPUnit\Framework\TestCase
{
    function test_ShouldReturnTrueWhenDayIsFirstDayInMonth()
    {
        $day = new App\Day(1, 1);
        $isFirstDayOfMonth = $day->IsFirstDayOfMonth();
        $this->assertTrue($isFirstDayOfMonth);
    }
    function test_ShouldReturnFalseWhenDayIsntFirstDayOfMonth()
    {
        $day = new App\Day(2, 1);
        $isFirstDayOfMonth = $day->IsFirstDayOfMonth();
        $this->assertFalse($isFirstDayOfMonth);
    }
    function test_ShouldReturnTrueWhenDayIsLastDayInMonth()
    {
        $day = new App\Day(31, 1);
        $isLastDayOfMonth = $day->IsLastDayOfMonth(1, 2022);
        $this->assertTrue($isLastDayOfMonth);
    }
    function test_ShouldReturnFalseWhenDayIsntLastDayInMonth()
    {
        $day = new App\Day(30, 1);
        $isLastDayOfMonth = $day->IsLastDayOfMonth(1, 2022);
        $this->assertFalse($isLastDayOfMonth);
    }
}



?>