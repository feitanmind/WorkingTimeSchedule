<?php namespace App; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../../../login/style/frtpassstyle.css"/>
</head>
<body>
<?php 
    require "../GeneralClasses/EmailNotification.php";
    date_default_timezone_set('America/Los_Angeles');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
    <div class="pass-changer">
            <h1>Forgot password?</h1>        
        <form method="post" action="RequestToChangePassword.php">
            <p>Insert your email below</p>
            <input type="text" name="umail" placeholder="INSERT YOUR EMAIL"/>
            <input type="submit" value="Send mail with recover"/>
        </form>
        <div id="returnInfo"></div>
        <?php
            if(isset($_POST['umail']))
            {
                $usrMail = $_POST['umail'];
                $sendRecover = new EmailNotification;
                $sendOrNot = $sendRecover->recoverPass($usrMail);
                if($sendOrNot == true)
                {
                    echo '
                        <script>let info1 = document.getElementById("returnInfo");
                            info1.innerHTML = "<p>Mail was send</p>";
                            info1.style.color = "green";
                        </script>
                    ';
                }else{
                    echo '
                        <script>
                            let info1 = document.getElementById("returnInfo");
                            info1.innerHTML = "<p>There is no user with this e-mail</p>";
                            info1.style.color = "red";
                        </script>
                    ';
                }
            }


        ?>
        
    </div>
</body>
</html>