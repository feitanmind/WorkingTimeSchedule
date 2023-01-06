<?php session_start();

if(isset($_SESSION['header']) && isset($_SESSION['log']))
{
    if($_SESSION['log'] == true)
    {
       header("Location: /../app");
    }
    else
    {
        echo "
        <script>
            let x = document.cookie;
            let pl = x.search('pl');
            if(pl == -1)
            {
                document.location = 'en-US/';
            }
            else
            {
                document.location = 'pl-PL/';
            }

        </script>
        ";
    }
}
else
{
    header("Location: en-US/");
}

?>
