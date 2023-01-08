<?php
namespace App;

        switch($_SESSION['Module'])
        {
            case 1:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'flex';
                        document.getElementById('ShiftModule').style.display = 'none'
                        document.getElementById('addDepartmentModule').style.display = 'none';
                    </script>";
                break;
            case 2:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'none';
                        document.getElementById('ShiftModule').style.display = 'none';
                        document.getElementById('addDepartmentModule').style.display = 'none';
                    </script>";
                break;
            case 3:
                echo "<script>
                        document.getElementById('addUser').style.display = 'block';
                        document.getElementById('calendarMode').style.display = 'none';
                        document.getElementById('ShiftModule').style.display = 'none';
                        document.getElementById('addDepartmentModule').style.display = 'none';
                    </script>";
                break;
            case 4:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'none';
                        document.getElementById('ShiftModule').style.display = 'flex';
                        document.getElementById('addDepartmentModule').style.display = 'none';
                    </script>";
                break;
            case 5:
                echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'none';
                         document.getElementById('ShiftModule').style.display = 'none';
                        document.getElementById('addDepartmentModule').style.display = 'flex';
                     </script>";
                break;
            default:
            echo "<script>
                        document.getElementById('addUser').style.display = 'none';
                        document.getElementById('calendarMode').style.display = 'flex';
                        document.getElementById('ShiftModule').style.display = 'none';
                        document.getElementById('DepartmentModule').style.display = 'none';
                </script>";
                
        }

?>