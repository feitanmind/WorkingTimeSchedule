<?php
namespace App;
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Encrypt
{
    private $cipher = "aes-256-cbc"; 
    private $encryption_key = "h^frdd#21!!cdw";
    private $iv_size = 16;
    private $iv = 'dsadadas8f3ed6ft'; 
    public $cipherAES;
    function __construct()
    {
        $cipherAES = array($this-> cipher,$this->encryption_key, $this->iv_size, $this->iv);
        $this -> cipherAES = $cipherAES;
    }
    //need a test
    function encryptString($stringToEncrypt)
    {
        $cipher = "aes-256-cbc"; 
        $encryption_key = "h^frdd#21!!cdw";
        $iv_size = openssl_cipher_iv_length($cipher);
        $iv = 'dsadadas8f3ed6ft'; 
        $encryptedData = openssl_encrypt($stringToEncrypt, $cipher, $encryption_key, 0, $iv); 
        $encryptedData = str_replace("+","U002B",
                         str_replace("/","U2215",
                         str_replace("=","U003D",
                         htmlentities($encryptedData, ENT_QUOTES,"UTF-8"))));

        return $encryptedData;
    }
    //need a test
    function decryptString($stringToDecrypt)
    {
        $cipher = "aes-256-cbc"; 
        $encryption_key = "h^frdd#21!!cdw";
        $iv_size = openssl_cipher_iv_length($cipher);
        $iv = 'dsadadas8f3ed6ft'; 
        $decryptedData = openssl_decrypt(
                         str_replace("U002B","+",
                         str_replace("U2215","/",
                         str_replace("U003D","=",
                         htmlentities($stringToDecrypt, ENT_QUOTES,"UTF-8")))),$cipher, $encryption_key, 0, $iv);
        return $decryptedData;
    }

}
?>