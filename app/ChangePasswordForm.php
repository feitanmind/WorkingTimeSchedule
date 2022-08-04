<?php

        $cipher = "aes-256-cbc"; 
        $encryption_key = "h^frdd#21!!cdw";
        $iv_size = openssl_cipher_iv_length($cipher);
        $iv = 'dsadadas8f3ed6ft'; 
    if(isset($_GET['uid']) && isset($_GET['d'])) //+ data wygaśniecia do sprawdzenia 
    {
        //sprawdzenie daty wygaśniecia 
        if(openssl_decrypt(str_replace("U002B","+",str_replace("U2215","/",str_replace("U003D","=",htmlentities($_GET['d'], ENT_QUOTES,"UTF-8")))),$cipher, $encryption_key, 0, $iv) >= strtotime("now")){
            
            $user_id = openssl_decrypt(str_replace("U002B","+",str_replace("U2215","/",str_replace("U003D","=",htmlentities($_GET['uid'], ENT_QUOTES,"UTF-8")))),$cipher, $encryption_key, 0, $iv);
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
   