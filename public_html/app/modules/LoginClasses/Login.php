<?php
namespace App;
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Login
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
            $userPassword = $cipher->encryptString($_POST['usrpass']);
            $access_Connection = ConnectToDatabase::connAdminPass();
            $searchUserInDb = "SELECT users.id, users.login, users.password, users.email FROM users WHERE users.login = '$userLogin' OR users.email = '$userLogin'";
            $result = $access_Connection ->query($searchUserInDb);
            if($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                if($row['password'] == $userPassword)
                {
                    $_SESSION['log'] = true;
                    //$_SESSION['user_id'] = $row['id'];
                    $_SESSION['User_Id']= $row['id'];
                    $id = $row['id'];
                    $_SESSION['username'] = $row['login'];
                    $_SESSION['password'] = $userPassword;
                    unset($_SESSION['warning1']);
                    $_SESSION['header'] = "Location: ../../app/";
                    //$_SESSION['workers_role'] = 1;
                    $_SESSION['Role_Of_Employees'] = 1;
                    //$_SESSION['shift_id'] = 1;
                    $_SESSION['Shift_Id'] = 1;
                    $_SESSION['Role_Id'] = 1;
                    $_SESSION['id_stat'] = 1;
                    $searchAdditionalInformation = "SELECT avatar, dep_id FROM user_data WHERE usr_id = $id;";
                    $res = $access_Connection->query($searchAdditionalInformation);
                    if($res->num_rows > 0)
                    {
                        $row = $res->fetch_assoc();
                        $_SESSION['Current_User_Department_Id'] = $row['dep_id'];
                        $_SESSION['User_Avatar'] = $row['avatar'];
                    }
                    

                    // $_SESSION['Month_Number'] = date('m');
                    // $_SESSION['Year_Number'] = date('Y');
                    $_SESSION['Month_Number'] = 1;
                    $_SESSION['Year_Number'] = 2022;
                    
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
?>