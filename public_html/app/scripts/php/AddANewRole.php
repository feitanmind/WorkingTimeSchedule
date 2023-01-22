<?php
namespace App;
if(isset($_POST['newRoleAsRoleDb']))
{
    $roleCurrentUser = $_SESSION['Current_User_Role_Id'];
    $roleInDb = $_POST['newRoleAsRoleDb'];
    try
    {
        $nameOfNewRole = $_POST['nameOfNewRole'];
        if(isset($_POST['newRoleDepId']))
        {
            $newRoledepId = $_POST['newRoleDepId'];
        }
        else
        {
            $newRoledepId = $_SESSION['Current_User_Department_Id'];
        }
        $accessConnection = ConnectToDatabase::connUserPass();
        $sqlAddNewRole = "INSERT INTO roles(name,dep_id,role_db) VALUES('$nameOfNewRole',$newRoledepId,$roleInDb);";
        $accessConnection->query($sqlAddNewRole);
    }
    catch(\Exception $e)
    {
        echo $e;
    }
}

    

?>