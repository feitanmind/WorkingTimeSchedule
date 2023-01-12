<?php
namespace App;
use \Exception as Ex;
if(isset($_POST['newPassword_CUS']))
{
    if($_POST['newPassword_CUS'] != "")
    {
        $decPass = $_POST['newPassword_CUS'];
        $idCurrentUser = $_SESSION['User_Id'];
        $username = $_SESSION['username'];
        $accessConnection = ConnectToDatabase::connAdminPass();
        $encrypt = new Encrypt();
        $newPassword = $encrypt->encryptString($_POST['newPassword_CUS']);
        $updatePasswordQuery = "UPDATE users SET password='$newPassword' WHERE id=$idCurrentUser;";
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
}
if(isset($_FILES['newAvatar_CUS']))
{
    if($_FILES['newAvatar_CUS']['size'] != 0)
    {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/app/style/img/avatars/";
        $nameOfFile = $_SESSION['username'] . '-avatar'. substr($_FILES['newAvatar_CUS']['name'],strpos($_FILES['newAvatar_CUS']['name'],"."));
        $target_file = $target_dir . $nameOfFile;
        $idUsr = $_SESSION['User_Id'];
                     
        if ($_FILES["newAvatar_CUS"]["size"] < 5000000) {
            move_uploaded_file($_FILES["newAvatar_CUS"]['tmp_name'], $target_file);
            $accessConnection = ConnectToDatabase::connAdminPass();
            $accessConnection->query("UPDATE user_data SET avatar='$nameOfFile' WHERE id = $idUsr;");
        }
    }
}

?>