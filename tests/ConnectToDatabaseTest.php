<?php
class ConnectToDatabaseTest extends \PHPUnit\Framework\TestCase 
{
    public function test_ShouldReturnTrueWhenPassedCorrectCredentials()
    {
        $userCredentials = array("localhost","testowy","testowy","app");
        $conn = new App\ConnectToDatabase;
        $ifconnected = $conn -> connectToDb($userCredentials[0],$userCredentials[1],$userCredentials[2],$userCredentials[3]);
        //Sprawdzamy co w przypadku podania dobrych danych do bazy danych
        $this -> assertEquals(true, $ifconnected[1]);
    }
   
    public function test_ShouldThrownExceptionWhenPassedBadUserCredentials()
    {
        $badUserCredentials = array("localhost","bad_user","bad_password","app");
        $conn = new App\ConnectToDatabase;
        //Sprawdzamy czy po podaniu błędnych danych dostaniemy wyjątek
        $this -> expectException(mysqli_sql_exception::class);
        $ifconnected = $conn -> connectToDb($badUserCredentials[0],$badUserCredentials[1],$badUserCredentials[2],$badUserCredentials[3]);
        
        
    }

    public function test_ShouldReturnStringWhenFunctionWasInvoked()
    {
        $conn = new App\ConnectToDatabase;
        $sql = $conn -> connAdminPass();
        $status = $sql->stat();
        $this -> assertStringContainsString("Uptime", $status);
        unset($conn); 
    }
}


?>