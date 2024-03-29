<?php
namespace App;
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require("../GeneralClasses/ConnectToDatabase.php");

class EmailNotification
{
public function recoverPass(string $userMail)
    {
        require "Mailer.php";
        //Cifer 
        $cipher = "aes-256-cbc"; 
        $encryption_key = "h^frdd#21!!cdw";
        $iv_size = openssl_cipher_iv_length($cipher);
        $iv = 'dsadadas8f3ed6ft';

        //Connect to Db
        $mysqli = ConnectToDatabase::connAdminPass();
        $sql = "SELECT email, id FROM users WHERE email = '$userMail'";
        $res = $mysqli->query($sql);
        //Find Mail in database
        if( $res->num_rows > 0)
        {
            $row = $res->fetch_assoc();
            $uid = $row['id'];

            $dateExpired=strtotime("now +30 Minutes");
            // testoo.html?usr_id=admin&password=pass
            $en_data = openssl_encrypt($uid, $cipher, $encryption_key, 0, $iv); 
            $en_date = openssl_encrypt($dateExpired, $cipher, $encryption_key, 0, $iv); 

            $en_data = str_replace("+","U002B",str_replace("/","U2215",str_replace("=","U003D",htmlentities($en_data, ENT_QUOTES,"UTF-8")))); 
            $en_date = str_replace("+","U002B",str_replace("/","U2215",str_replace("=","U003D",htmlentities($en_date, ENT_QUOTES,"UTF-8")))); 
            $mailer = new Mailer;
            $mailSubject = "WorkingTimeSchedule - Recover Your Password";
            // Będzie trzeba zmienić localhost an ip
            $data1= 'http://'.$_SERVER['SERVER_ADDR'].'/app/ChangePasswordForm.php?uid='.$en_data.'&d='.$en_date;
            $mailBody = "
            Hello User<br>
            We have received a request to <b>reset your account password</b>.<br>
            Keep a link that will help you with this.<br>
            ".$data1."<br>
            <br>
            If this is not your request, please delete this message<br>
            Best Regards<br>
            --
            Working Time Schedule<br>
            PiesioSpace<br>
            ";
            $mailer->sendMail("Adam",$userMail,$mailSubject,$mailBody);
            $res->free();
            $mysqli->close();
            return true;
        }else{
            $mysqli->close();
            return false;
        }
    }
}
 

?> 