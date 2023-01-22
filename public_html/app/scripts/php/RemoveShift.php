<?php
namespace App;
use \Exception as Ex;
    
if(isset($_POST['Id_removeShift']))
{
    try
    {
        $idToDel = $_POST['Id_removeShift'];
        $accessConnection = ConnectToDatabase::connUserPass();
        $sql = "DELETE FROM shifts WHERE id=$idToDel;";
        $accessConnection->query($sql);
        $_SESSION['Module'] = 4;
    }
    catch(Ex $e)
    {
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotification = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotification`,TypeOfNotification.Error,SubjectNotification.AddShiftFailed);";
        echo "</script>";
        
        $_SESSION['Module'] = 4;
    }
}
    



?>