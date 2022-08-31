<!DOCTYPE html>
<html>
<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Working time schedule</title>
</head>
<body>
    <?php
        echo "Witaj ". $_SESSION['username'] . "<br>";

    ?>
    <form action="Logout.php">
        <input type="submit" value="logout"/>
    </form>
</body>
</html>