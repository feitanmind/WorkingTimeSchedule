<?php

use App\Shift;
class PHPScripts
{
    public static function CHECK_User_Is_Logged()
    {
        if(!isset($_SESSION['user_id']) && !isset($_SESSION['log'])) header("Location: ../");
        else if($_SESSION['log'] == false) header("Location: ../");
    }
    public static function CHECK_AND_SET_Session_Var_Shift_and_Role()
    {
        isset($_POST['shiftID']) ? $_SESSION['shift_id'] = $_POST['shiftID'] : $_SESSION['shift_id'] = 1;
        isset($_POST['roleID']) ? $_SESSION['role_id'] = $_POST['roleID'] : $_SESSION['role_id'] = 1;
    }
}

       
?>