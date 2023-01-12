<?php
namespace App;

class ConnectToDatabase
{
    public function connectToDb($server,$username,$password,$database)
    {
        $mysqli = new \MySQLi($server, $username,$password,$database);
        if($mysqli->connect_errno)
        {
            echo("Failed to connect. Error: ".$mysqli->connect_error);
            return false;
        }
        else
        {
            return array($mysqli,true);
        }
    }

    public static function connUserPass()
    {
        $en = new Encrypt();
        $username = $_SESSION['username'];
        $encryptedPass = $_SESSION['password'];
        $decryptPass = $en->decryptString();
        $mysqli = new \MySQLi('localhost', $username,$decryptPass,'app_commercial');
        if($mysqli->connect_errno)
        {
            echo("Failed to connect. Error: ".$mysqli->connect_error);
            return false;
        }
        else
        {
            return $mysqli;
        }
    }

    public static function connAdminPass()
    {
        $en = new Encrypt();
        $decryptPass = $en->decryptString('xaDKyOPiQ9lA2p5qQg3OAQU003DU003D');
        $mysqli = new \MySQLi('localhost', 'root','niemahasla','app_commercial');
        if($mysqli->connect_errno)
        {
            echo("Failed to connect. Error: ".$mysqli->connect_error);
            return false;
        }
        else
        {
            return $mysqli;
        }
    }
}

?>