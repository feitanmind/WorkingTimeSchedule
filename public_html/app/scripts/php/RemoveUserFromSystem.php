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
        $sql_LockUser = "DROP USER '$loginToLock'@'localhost';";
        $accessConnection->query($sql_DelFromUsers);
        $accessConnection->query($sql_DelFromUserData);
        $accessConnection->query($sql_LockUser);
        $depId = $_SESSION['Current_User_Department_Id'];
        $_SESSION['Module'] = 1;
        unset($_SESSION['arrayOfHoursOfWorkForCurrentMonth']);
        $result_newStatisticUser = $accessConnection->query("SELECT usr_id FROM user_data WHERE dep_id=$depId;");
        $row_newStatisticUser = $result_newStatisticUser->fetch_assoc();
        if($result_newStatisticUser->num_rows > 0)
        {
            $_SESSION['id_stat'] = $row_newStatisticUser['usr_id'];
        }
        else
        {
            $_SESSION['id_stat'] = 1;
        }
    }
    catch(Ex $e)
    {
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotification = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotification`,TypeOfNotification.Error,SubjectNotification.CantRemoveUserFromSystem);";
        echo "</script>";
        echo $e;
        $_SESSION['Module'] = 3;
    }
}
?>