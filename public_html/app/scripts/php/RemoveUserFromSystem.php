<?php
namespace App;
use \Exception as Ex;
    
if(isset($_POST['userToRemoveFromSystem']))
{
    try
    {
        $userToRemove = $_POST['userToRemoveFromSystem'];
        $idToDel = substr($userToRemove,0,strpos($userToRemove,'!'));
        $loginToLock = substr($userToRemove, strpos($userToRemove, '!') + 1);
        $accessConnection = ConnectToDatabase::connAdminPass();
        $sql_DelFromUsers = "DELETE FROM users WHERE id=$idToDel;";
        $sql_DelFromUserData = "DELETE FROM user_data WHERE id=$idToDel;";
        $sql_LockUser = "ALTER USER '$loginToLock'@'localhost' ACCOUNT LOCK;";
        $accessConnection->query($sql_DelFromUsers);
        $accessConnection->query($sql_DelFromUserData);
        $accessConnection->query($sql_LockUser);
        $_SESSION['Module'] = 3;
    }
    catch(Ex $e)
    {
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotification = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotification`,TypeOfNotification.Error,SubjectNotification.CantRemoveUserFromSystem);";
        echo "</script>";
        
        $_SESSION['Module'] = 3;
    }
}
?>