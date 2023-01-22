<?php namespace App;?>
<div class="AddDepartmentModule" id="AddDepartmentModule">
    <div class="AddDepartmentForm">
        <h2>Add new Department</h2>
        <div class="AddDepartmentFormBody">
            <div class="ShowAllDepartments">
                <h3>List of current Departments</h3>
                <div class="listOfCurrentDepartment">
                    <?php
                    $accessConnection = ConnectToDatabase::connUserPass();
                    $sql = "SELECT name FROM department";
                    $result = $accessConnection->query($sql);
                    echo "<ul>";
                    while($row = $result->fetch_assoc())
                    {
                        echo "<li>".$row['name']."</li>";
                    }
                    echo "</ul>";
                    ?>
                </div>
            </div>
            <form method="post">
                <h3>Enter new department</h3>
                <input type="text" class="inputAddDepartment" name="nameOfAddingDepartment"/>
                <input type="submit" class="button1 submitAddDepartment" value="Add Department"/>
            </form>
        </div>
    </div>

</div>