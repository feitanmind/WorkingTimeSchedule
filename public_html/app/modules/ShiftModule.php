<?php namespace App; ?>
<div id="tabsShift">
        <div id="Shift_Add" class="tabshifts" onclick="Shift.AddShiftShow();">Add Shift</div>
        <div id="Shift_Remove" class="tabshifts" onclick="Shift.RemoveShiftShow();">Remove Shift</div>
    </div>
<div class="ShiftModule" id="AddShiftModule">
    
    <div class="ShiftForm">
        <h2>Add new Shift!</h2>
        <form method="post" id="addShiftFormPost">
            <p>Name of new Shift</p>
            <input type="text" class="inputAddShift" name="nameOfShift_addShift" id="nameOfShift_addShift"/>
            <p>Start hour of new Shift</p>
            <input type="text" class="inputAddShift" name="startHour_addShift" id="startHour_addShift" placeholder="Example: 07:00"/>
            <p>End hour of new Shift</p>
            <input type="text" class="inputAddShift" name="endHour_addShift" id="endHour_addShift" placeholder="Example: 15:00"/>
            <?php
            if($_SESSION['Current_User_Role_Id'] == 1)
            {
                $accessConnection = ConnectToDatabase::connUserPass();
                $sql = "SELECT id, name FROM department;";
                $result = $accessConnection->query($sql);
                if($result->num_rows > 0)
                {
                    echo "<p>Select Department</p>";
                    echo "<select name=\"departmentId_addShift\" id=\"selectDep\">";
                    $id_first = true;
                    while($row = $result->fetch_assoc())
                    {
                        $id = $row['id'];
                        $name = $row['name'];
                        if($id_first)
                        {
                            echo "<option value=\"$id\" selected>$id - $name</option>";
                            $id_first = false;
                        }
                        else
                        {
                            echo "<option value=\"$id\">$id - $name</option>";
                        }
                        
                    }
                    echo "</select>";
                }
            }
            echo '<div name="addNewShift" class="button1 ShiftSubmit" onclick="Shift.AddShiftVerify();">Add shift</div>';
            ?>
        </form>
    </div>
</div>

<div class="ShiftModule" id="RemoveShiftModule">
    
    <div class="ShiftForm">
        <h2>Remove Shift!</h2>
        <form method="post">
            <p>Select shift to delete</p>
            <?php
            $currentUserRole = $_SESSION['Current_User_Role_Id'];
            if($currentUserRole == 1 || $currentUserRole == 2)
            {
                $accessConnection = ConnectToDatabase::connAdminPass();
                $depId = $_SESSION['Current_User_Department_Id'];
                $sql = $currentUserRole == 1 ? "SELECT id, name, dep_id, startHour, endHour, color FROM shifts;" : "SELECT id, name, dep_id, startHour, endHour, color FROM shifts WHERE dep_id=$depId;";
                
                $result = $accessConnection->query($sql);
                if($result->num_rows > 0)
                {
                    $id_first = true;
                    echo "<select name=\"Id_removeShift\" id=\"selectToRemoveShift\" multiple>";
                    while($row = $result->fetch_assoc())
                    {
                        $id = $row['id'];
                        $name = $row['name'];
                        $starHour = $row['startHour'];
                        $endHour = $row['EndHour'];
                        if($id_first)
                        {
                            echo "<option value=\"$id\" selected=\"selected\">$id - $name - Starts: $startHour, Ends: $endHour</option>";
                            $id_first = false;
                        }
                        else
                        {
                            echo "<option value=\"$id\">$id - $name - Starts: $startHour, Ends: $endHour</option>";
                        }
                    }
                    echo "</select>";
                }
            }
            echo '<input type="submit" name="removeShift" class="button1 ShiftSubmit" value="Remove"/>'
            ?>
        </form>
    </div>
</div>