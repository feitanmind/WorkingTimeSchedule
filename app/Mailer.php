<?php
namespace App;
// Framework PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '/var/www/html/wokingTimeSchedule/vendor/autoload.php';
class Mailer
{
    public function sendMail(string $nameAdrr, string $addrAdrr, string $mailSubject, string $mailBody)
    {
        // $conn = new App\ConnectToDatabase;
        

        try{
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp-mail.outlook.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'workingtimeschedule@outlook.com';
            $mail->Password   = 'hgfgh4$f4g!!DD';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            //Recipients
            $mail->setFrom('workingtimeschedule@outlook.com', 'Working Time Schedule');
            $mail->addAddress($addrAdrr, $nameAdrr);
            $mail->addReplyTo('workingtimeschedule@outlook.com', 'Information');
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body    = $mailBody;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            //$mail->send();
            return true;
        
        }catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }



}



?>