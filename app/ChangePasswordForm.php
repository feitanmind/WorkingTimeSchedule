<?php

    require "Encrypt.php";
    if(isset($_GET['uid']) && isset($_GET['d'])) //+ data wygaśniecia do sprawdzenia 
    {
        $cryptedEvaluationDate = $_GET['d'];
        $cryptedUserIdentifier = $_GET['uid'];
        $cipher = new Encrypt();
        //sprawdzenie daty wygaśniecia 
        if($cipher->decryptString($cryptedEvaluationDate) >= strtotime("now")){
            
            $user_id = $cipher->decryptString();
            echo '
            <form method="post" action="ChangePasswordForm.php">
                <input type="text" name="newpass" placeholder="insert new password" /><br>
                <input type="text" name="confpass" placeholder="confirm new password" /><br>
                <input style="display: none" type="text" name="uids" placeholder="uid" value="<?php if(isset($user_id))echo $user_id ?>"/><br>
                <input type="submit" value="Change password"/>
            </form>
            
            ';
        }else{
            echo "Strona wygasła";
        }
               
    }else{
        if(isset($_POST['newpass']) && isset($_POST['confpass'])){
            echo "Success. Password was changed";
        }else{
            header("Location: index.php");
        }
    }
  


?>
   