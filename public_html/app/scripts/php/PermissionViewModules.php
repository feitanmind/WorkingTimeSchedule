<?php
namespace App;
    if($_SESSION['Current_User_Role_Id'] == 1)
    {
        echo '
        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'flex\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     ">
            Calendar Mode
        </div>

        <div class="button1"
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'flex\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    ">
            Simple Mode
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'block\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     
                     ">
            Add user
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'flex\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    
                    ">
            
            Add shift
        </div>

        <div class="button1" 
        onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                 document.getElementById(\'addUser\').style.display = \'none\'
                 document.getElementById(\'addShiftModule\').style.display = \'none\';
                 document.getElementById(\'simpleCalendar\').style.display = \'none\';
                 document.getElementById(\'addDepartmentModule\').style.display = \'flex\';
                
                ">
        
        Add department
        </div>
        ';
    }
    else if($_SESSION['Role_Id'] == 2)
    {
        echo '
        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'flex\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     ">
            Calendar Mode
        </div>

        <div class="button1"
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'flex\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    ">
            Simple Mode
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'block\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     
                     ">
            Add user
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'flex\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    
                    ">
            
            Add shift
        </div>

        ';
    }
    else
    {
        echo '
        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'flex\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     ">
            Calendar Mode
        </div>

        <div class="button1"
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'addUser\').style.display = \'none\'
                     document.getElementById(\'addShiftModule\').style.display = \'none\';
                     document.getElementById(\'simpleCalendar\').style.display = \'flex\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    ">
            Simple Mode
        </div>';
    }




?>