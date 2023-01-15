<?php namespace App; ?>
<div id="tabsRoles">
    <div id="Role_Add" class="tabroles" onclick="Role.AddRoleShow();">Add Role</div>
    <div id="Role_Remove" class="tabroles" onclick="Role.RemoveRoleShow();">Remove Role</div>
</div>

<form class="AddNewRole boardRolesModule" id="AddRoleModule" method="post">
    <h2>Add new role</h2>
    <?php
        $roleCurrentUser = $_SESSION['Current_User_Role_Id'];
        $department = $_SESSION['Current_User_Department_Id'];
        $accessConnection = ConnectToDatabase::connAdminPass();
        if($roleCurrentUser == 1)
        {
            $sql = "SELECT id, name FROM roles;";
        }
        else
        {
            $sql = "SELECT id, name FROM roles WHERE dep_id = $department;";
        }
        $res = $accessConnection->query($sql);
        echo '<div id="listOfRolesRoleModule">';
        echo "<h3>List of roles for current department</h3>";
        while($r = $res->fetch_assoc())
        {
            echo "<p>Role id: ".$r["id"] . " Role name: ".$r["name"] . "</p>";
        }
        echo '</div>';
        echo 'Name of new role: <input type="text" id="nameOfNewRole" name="nameOfNewRole"/>';
        
        if($_SESSION['Current_User_Role_Id'] == 1)
        {
            echo 'As <select name="newRoleAsRoleDb" id="newRoleAsRoleDb">';
                echo '<option value="1">Administrator</option>';
                echo '<option value="2">Manager</option>';
                echo '<option value="3">Worker</option>';
            echo '</select>';
            
            $sqlDep = "SELECT id, name FROM department;";
            $resDep = $accessConnection->query($sqlDep);
            echo '<h3 id="newRoleDepId_header">Select department for new role</h3>';
            echo '<select name="newRoleDepId" id="newRoleDepId">';
            while($rowDep = $resDep->fetch_assoc())
            {
                echo '<option value="'.$rowDep['id'].'">'.$rowDep['name'].'</option>';
            }
            echo '</select>';
        }
        else
        {
            echo 'As <select name="newRoleAsRoleDb" id="newRoleAsRoleDb">';
                echo '<option value="2">Manager</option>';
                echo '<option value="3">Worker</option>';
            echo '</select>';
        }
        $res->free();
    ?>
    <input type="submit" class="button1" value="Add" />

</form>
<form class="RemoveRole boardRolesModule" method="post" id="RemoveRoleModule"style="display: none;">
    <h2>Remove role</h2>
    <?php
        if($roleCurrentUser == 1)
        {
            $sql = "SELECT id, name FROM roles;";
        }
        else
        {
            $sql = "SELECT id, name FROM roles WHERE dep_id = $department AND NOT role_db = 1;";
        }
        $res = $accessConnection->query($sql);
        echo '<select name="roleToRemove">';
        while($rowDepRem = $res->fetch_assoc())
        {
            echo '<option value="'.$rowDepRem['id'].'">Id '.$rowDepRem['id'].' Name '.$rowDepRem['name'].'</option>';
        }
        echo '</select>';
        
    ?>
    <input type="submit" class="button1" id="roleRemoveSubmit" value="Remove" />
</form>
