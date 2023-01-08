<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style/style_Notifications.css"/>
</head>
<body>
<script src="scripts/EnumClasses.js"></script>
    <script src="scripts/Notification.js"></script>
    <?php 
        $xmlFile = fopen("templatesNotification.xml", "r");
        $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
        echo "<script>";
            echo 'window.history.pushState({}, document.title, "/" + "app/");';
            echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Error,SubjectNotification.AddShiftFailed);";
        echo "</script>";

    ?>
</body>
</html>