<?php
declare(strict_types=1);


final class CalendarTest extends \PHPUnit\Framework\TestCase
{
    private static $NumberOfYear,
    $DepartmentId,
    $NumberOfMonth,
    $DayIdInCalendar,
    $ShiftId,
    $TestUser;

    public static function setUpBeforeClass(): void
    {
        self::$NumberOfYear = 2022;
        self::$DepartmentId = 1;
        self::$NumberOfMonth = 1;
        self::$DayIdInCalendar = 2;
        self::$ShiftId = 1;
        self::$TestUser = new App\User(1);
    }

    public static function tearDownAfterClass(): void
    {
        self::$NumberOfYear = null;
        self::$DepartmentId = null;
        self::$NumberOfMonth = null;
        self::$DayIdInCalendar = null;
        self::$ShiftId = null;
        self::$TestUser = null;

    }
    public function test_ShouldReturnNameOfUserWhenSignUserBeforeToWorking()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserToWorkInDay(self::$TestUser, self::$DayIdInCalendar, self::$ShiftId);
        $this->assertEquals($calendar->Days[1]->Shifts[0]->EmployeesWorking[0]->name, 'Adam');
    }
    public function test_ShuldReturnEmptyArrayWhenDeleteLastUserFromShift()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserToWorkInDay(self::$TestUser, self::$DayIdInCalendar, self::$ShiftId);
        $calendar->UnsignWorkingUserFormDay(self::$TestUser, self::$DayIdInCalendar, self::$ShiftId);  
        $this->assertEmpty($calendar->Days[1]->Shifts[0]->EmployeesWorking);
    }
    public function test_ShouldReturnEncodedObjectInJsonFormat()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $jsonCalendar = json_encode($calendar);
        $this->assertIsString($jsonCalendar);
        $this->assertEquals($this->assertStringStartsWith("{", $jsonCalendar),$this->assertStringEndsWith("}",$jsonCalendar));

    }
    /**
     * @group CanUserBeSignOnDay
     */
    public function test_ShouldReturnTrueWhenUserCanBeSignOnDay()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, self::$DayIdInCalendar,self::$ShiftId);
        $this->assertTrue($CanBeSign);
    }
    public function test_ShouldReturnFalseWhenUserWasSignedOnCurrentDay()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserToWorkInDay(self::$TestUser, self::$DayIdInCalendar, self::$ShiftId);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, self::$DayIdInCalendar,self::$ShiftId);
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFlaseWhenUserWasOnVacation()
    {
        $dayNumber = self::$DayIdInCalendar+2;
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserVacation(self::$TestUser,$dayNumber, self::$ShiftId);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, $dayNumber,self::$ShiftId);
        $this->assertFalse($CanBeSign);
    }
    // public function test_ShouldReturnTrueWhenDayIsTheFirstDayInMonth()
    // {

    // }
}



?>