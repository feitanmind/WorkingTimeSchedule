<?php
        if (isset($_POST['changeModule']))
        {
            $_SESSION['Module'] = $_POST['changeModule'];
        }
        switch(intval($_SESSION['Module']))
        {
            case 1:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'flex';
                        document.getElementById('addShiftModule').style.display = 'none';
                    </script>";
                break;
            case 2:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'none';
                        document.getElementById('addShiftModule').style.display = 'none';
                    </script>";
                break;
            case 3:
                echo "<script>
                        document.getElementById('addUser').style.display = 'block';
                        document.getElementById('calendarMode').style.display = 'none';
                        document.getElementById('addShiftModule').style.display = 'none';
                    </script>";
                break;
            case 4:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'none';
                        document.getElementById('addShiftModule').style.display = 'flex';
                    </script>";
                break;
            default:
            echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'flex';
                        document.getElementById('addShiftModule').style.display = 'none';
                </script>";
                
        }

?>