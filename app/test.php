<?php
namespace App;
    require "Encrypt.php";

    $en = new Encrypt();
    $ab = $en -> encryptString("secret");
    echo $ab;



?>