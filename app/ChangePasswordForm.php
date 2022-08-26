<?php
namespace App;
    require "Encrypt.php";
    if(isset($_GET['uid']) && isset($_GET['d'])) //+ data wygaśniecia do sprawdzenia 
    {
        $cipher = new Encrypt();
        $cryptedUserIdentifier = $_GET['uid'];
        $cryptedEvaluationDate = $_GET['d'];      
        //sprawdzenie daty wygaśniecia 
        if($cipher->decryptString($cryptedEvaluationDate) >= strtotime("now")){
            
            $user_id = $cipher->decryptString($cryptedUserIdentifier);
            echo '
            <link rel="stylesheet" type="text/css" href="style/ChangePasswordStyle.css"/>
            <script src="/../scripts/showpass.js"></script>
            <script>


            </script>
            <form class="ChangePasswordPosition" id="form1" method="post" action="ChangePasswordForm.php">
                <h2>Change your password</h2>
                <input type="password" name="newpass" id="newpass" onfocusout="checkPasswordsAreSameFieldOne()" placeholder="insert new password" /><br>
                <input type="password" name="confpass" id="confpass" onfocusout="checkPasswordsAreSameFieldTwo()" placeholder="confirm new password" /><br>
                <div style="height: 1vh" id="info2">.</div>
                <input type="submit" id="changePassSend" disabled="true" value="Change password"/>
                
            </form>
            
            ';
        }else{
            echo "Strona wygasła";
        }
               
    }else{
        if(isset($_POST['newpass']) && isset($_POST['confpass'])){
            echo '
            <link rel="stylesheet" type="text/css" href="style/ChangePasswordStyle.css"/>
            <body>
            
            <form id="form1"></form>
            </body>
            <script>
                document.getElementById("form1").innerHTML="<h2>Zmieniono hasło</h2><p>Zostaniesz przeniesiony do strony logowania za:</p><h2 style=\"margin-left: 6vw; margin-top: 2vh; font-size: 4vw;\">10</h2>";
                var reconnectCount = 10;
                let rec = setInterval(() => {
                    reconnectCount--;
                    if(reconnectCount < 1)
                    {
                        document.location = "../";
                    }
                    else
                    {
                        document.getElementsByTagName("h2")[1].innerText = reconnectCount;
                    }
                     
                }, 1000);

            </script>
            
            ';
        }else{
            header("Location: ../");
        }
    }
  


?>
<script>
    let check1 = 0;
    let info2 = document.getElementById("info2");
    let changePassSend = document.getElementById("changePassSend");
    function checkPasswordsAreSameFieldTwo()
        {
            check1 = 1;
            let field1 = document.getElementById("newpass");
            let field2 = document.getElementById("confpass");
            
            
            if(field1.value == field2.value)
            {
                changePassSend.disabled = false;
                changePassSend.style.backgroundColor = "#0A85ED";
                            field1.style.borderColor = "#caccce";
                            field2.style.borderColor = "#caccce";
                            field1.style.backgroundColor = "#fff";
                            field2.style.backgroundColor = "#fff";
                            info2.innerHTML = " ";
                            
                        }
                        else
                        {
                            changePassSend.disabled = true;
                            changePassSend.style.backgroundColor = "gray";
                            field1.style.borderColor = "red";
                            field2.style.borderColor = "red";
                            field1.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                            field2.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                            info2.innerHTML = "Hasła nie są takie same!";
                            info2.style.color = "red";
                        }

                    }
                    
    function checkPasswordsAreSameFieldOne()
                {
                    if( check1 != 0 )
                    {
                        field1 = document.getElementById("newpass");
                        field2 = document.getElementById("confpass");
                        if(field1.value == field2.value)
                        {
                            changePassSend.disabled = false;
                            changePassSend.style.backgroundColor = "#0A85ED";
                            field1.style.borderColor = "#caccce";
                            field2.style.borderColor = "#caccce";
                            field1.style.backgroundColor = "#fff";
                            field2.style.backgroundColor = "#fff";
                            info2.style.innerHTML = " ";

                        }
                        else
                        {
                            changePassSend.disabled = true;
                            changePassSend.style.backgroundColor = "gray";
                            field1.style.borderColor = "red";
                            field2.style.borderColor = "red";
                            field1.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                            field2.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                            info2.style.color = "red";
                            info2.innerHTML = "Hasła nie są takie same!";
                        }
                    }

                    
                }
                
</script>