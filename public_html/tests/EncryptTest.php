<?php
    class EncryptTest extends \PHPUnit\Framework\TestCase
    {
        public function testCheckEncryptionReturn()
        {
            $ci = new App\Encrypt();
            $this->assertEquals("aes-256-cbc",$ci-> cipherAES[0]);
        }

        public function testEncriptonWorksProprely()
        {
            $ci = new App\Encrypt();
            $this->assertEquals("Nv51daHO4hxgW6n0xS0pmAU003DU003D",$ci->encryptString("secret"));
        }
        public function testDecriptionWorksProprely()
        {
            $ci = new App\Encrypt();
            $this-> assertEquals("secret",$ci->decryptString("Nv51daHO4hxgW6n0xS0pmAU003DU003D"));
        }


    }



?>