<?php
declare(strict_types=1);

use App\Calendar;
use PHPUnit\Framework\TestCase;

final class CalendarTest extends TestCase
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
    public function test_ShouldReturnFalseWhenUserWasSignedOnCurrentDayOnSameShift()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserToWorkInDay(self::$TestUser, self::$DayIdInCalendar, self::$ShiftId);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, self::$DayIdInCalendar,self::$ShiftId);
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFalseWhenUserWasSignedOnCurrentDayOnOtherShift()
    {
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserToWorkInDay(self::$TestUser, self::$DayIdInCalendar, self::$ShiftId + 1);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, self::$DayIdInCalendar,self::$ShiftId);
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFlaseWhenUserWasOnVacationOnSameShiftInCurrentDay()
    {
        $dayNumber = self::$DayIdInCalendar+2;
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserVacation(self::$TestUser,$dayNumber, self::$ShiftId);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, $dayNumber,self::$ShiftId);
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFlaseWhenUserWasOnVacationOnOtherShiftInCurrentDay()
    {
        $dayNumber = self::$DayIdInCalendar+2;
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);
        $calendar->SignUserVacation(self::$TestUser,$dayNumber, self::$ShiftId+1);
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, $dayNumber,self::$ShiftId);
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFalseWhenUserWasSignedOnDayBeforeOnCollidingShift()
    {
        $dayNumber = 5;
        $dayBeforeNumber = $dayNumber - 1;
        $shiftCurrent = 1; // 7 - 15
        $shiftCollising = 3; //19 - 7
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);       
        $calendar->SignUserToWorkInDay(self::$TestUser,$dayBeforeNumber, $shiftCollising);
        // $debug = $calendar->Days[3]->Shifts[2]->EmployeesWorking[0]->name;
        // fwrite(STDERR, print_r($debug, TRUE)); 
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, $dayNumber,$shiftCurrent); 
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFalseWhenUserWasSignedOnDayBeforeOnCollidingShiftOnOtherMonth()
    {
        $dayNumber = 1;
        $dayBeforeNumber = 31;
        $shiftCurrent = 1;
        $shiftCollising = 3;
        $monthBefore = 12; // grudzień
        $yearBefore = self::$NumberOfYear - 1; // 2021
        //(STYCZEŃ 2022)
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);  
        // (GRUDZIEŃ 2021)
        $calendarBefore = App\Calendar::CreateWorkingCalendar(self::$DepartmentId, 1, $monthBefore, $yearBefore);
             
        //$calendarBefore->SignUserToWorkInDay(self::$TestUser,$dayBeforeNumber, $shiftCollising);
        // $debug = $calendarBefore->Days[30]->Shifts[2]->EmployeesWorking[0]->name;
        // fwrite(STDERR, print_r($debug, TRUE)); 
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, $dayNumber,$shiftCurrent);
        $this->assertFalse($CanBeSign);
    }
    public function test_ShouldReturnFalseWhenUserWasSignedOnNextDayOnCollidingShiftOnOtherMonth()
    {
        $dayNumber = 31;
        $nexDayNumber = 1;
        $shiftCurrent = 3;
        $shiftCollising = 1;
        $nextMonth = 2; // grudzień
        //(STYCZEŃ 2022)
        $calendar = new App\Calendar(self::$NumberOfMonth, self::$NumberOfYear, self::$DepartmentId);  
        // (LUTY 2022)
        $calendarNext = App\Calendar::CreateWorkingCalendar(self::$DepartmentId, 1, $nextMonth, self::$NumberOfYear);
        // $debug = $calendarBefore->Days[30]->Shifts[2]->EmployeesWorking[0]->name;
        // fwrite(STDERR, print_r($debug, TRUE)); 
        $CanBeSign = $calendar->CanUserBeSignOnDay(self::$TestUser, $dayNumber,$shiftCurrent);
        $this->assertFalse($CanBeSign);
    }



}



?>