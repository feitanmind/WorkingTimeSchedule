<?php
session_start();
class LoginTest extends \PHPUnit\Framework\TestCase
{
    public function test_shouldReturnTrueWhenCorrectCredentialsPassedByForm()
    {
        $_SESSION['log'] = false;
        $userLog = 'adam';
        $userPass = 'niemahasla';
        $_POST['usrlogin'] = $userLog;
        $_POST['usrpass'] = $userPass;
        $login = new App\Login;
        $this->assertEquals(true,$_SESSION['log']);
    }
    public function test_ShouldReturnReturnInformationWithError()
    {
        $_SESSION['log'] = false;
        $userLog = 'baduser';
        $userPass = 'n888jy7';
        $_POST['usrlogin'] = $userLog;
        $_POST['usrpass'] = $userPass;
        $login = new App\Login;
        $this->assertEquals('Cannot find user',$_SESSION['warning1']);
    }
}




?>