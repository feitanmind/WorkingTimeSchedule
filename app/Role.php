<?php
namespace App;
class Role
{
    public static function displaySelectRolesForUser()
    {
        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $sqlSelectAllRoles = "SELECT * FROM roles;";
        $res = $mysqliAdm->query($sqlSelectAllRoles);
        echo '<form method="post">';
        echo '<select name="roleID">';

        while($row = $res->fetch_assoc())
        {
            echo '<option onclick="this.form.submit();" value='.$row['id'].">".$row['name'].'</option>';
        }
        echo '</select>';
        echo '</form>';

        $res->free();
        unset($conn);
                
    }

}


?>