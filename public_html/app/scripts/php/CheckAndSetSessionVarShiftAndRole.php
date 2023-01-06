<?php
if (isset($_POST['shiftID'])) 
{
    $_SESSION['Shift_Id'] = $_POST['shiftID'];
}
if (isset($_POST['roleID'])) 
{

    $_SESSION['Role_Id'] = $_POST['roleID'];
}


?>