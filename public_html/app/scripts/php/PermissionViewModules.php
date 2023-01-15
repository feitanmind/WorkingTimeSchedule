<?php
namespace App;
    if($_SESSION['Current_User_Role_Id'] == 1)
    {
        echo '
        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'flex\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     ">
            Calendar Mode
        </div>

        <div class="button1"
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'flex\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    ">
            Roles
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'UserModule\').style.display = \'block\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     
                     ">
            Users
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'flex\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    
                    ">
            
            Shifts
        </div>

        <div class="button1" 
        onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                 document.getElementById(\'UserModule\').style.display = \'none\'
                 document.getElementById(\'ShiftModule\').style.display = \'none\';
                 document.getElementById(\'rolesModule\').style.display = \'none\';
                 document.getElementById(\'addDepartmentModule\').style.display = \'flex\';
                
                ">
        
        Add department
        </div>
        ';
    }
    else if($_SESSION['Current_User_Role_Id'] == 2)
    {
        echo '
        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'flex\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     ">
            Calendar Mode
        </div>

        <div class="button1"
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'flex\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    ">
            Roles
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'UserModule\').style.display = \'block\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     
                     ">
            Users
        </div>

        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'none\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'flex\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                    
                    ">
            
            Shifts
        </div>

        ';
    }
    else
    {
        echo '
        <div class="button1" 
            onclick="document.getElementById(\'calendarMode\').style.display = \'flex\';
                     document.getElementById(\'UserModule\').style.display = \'none\'
                     document.getElementById(\'ShiftModule\').style.display = \'none\';
                     document.getElementById(\'rolesModule\').style.display = \'none\';
                     document.getElementById(\'addDepartmentModule\').style.display = \'none\';
                     ">
            Calendar Mode
        </div>
        ';
    }




?>