<?php
namespace App;
if(isset($_POST['roleToRemove']))
{
    $roleToRemove = $_POST['roleToRemove'];
    $accessConnection = ConnectToDatabase::connAdminPass();
    $sqlAddNewRole = "DELETE FROM roles WHERE id = $roleToRemove;";
    try
    {
        $accessConnection->query($sqlAddNewRole);
        //komunikat gdy się uda
    }
    catch(\Exception $e)
    {
        //komunikat gdy się nie uda
    }
}


?>