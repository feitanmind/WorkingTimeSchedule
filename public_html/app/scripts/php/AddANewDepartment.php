<?php
namespace App;

use \Exception as Ex;

if(isset($_POST['nameOfAddingDepartment']))
{
    $name = $_POST['nameOfAddingDepartment'];
    $accessConnection = ConnectToDatabase::connUserPass();
    $sql = "INSERT INTO department(name) VALUES('$name');";
    try
    {
        $accessConnection->query($sql);
        $_SESSION['Module'] = 5;
    }
    catch(Ex $e)
    {
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Error,SubjectNotification.AddDepartmentFailed);";
        echo "</script>";
        $_SESSION['Module'] = 5;
    }

}




?>