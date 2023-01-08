<form method="post" class="formAdduser">
                <h2>Add new user</h2>
                Name: <input type="text" name="addu_name"/>
                Surname: <input type="text" name="addu_surname"/>
                Login: <input type="text" name="addu_login"/>
                Email: <input type="text" name="addu_email"/>
                CustomID: <input type="text" name="addu_custom_id"/>
                FullTime:   <select name="addu_fulltime">
                                <option value="1">Full Time (1)</option>
                                <option value="0.5">Half (1/2)</option>
                                <option value="0.6">Three-Fifths (3/5)</option>
                                <option value="0.8">Four Fifths (4/5)</option>
                            </select><br>
                            Role:
                            <select id="role" name="addu_role">
                                <?php
                                use App\ConnectToDatabase;

                                $accessConnection = ConnectToDatabase::connAdminPass();
                                $sql = $_SESSION['Current_User_Role_Id'] == 1 ? 'SELECT id, name FROM roles;' : 'SELECT id, name FROM roles WHERE NOT id = 1;';
                                $result = $accessConnection->query($sql);
                                while($row = $result->fetch_assoc())
                                {
                                    echo "<option value=\"".$row['id']."\">".$row['name']."</option>";
                                }
                                $result->free();
                                unset($accessConnection);
                                ?>
                            </select>
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
                                <input type="file" style="display:none;" name="maleDefault" value="default.png"/>
                                <input type="file" style="display:none;" name="femaleDefault" value="default2.png"/>
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
                            <input type="password" name="addu_password"/>
                            <input type="text" name="changeModule" value="3" style="display: none;"/>
                            <!-- Trzeba dodać skrypt js do weryfikacji danych -->
                            <input type="submit" value="Add user" class="button1"/>
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
                    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/style/img/avatars/";
                    $target_file = $target_dir . $_POST['addu_login'] . '-avatar';
                    if(isset($_FILES['addu_userAvatar']['tmp_avatar']))
                    {
                        $check = getimagesize($_FILES['addu_userAvatar']['tmp_avatar']);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        if($check !== false) {
    
                        $uploadOk = 1;
                        } else {
                        $uploadOk = 0;
                        }
    
                        if ($_FILES["fileToUpload"]["size"] > 500000) {
                            $uploadOk = 0;
                        }
                    }
                    else
                    {
                        $uploadOk = 0;
                    }
                    
                   
                    if($uploadOk == 1)
                    {
                        move_uploaded_file($_FILES["fileToUpload"]["addu_userAvatar"], $target_file);
                        $avstr = $_POST['addu_login'] . '-avatar';
                    }
                    else
                    {
                        if($_POST['gender'] == "male")
                        {
                            $avstr = "default1.png";
                        }
                        else
                        {
                        $avstr = "default2.png";
                        }
                        
                    }


                    $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $avstr, $_POST['addu_custom_id'], $_POST['addu_fulltime'], $_POST['addu_hps']);


                    $_SESSION['Module'] = 3;
                }
            ?>