<?php
use App\ConnectToDatabase;
use \Exception as Ex;
?>
<div id="tabsUsers">
        <div id="User_Add" class="tabusers" onclick="User.AddUserShow();">Add User</div>
        <div id="User_Remove" class="tabusers" onclick="User.RemoveUserShow();">Remove User</div>
</div>


<form method="post" id="UserAdd_UserModule" class="formUserModule" enctype="multipart/form-data">
                <h2>Add new user</h2>
                Name: <input type="text" name="addu_name" id="addu_name" onclick="this.style.backgroundColor='white';"/>
                Surname: <input type="text" name="addu_surname" id="addu_surname" onclick="this.style.backgroundColor='white';"/>
                Login: <input type="text" name="addu_login" id="addu_login" onclick="this.style.backgroundColor='white';"/>
                Email: <input type="text" name="addu_email" id="addu_email" onclick="this.style.backgroundColor='white';"/>
                CustomID: <input type="number" name="addu_custom_id" id="addu_custom_id" onclick="this.style.backgroundColor='white';"/>
                FullTime:   <select name="addu_fulltime">
                                <option value="1">Full Time (1)</option>
                                <option value="0.5">Half (1/2)</option>
                                <option value="0.6">Three-Fifths (3/5)</option>
                                <option value="0.8">Four Fifths (4/5)</option>
                            </select><br>
                            Role:
                            <select id="role" name="addu_role">
                                <?php
                                
                                $accessConnection = ConnectToDatabase::connAdminPass();
                                $sql = $_SESSION['Current_User_Role_Id'] == 1 ? 'SELECT id, name FROM roles;' : 'SELECT id, name FROM roles WHERE NOT id = 1;';
                                $result = $accessConnection->query($sql);
                                while($row = $result->fetch_assoc())
                                {
                                    echo "<option value=\"".$row['id']."\">".$row['name']."</option>";
                                }
                                $result->free();
                                
                                ?>
                            </select>
                            <?php
                                if($_SESSION['Current_User_Role_Id'] == 1)
                                {
                                    $resultDepartment = $accessConnection->query("SELECT id,name FROM department;");
                                    echo '<p>Department</p>';
                                    echo '<select name="newUserDepId" style="margin-bottom: -5vh;">';
                                            while($rowDep = $resultDepartment->fetch_assoc())
                                            {
                                                echo '<option value="'.$rowDep['id'].'">'.$rowDep['name'].'</option>';
                                            }
                                    echo '</select>';
                                    
                                }

                            ?>
                        <p>Avatar:</p>
                        <img id="userAvatarShow" src="style/img/user.png" alt="your image" />
                            <input accept="image/*" name="addu_userAvatar" type='file' id="addu_userAvatar" />
                            
                            <script>
                                var changedAv = 0;
                                addu_userAvatar.onchange = evt => {
                                const [file] = addu_userAvatar.files
                                if (file)
                                {
                                    userAvatarShow.src = URL.createObjectURL(file);
                                    changedAv = 1;
                                }
                                }
                               
                            </script>
                            Gender:
                            <select id="gender" name="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            Hours per Shift:
                            <select id="hps" name="addu_hps">
                                <option value="08:00:00">8</option>
                                <option value="07:35:00">7:35</option>
                            </select>
                            <script>
                                 $('#gender').change(function() {
                                    if($(this).val() === 'female' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/avatars/default2.png';
                                    }
                                    if($(this).val() === 'male' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/avatars/default1.png';
                                    }
                                });

                            </script>
                            Temporary password:
                            <input type="password" name="addu_password" id="addu_password" onclick="this.style.backgroundColor='white';"/>
                            <input type="text" name="changeModule" value="3" style="display: none;"/>
                            <div id="addnewUser_adduInput" class="button1" onclick="User.VerificationAddForm();">Add user</div>
                            <!-- <input type="submit" value="Add user" id="addnewUser_adduInput" class="button1"/> -->
            </form>


            <form method="post" id="RemoveUser_UserModule" class="formUserModule">
            <h2>Remove user</h2>
            <h3>Select user to Remove</h3>
            <?php
            $currentUserRole = $_SESSION['Current_User_Role_Id'];
            $currentUserDepartment = $_SESSION['Current_User_Department_Id'];
            echo "<select id=\"userToRemoveFromSystem\" name=\"userToRemoveFromSystem\">";
                $accessConnection = ConnectToDatabase::connAdminPass();
           if($currentUserRole == 1)
            {
                $sql = 'SELECT user_data.name AS nameOfUser, users.login, user_data.surname, user_data.usr_id , department.name AS dep , roles.name AS role
                        FROM (((user_data
                        INNER JOIN department ON user_data.dep_id)
                        INNER JOIN roles ON user_data.role_id = roles.id)
                        INNER JOIN users ON user_data.usr_id = users.id);';
                $result = $accessConnection->query($sql);
                while($row = $result->fetch_assoc())
                {
                    echo '<option value="' . $row['usr_id'] .'!'.$row['login'].'">'.$row['usr_id'] . ' '.$row['nameOfUser']. ' '.$row['surname'].' | '.$row['dep'].' | '.$row['role'].'</option>';
                }
            }
            else
            {
                $sql = 'SELECT user_data.name AS nameOfUser, user_data.surname, user_data.usr_id , roles.name AS role
                FROM ((user_data
                INNER JOIN roles ON user_data.role_id = roles.id)
                INNER JOIN users ON user_data.usr_id = users.id) 
                WHERE user_data.dep_id='.$currentUserDepartment.';';

                $result = $accessConnection->query($sql);
                while($row = $result->fetch_assoc())
                {
                    echo '<option value="' . $row['usr_id'] .'!'.$row['login'].'">'.$row['usr_id'] . ' '.$row['nameOfUser']. ' '.$row['surname'].' | '.$row['role'].'</option>';
                }

            }
                echo "</select>";
                

            ?>
            <input type="submit" value="Remove user" id="submitRemoveUser" class="button1"/>
            </form>



            <?php
            
                if
                (
                    isset($_POST['addu_name']) &&
                    isset($_POST['addu_surname']) && 
                    isset($_POST['addu_login']) && 
                    isset($_POST['addu_email']) && 
                    isset($_POST['addu_custom_id']) && 
                    isset($_POST['addu_password']) && 
                    isset($_POST['addu_role']) && 
                    isset($_POST['addu_fulltime']) &&
                    isset($_POST['addu_hps'])
                )
                {
                    
                    if(isset($_FILES['addu_userAvatar']))
                    {
                        if($_FILES['addu_userAvatar']['size'] != 0)
                        {
                            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/app/style/img/avatars/";
                            $nameOfFile = $_POST['addu_login'] . '-avatar'. substr($_FILES['addu_userAvatar']['name'],strpos($_FILES['addu_userAvatar']['name'],"."));
                            $target_file = $target_dir . $nameOfFile;
                     
                            if ($_FILES["addu_userAvatar"]["size"] < 5000000) {
                                move_uploaded_file($_FILES["addu_userAvatar"]['tmp_name'], $target_file);
                                $avstr = $nameOfFile;
                            }
                        }
                        else $avstr = $_POST['gender'] == "male" ? "default1.png" : "default2.png";
                           
                    }
                    else $avstr = $_POST['gender'] == "male" ? "default1.png" : "default2.png";
    
                    $departmentId = $user->dep_id;
                    if(isset($_POST['newUserDepId'])) if($_POST['newUserDepId'] != "") $departmentId = $_POST['newUserDepId'];
                  
                    $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $departmentId, $_POST['addu_role'], $avstr, $_POST['addu_custom_id'], $_POST['addu_fulltime'], $_POST['addu_hps']);

                    
                    $_SESSION['Module'] = 3;
                }
            ?>