<form method="post" enctype="multipart/form-data">
<input type="file"
       id="avatar" name="avatar"
       accept="image/png, image/jpeg, image/jpg">
    <input type="submit" name="submit" value="submit"/>
</form>

<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// $xmlFile = fopen("templatesNotification.xml", "r");
//         $tempateNotification = fread($xmlFile,filesize("templatesNotification.xml"));
//         print_r($tempateNotification);


echo $_SERVER['SERVER_ADDR'];
?>