<?php
class UserTest extends \PHPUnit\Framework\TestCase
{
    public function test_ShouldReturnNameOfUser()
    {
        $adam = new App\User(1);
        $this->assertEquals("Adam",$adam->name);
        $this->assertEquals("system",$adam->department);
    }
}




?>