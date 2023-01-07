<?php namespace App; ?>

<div class="AddShiftModule" id="AddShiftModule">
    <div class="AddShiftForm">
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