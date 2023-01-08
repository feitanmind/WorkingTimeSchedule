<?php namespace App; ?>
<div id="tabsShift">
        <div id="Shift_Add" class="tabshifts" onclick="Shift.AddShiftShow();">Add Shift</div>
        <div id="Shift_Remove" class="tabshifts" onclick="Shift.RemoveShiftShow();">Remove Shift</div>
    </div>
<div class="ShiftModule" id="AddShiftModule">
    
    <div class="ShiftForm">
        <h2>Add new Shift!</h2>
        <form method="post">
            <p>Name of new Shift</p>
            <input type="text" class="inputAddShift" name="nameOfShift_addShift"/>
            <p>Start hour of new Shift</p>
            <input type="text" class="inputAddShift" name="startHour_addShift" placeholder="Example: 07:00"/>
            <p>End hour of new Shift</p>
            <input type="text" class="inputAddShift" name="endHour_addShift" placeholder="Example: 15:00"/>
            <?php
            if($_SESSION['Role_Id'] == 1)
            {
                $accessConnection = ConnectToDatabase::connAdminPass();
                $sql = "SELECT id, name FROM department;";
                $result = $accessConnection->query($sql);
                if($result->num_rows > 0)
                {
                    echo "<p>Select Department</p>";
                    echo "<select name=\"departmentId_addShift\" id=\"selectDep\">";
                    while($row = $result->fetch_assoc())
                    {
                        $id = $row['id'];
                        $name = $row['name'];
                        echo "<option value=\"$id\">$id - $name</option>";
                    }
                    echo "</select>";
                }
            }
            echo '<input type="submit" name="addNewShift" class="button1 addShiftSubmit" value="Add shift"/>'
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
                    
                    echo "<select name=\"departmentId_removeShift\" id=\"selectToRemoveShift\" multiple>";
                    while($row = $result->fetch_assoc())
                    {
                        $id = $row['id'];
                        $name = $row['name'];
                        $starHour = $row['startHour'];
                        $endHour = $row['EndHour'];
                        echo "<option value=\"$id\">$id - $name - Starts: $startHour, Ends: $endHour</option>";
                    }
                    echo "</select>";
                }
            }
            echo '<input type="submit" name="removeShift" class="button1 ShiftSubmit" value="Remove"/>'
            ?>
        </form>
    </div>
</div>