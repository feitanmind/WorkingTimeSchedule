<?php

class CalendarTest extends \PHPUnit\Framework\TestCase
{
    public function test_ShouldReturnNameOfUserWhenSignUserBeforeToWorking()
    {
        $_number_of_month = 7;
        $_number_if_year = 2022;
        $_department_id = 1;
        $_user_id = 1;

        $day_id_in_calendar = 0;
        $shift_id_in_calendar = 0;
        $calendar = new App\Calendar($_number_of_month, $_number_if_year,$_department_id);
        $user1 = new App\User( $_user_id );
        $calendar->SignUserToWorkInDay($user1, $day_id_in_calendar, $shift_id_in_calendar);

        $this->assertEquals($calendar->Days[0]->Shifts[0]->EmployeesWorking[0]->name, 'Adam');
    }

    public function test_ShuldReturnEmptyArrayWhenDeleteLastUserFromShift()
    {
        $_number_of_month = 7;
        $_number_if_year = 2022;
        $_department_id = 1;
        $_user_id = 1;

        $day_id_in_calendar = 0;
        $shift_id_in_calendar = 0;
        $calendar = new App\Calendar($_number_of_month, $_number_if_year,$_department_id);
        $user2 = new App\User( $_user_id );
        $calendar->SignUserToWorkInDay($user2, $day_id_in_calendar, $shift_id_in_calendar);

        //Usuwanie użyrtkownika ze zmiany
        $calendar->UnsignWorkingUserFormDay($user2, $day_id_in_calendar,$shift_id_in_calendar);
        $this->assertEmpty($calendar->Days[0]->Shifts[0]->EmployeesWorking);
    }

    //Test ponieważ nie chciał działać przez obrazek typu blob
    public function test_ShouldReturnEncodedObjectInJsonFormat()
    {
        $_number_of_month = 7;
        $_number_if_year = 2022;
        $_department_id = 1;
        $calendar = new App\Calendar($_number_of_month, $_number_if_year,$_department_id);
        $jsonCalendar = json_encode($calendar);
        $this->assertIsString($jsonCalendar);
        $this->assertEquals($this->assertStringStartsWith("{", $jsonCalendar),$this->assertStringEndsWith("}",$jsonCalendar));

    }
    // public function test_ShouldReturnDecodedObjdect()
    // {
    //     $_number_of_month = 7;
    //     $_number_if_year = 2022;
    //     $_department_id = 1;
    //     $calendarBefore = new App\Calendar($_number_of_month, $_number_if_year,$_department_id);
    //     $jsonCalendar = json_encode($calendarBefore);
    //     unset($calendarBefore);
    //     $calendarAfter = json_decode($jsonCalendar);
    //     $this->assertInstanceOf('App\Calendar', (App\Calendar)$calendarAfter);
    // }
}



?>