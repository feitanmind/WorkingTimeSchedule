<?php
    class EncryptTest extends \PHPUnit\Framework\TestCase
    {
        public function testCheckEncryptionReturn()
        {
            $ci = new App\Encrypt();
            $this->assertEquals("aes-256-cbc",$ci-> cipherAES[0]);
        }
    }



?>