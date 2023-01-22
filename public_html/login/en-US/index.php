<?php namespace App;session_start();?>
<!DOCTYPE html>
<html>
<head>
    <?php 
    date_default_timezone_set('America/Los_Angeles');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
 
   
    require __DIR__.'/../../app/modules/GeneralClasses/Encrypt.php';
    require __DIR__.'/../../app/modules/GeneralClasses/ConnectToDatabase.php';
    require __DIR__.'/../../app/modules/LoginClasses/Login.php';
    unset($_SESSION['cal']);
    $log = new Login;
    if(isset($_SESSION['header'])) header($_SESSION['header']);
    ?>
    <meta charset="UTF-8">
    <meta name="description" content="Application for working time schedule">
    <meta name="author" content="Adam Burski">
    <meta name="keywords" content="working, work, schedule, time">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Working Time Schedule</title>
    <script src="/../app/scripts/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/clear_css.css">
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <script src="/../app/scripts/loadingPage.js"></script>
    <script src="/../app/scripts/animation-login.js"></script>
    <script src="/../app/scripts/showpass.js"></script>
    <script src="/../app/scripts/closeRecoverPassForm.js"></script>
    <link rel="icon" type="image/x-icon" href="../style/img/favicon.ico">

</head>
<body>
    <iframe src="loading.html" id="loading" style="position: absolute; z-index:2; width: 100vw; height: 100vh; margin:0;"></iframe>
    <div id="closea" onclick="closeForm()"></div>

    <div class="loginBoard">
        <!-- LEFT PANEL -->
        <div class="leftPanelLogin">
            <div class="contactTutorialAndSupport">
                <div class="contactUS box1">
                    <div class="lpl-ctas-header">DO YOU NEED HELP?</div>
                    <div class="lpl-ctas-content">
                    Contact us in a convenient way.
                    You can do this by e-mail or by sending a form
                    </div>
                    <div class="lpl-ctas-btns">
                        <div class="btn-mail btn-1"><a href="mailto:burskiadamwork@gmail.com">MAIL</a></div>
                        <div class="btn-form btn-1"><a href="contactForm.php">FORM</a></div>
                    </div>
                </div>
                <div class="tutorial box1">
                    <div class="lpl-ctas-header">BEFORE YOU START</div>
                    <div class="lpl-ctas-content">
                    Check the operating instructions provided by our experts
                    </div>
                    <div class="lpl-ctas-btns">
                        <div class="btn-tutorial btn-1"><a href="./instruction.pdf" target="_blank" rel="noopener noreferrer">INSTRUCTION</a></div>
                    </div>
                </div>
                <div class="supportUS box1">
                    <div class="lpl-ctas-header">DO YOU LIKE THIS PROJECT?</div>
                    <div class="lpl-ctas-content">See our official Instagram profile</div>
                    <div class="lpl-ctas-btns">
                        <div class="btn-instagram btn-1"><a href="https://www.instagram.com/yellow.minds.angel/?hl=en" target="_blank" rel="noopener noreferrer">INSTAGRAM</a></div>
                    </div>
                </div>
            </div>
            <div class="logoAndAbout">
            </div>
        </div>
        <!-- RIGHT PANEL -->
        <div class="rightPanelLogin">
            <div class="changeLanguage">
                <div class="pl-lang" onclick="document.location = '../pl-PL/'">PL</div>
                |
                <div class="eng-lang" style="color: #0A85ED;" onclick="document.location = '../en-US/'">ENG</div>
            </div>
            <div class="helloText">
                <h2>Hello Again!</h2>
                <p>Log in to your account</p>
            </div>
            <div class="login-form">
                <form method="post" class="form-1">
                    <div class="ulog fontform">LOGIN</div>
                    <input type="text" autocomplete="off" name="usrlogin" id="usrlogin" placeholder="LOGIN OR EMAIL" />
                    <div class="upass fontform">PASSWORD</div>
                    <div class="passwo">
                        <input type="password" autocomplete="off" id="usrpass" name="usrpass" placeholder="      PASSWORD" onfocus="test()"/>
                        <div id="showp" class="showPass" onmousedown="showpass()" onmouseup="hidepass()"></div>
                    </div>
                    <p><a onclick="document.getElementById('passchanger').style.display = 'flex';document.getElementById('closea').style.display = 'flex';" style="cursor:pointer;">Forgot password?</a></p>
                    <input class="usub btn-1" type="submit" value="Log in"/>
                    <div id="warning1">
                    <?php 
                        if(isset($_SESSION['warning1'])) echo $_SESSION['warning1'];

                    ?>
                    </div>
                    <script>
                        function test()
                        {
                            document.getElementById('warning1').innerHTML ='.'
                        }
                    </script>
                </form>       
            </div>
            <div class="copyright">© 2022 | PIESIOSPACE CORPORATION. ALL RIGHTS RESERVED</div>
        </div>
    </div>
    <div class="pong" id="pong"></div>
    <div class="pong" id="pong2"></div>
    <div class="pong" id="pong3"></div>
    <div id="handguy"></div>
    <div id="dotguy"></div>
    <iframe src="../../app/modules/LoginClasses/RequestToChangePassword.php" id="passchanger" style=" display: none; position: absolute; z-index:2; width: 100vw; height: 100vh; margin:0;"></iframe>
    
</body>
</html>