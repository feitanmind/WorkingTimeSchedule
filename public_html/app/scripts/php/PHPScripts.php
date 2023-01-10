<?php
namespace App;
use Exception as Ex;
use PHPUnit\TextUI\CliArguments\Exception;

use function PHPUnit\Framework\throwException;

class PHPScripts
{
    public static function CHECK_User_Is_Logged() { include('CheckUserIsLogged.php'); }

    public static function CHECK_AND_SET_Session_Var_Shift_and_Role() { include('CheckAndSetSessionVarShiftAndRole.php'); }

    public static function WHAT_MODULE_IS_SELECTED() { include('WhatModuleIsSelected.php'); }

    public static function PERMISSION_VIEW_MODULES() { include('PermissionViewModules.php'); }

    public static function ADD_USER_TO_Day_of_Calendar(){ include('AddUserToDayOfCalendar.php'); }

    public static function CreateArrayOfHoursOfWorkForUsers() { include('CreateArrayOfHoursOfWorkForUsers.php'); }

    public static function GRANT_USER_Vacation_In_Day_of_Calendar() { include('GrantUserVacationInDayOfCalendar.php'); }

    public static function REMOVE_USER_FROM_Day_Of_Calendar() { include('RemoveUserFromDayOfCalendar.php'); }

    public static function REVOKE_VACATION_For_A_User() { include('RevokeVacationForAUser.php'); }

    public static function SAVE_IN_DATABASE() { include('SaveInDatabase.php'); }

    public static function CHANGE_MONTH() { include('ChangeMonth.php'); }

    public static function CHANGE_USER_STATS() { include('ChangeUserStats.php'); }

    public static function ADD_NEW_SHIFT() { include('AddANewShift.php'); }

    public static function ADD_NEW_DEPARTMENT() { include('AddANewDepartment.php'); }

    public static function REMOVE_SHIFT() { include('RemoveShift.php'); }

    public static function REMOVE_USER_FROM_SYSTEM(){ include('RemoveUserFromSystem.php'); } 

    public static function REFRESH_CAL_AND_HOURS(){ include('RefreshCalendarAndHoursOfWork.php'); }

    public static function CHANGE_SETTINGS_CURRENT_USER(){ include('ChangeSettingsCurrentUser.php');}
}


?>