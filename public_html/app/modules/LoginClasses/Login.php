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
                    $_SESSION['User_Id']= $row['id'];
                    $_SESSION['IsCalendarSave'] = 'yes';
                    $id = $row['id'];
                    $_SESSION['username'] = $row['login'];
                    $_SESSION['password'] = $userPassword;
                    unset($_SESSION['warning1']);
                    $_SESSION['header'] = "Location: ../../app/";         
                    
                    $_SESSION['id_stat'] = $_SESSION['User_Id'];
                    $searchAdditionalInformation = "SELECT avatar, dep_id, role_id FROM user_data WHERE usr_id = $id;";
                    $res = $access_Connection->query($searchAdditionalInformation);
                    if($res->num_rows > 0)
                    {
                        $row = $res->fetch_assoc();
                        $_SESSION['Current_User_Department_Id'] = $row['dep_id'];
                        $_SESSION['User_Avatar'] = $row['avatar'];
                        $_SESSION['Role_Id'] = $row['role_id'];
                        $sqlRoleDb = "SELECT role_db FROM roles WHERE id=".$row['role_id'].";";
                        $resRole = $access_Connection->query($sqlRoleDb);
                        $rowRole = $resRole->fetch_assoc();
                        $_SESSION['Current_User_Role_Id'] = $rowRole['role_db'];
                    }
                    $searchShift = "SELECT id FROM shifts WHERE dep_id =".$row['dep_id'] .";";
                    $resShi = $access_Connection->query($searchShift);
                    if($res->num_rows > 0)
                    {
                        $rowShi = $resShi->fetch_assoc();
                        $_SESSION['Shift_Id'] = $rowShi['id'];
                    }
                    else
                    {
                        $_SESSION['Shift_Id'] = 0;
                    }

                    // $_SESSION['Month_Number'] = date('m');
                    // $_SESSION['Year_Number'] = date('Y');
                    $_SESSION['Month_Number'] = 1;
                    $_SESSION['Year_Number'] = 2022;
                    $_SESSION['Module'] = 1;
                    
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