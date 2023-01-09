<?php
    class HoursOfWorkTest extends \PHPUnit\Framework\TestCase
    {
        public function test_ShouldGetCorrectNumbersOfWorkingDays()
        {
            $workingDaysInApril2022 = App\HoursOfWork::GetWorkingDaysInMonth(2022,4);
            $correctWorkingDaysInApril2022 = 20;
            $this->assertEquals($workingDaysInApril2022,$correctWorkingDaysInApril2022);
        }
        public function test_ShouldSubstractHoursOfWorkOnObject()
        {
            $_SESSION['Shift_Id'] = 1;
            $substractingHoursOnShift1 = "08:00:00";
            $user = new App\User(1);
            $hoursOfWorkForUserOnMay2022 = new App\HoursOfWork($user,5,2022,'08:00:00');
            $hoursBefore = $hoursOfWorkForUserOnMay2022->Hours;

            //Spradzamy czy wyniki się róznią
            $hoursOfWorkForUserOnMay2022->SubstractTimeOfWork($user);
            $hoursAfter = $hoursOfWorkForUserOnMay2022->Hours;
            $this->assertNotEquals($hoursBefore,$hoursAfter);
            //Sprawdzamy czy odjęto dokładną wartość
            $hoursBeforeAsInt = intval(substr($hoursBefore,0,3));
            $hoursAfterAsInt = intval(substr($hoursAfter,0,3));

            $this->assertEquals($hoursBeforeAsInt-8, $hoursAfterAsInt);
        }
        public function test_ShouldAddHoursOfWorkOnObject()
        {
            $user = new App\User(1);
            $hoursOfWorkForUserOnMay2022 = new App\HoursOfWork($user,5,2022,'08:00:00');
            $hoursBefore = $hoursOfWorkForUserOnMay2022->Hours;
            $addingHours = 8;
            //Spradzamy czy wyniki się róznią
            $hoursOfWorkForUserOnMay2022->AddTimeOfWork();
            $hoursAfter = $hoursOfWorkForUserOnMay2022->Hours;
            $this->assertNotEquals($hoursBefore,$hoursAfter);
            //Sprawdzamy czy dodano dokładną wartość
            $hoursBeforeAsInt = intval(substr($hoursBefore,0,3));
            $hoursAfterAsInt = intval(substr($hoursAfter,0,3));
            $this->assertEquals($hoursBeforeAsInt+$addingHours, $hoursAfterAsInt);
        }
        public function test_ShouldReturnEasterDate()
        {
            $easterDateIn2024 = "2024-3-31";
            $calculatedEasterDateIn2024 = App\HoursOfWork::GetEasterDate(2024);
            $this->assertEquals($easterDateIn2024,$calculatedEasterDateIn2024);
        }
    }


?>