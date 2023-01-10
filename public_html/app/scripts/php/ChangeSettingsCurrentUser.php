<?php
namespace App;
use \Exception as Ex;
if(isset($_POST['newPassword_CUS']))
{
    $decPass = $_POST['newPassword_CUS'];
    $idCurrentUser = $_SESSION['User_Id'];
    $username = $_SESSION['username'];
    $accessConnection = ConnectToDatabase::connAdminPass();
    $encrypt = new Encrypt();
    $newPassword = $encrypt->encryptString($_POST['newPassword_CUS']);
    $updatePasswordQuery = "UPDATE user_data SET password='$newPassword' WHERE usr_id=$idCurrentUser;";
    //$changeDbPass = "ALTER USER '$username'@'hostname' IDENTIFIED BY '$decPass';";
    //$changeDbPass = "UPDATE mysql.user SET Password=PASSWORD('$decPass') WHERE USER='$username' AND Host='localhost';";
    $changeDbPass = "SET PASSWORD FOR '$username'@'localhost' = PASSWORD('$decPass');";
    try
    {
        $accessConnection->query($changeDbPass);
        $accessConnection->query($updatePasswordQuery);

        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Success,SubjectNotification.PasswordWasChanged);";
        echo "</script>";
        $_SESSION['password'] = $newPassword;
    }
    catch(Ex $e)
    {   
        print_r($e);
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Error,SubjectNotification.CantChangePassword);";
        echo "</script>";
    }


}



?>