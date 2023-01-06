<?php
    namespace App;
    require "app/modules/GeneralClasses/Encrypt.php";
    require "app/modules/GeneralClasses/ConnectToDatabase.php";
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Changing password- Working Time Shcedule</title>
    <link rel="stylesheet" type="text/css" href="style/ChangePasswordStyle.css"/>
</head>
<body>
<?php

    //Sprawdzenie czy zostały przekazane dane (zaszyfrowane id użytkownika oraz zaszyfrowana data wygaśniecia)
    if(isset($_GET['uid']) && isset($_GET['d']))
    {
        $cipher = new Encrypt();
        $cryptedUserIdentifier = $_GET['uid'];
        $cryptedEvaluationDate = $_GET['d'];      
        //sprawdzenie daty wygaśniecia strony 
        if($cipher->decryptString($cryptedEvaluationDate) >= strtotime("now")){
            
            $user_id = $cipher->decryptString($cryptedUserIdentifier);
            echo '
            
            <script src="/../scripts/showpass.js"></script>
            <form class="ChangePasswordPosition" id="form1" method="post" action="ChangePasswordForm.php">
                <h2>Change your password</h2>
                <input type="password" name="newpass" id="newpass" onfocusout="checkPasswordsAreSameFieldOne()" placeholder="insert new password" /><br>
                <input type="password" name="confpass" id="confpass" onfocusout="checkPasswordsAreSameFieldTwo()" placeholder="confirm new password" /><br>
                <input type="text" name="uid" value="'.$user_id.'" style="display: none;"/>
                <div style="height: 1vh" id="info2">.</div>
                <input type="submit" id="changePassSend" disabled="true" value="Change password"/>
                
            </form>
            <script src="/../scripts/checkPasswordChange.js"></script>
            ';
        }
        else
        {
            echo '
            <form class="ChangePasswordPosition" style="height: 8vh; background-color: id="form1">
                <h2>Site expired...</h2>
            </form>
            <h2 style="margin-top: 5vh;">Redirecting to login site...</h2>
            <h2 style="font-size: 10vw;">9</h2>
            <script src="/scripts/countDownAndLog.js"></script>
            ';
        }            
    }
    else
    {
        if(isset($_POST['newpass']) && isset($_POST['confpass']))
        {
            //Drugie sprawdzenie na wypadek obejścia skryptu JavaScript
            if($_POST['newpass'] == $_POST['confpass'] && $_POST['uid'] != '') 
            {
                $uid = intval($_POST['uid']);
                $newPasswordDecrypted = $_POST['newpass'];
                $cipher = new Encrypt();
                $newPasswordForUser = $cipher->encryptString($_POST['newpass']);
                $access_Connection= ConnectToDatabase::connAdminPass();
                //Change password in user field
                $sqlUpdatePassword = "UPDATE users SET password='$newPasswordForUser' WHERE id = $uid;";

                if($access_Connection->query($sqlUpdatePassword) === TRUE)
                {
                    $findUsernameInDatabase = "SELECT login FROM users WHERE id = $uid";
                    $resultSelectLogin = $access_Connection->query($findUsernameInDatabase);
                    $rowSelectLogin = $resultSelectLogin->fetch_assoc();

                    $changePasswordForDatabaseSQL = "SET PASSWORD FOR '".$rowSelectLogin['login']."'@'localhost' = PASSWORD('".$newPasswordDecrypted."');";
                    $resultSelectLogin = $connAuth->query($changePasswordForDatabaseSQL);
                    echo '
                    <form class="ChangePasswordPosition" style="height: 8vh; background-color: rgba(82, 218, 54, 0.212);" id="form1">
                        <h2>Password changed</h2>
                    </form>
                    <h2 style="margin-top: 5vh;">Redirecting to login site...</h2>
                    <h2 style="font-size: 10vw;">9</h2>
                    <script src="/scripts/countDownAndLog.js"></script>
                    ';
                }
                else
                {
                    echo '
                    <form class="ChangePasswordPosition" style="height: 8vh; background-color: rgba(177, 23, 23, 0.521);" id="form1">
                        <h2>Error: Contact with Administrator</h2>
                    </form>
                    <h2 style="margin-top: 5vh;">Redirecting to login site...</h2>
                    <h2 style="font-size: 10vw;">9</h2>
                    <script src="/scripts/countDownAndLog.js"></script>
                    ';
                }
            }
            else
            {
                header("Location: ../");
            }
        }
        else
        {
            header("Location: ../");
        }
    }
?>
</body>
</html>