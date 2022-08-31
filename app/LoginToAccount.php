<?php
namespace App;
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class LoginToAccount
{
    
    function __construct()
    {
        if(isset($_SESSION['log']))
        {
            if($_SESSION['log'] == true)
            {
                $_SESSION['header'] = "Location: ../../app/";
            }
        }

        if(isset($_POST['usrlogin']) && isset($_POST['usrpass']))
        {

            $userLogin = $_POST['usrlogin'];
            $cipher = new Encrypt;
            $conn = new ConnectToDatabase;
            $userPassword = $cipher->encryptString($_POST['usrpass']);

            $mysqliAdm = $conn -> connAdminPass();
            $searchUserInDb = "SELECT users.login, users.password, user_data.email FROM users INNER JOIN user_data ON users.id = user_data.usr_id WHERE users.login = '$userLogin' OR user_data.email = '$userLogin'";
            $result = $mysqliAdm ->query($searchUserInDb);
            if($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                if($row['password'] == $userPassword)
                {
                    $_SESSION['log'] = true;
                    $_SESSION['username'] = $row['login'];
                    $_SESSION['password'] = $userPassword;
                    unset($_SESSION['warning1']);
                    $_SESSION['header'] = "Location: ../../app/";
                    
                }
                else
                {
                    $_SESSION['log'] = false;
                    $_SESSION['warning1'] = 'Bad password';
                }
            }
            else
            {
                $_SESSION['log'] = false;
                $_SESSION['warning1'] = 'Cannot find user';
            }

            
        }
    }

}
    