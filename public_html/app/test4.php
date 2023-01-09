<form method="post" enctype="multipart/form-data">
<input type="file"
       id="avatar" name="avatar"
       accept="image/png, image/jpeg, image/jpg">
    <input type="submit" name="submit" value="submit"/>
</form>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_FILES['avatar']))
{
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/app/style/img/avatars/";
    $target_file = $target_dir . "xdd". '-avatar';
    $check = $_FILES['avatar']['size'];


    if($check == 0)
    {
        $thumb = imagecreatetruecolor(600, 400);
        print_r($_FILES['avatar']);
        $source = imagecreatefromjpeg($_FILES['avatar']['full_path']);
        list($width, $height) = getimagesize($filename);
        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, 0, 0);

        // Output
        $imup = imagejpeg($thumb);
        print_r($imup);
        move_uploaded_file($imup, $target_file);
    }
    else
    {
        echo "helo";
        print_r($_FILES);
        move_uploaded_file($_FILES["avatar"]['tmp_name'], $target_file);
    }
    
    //$check = getimagesize($_POST['av']['tmp']);
    echo 'check:'.$check;
}


?>