<?php
namespace App;

class ConnectToDatabase
{
    public function connectToDb($server,$username,$password,$database)
    {
        $mysqli = new \MySQLi($server, $username,$password,$database);
        if($mysqli->connect_errno){
            echo("Failed to connect. Error: ".$mysqli->connect_error);
            return false;
        }else{
            return true;
        }
    } 
}

?>