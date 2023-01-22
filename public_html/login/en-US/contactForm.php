
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #problemFormBody
        {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1vw;
            margin: 0;
            padding: 0;
            padding-top: 10vh;
        }
        #problemForm
        {
            width: 70vw;
            height: 80vh;
            background-color: #0A85ED;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 1vw;

        }
        #problemform_Name
        {
            all: unset;
            outline: 0.1vw solid black;
            width: 80%;
            height: 5vh;
            background-color: white;
        }
        #problemform_Problem
        {
            all: unset;
            border: 0.1vw solid black;
            width: 80%;
            height: 60vh;
            background-color: white;
        }
        #problemform_submit
        {
            all: unset;
            width: 15vw;
            height: 5vh;
            background-color: lightgrey;
            text-align: center;
            margin-top: 2vh;
        }
    </style>
</head>
<?php
if(isset($_POST['problemform_Name']))
{
    $reportingUser = $_POST['problemform_Name'];
    $issue = $_POST['problemform_Problem'];

    $mysqli = new \mysqli("localhost", "root", "niemahasla", "app_commercial");
    try
    {
        $mysqli->query("INSERT INTO issues(name,issue) VALUES('$reportingUser','$issue');");
        echo '<h2>Thanks for sending issue</h2>';
    }
    catch(\Exception $e)
    {
        echo '<h2>Error</h2>';
    }
    



    
    echo 'redirecting to login site...';

    echo "<script>window.setTimeout(function() {location.href = \"/../\"}, 2000);</script>";
}
else
{
    echo '
<body id="problemFormBody">
    <form method="post" id="problemForm">
        <input type="text" name="problemform_Name" id="problemform_Name" placeholder="Write your name"/><br>
        <input type="text" name="problemform_Problem" id="problemform_Problem" placeholder="Describe your problem"/>
        <input type="submit" id="problemform_submit" value="Send your problem"/>
    </form>
</body>

';
}



?>


</html>