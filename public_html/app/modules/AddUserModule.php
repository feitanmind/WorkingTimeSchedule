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
                                <option value="2">Nurse</option>
                                <option value="3">NotNurse</option>
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
                                <input type="file" style="display:none;" name="maleDefault" value="/var/www/test/public_html/app/style/img/user.png"/>
                                <input type="file" style="display:none;" name="femaleDefault" value="/var/www/test/public_html/app/style/img/user2.png"/>
                            <script>
                                 $('#gender').change(function() {
                                    if($(this).val() === 'female' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/user2.png';
                                    }
                                    if($(this).val() === 'male' && changedAv == 0){
                                        userAvatarShow.src = 'style/img/user.png';
                                    }
                                });

                            </script>
                            Temporary password:
                            <input type="password" name="addu_password"/>
                            
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
                    isset($_POST['addu_fulltime']) 
                )
                {
                    if(isset($_POST['addu_userAvatar']))
                    {
                        $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $_POST['addu_userAvatar'], $_POST['addu_custom_id'], $_POST['addu_fulltime']);
                    }
                    else if(isset($_POST['gender']))
                    {
                        if($_POST['gender'] == "male")
                        {
                            //trzeba pomyśleć czemu nie działa dodawanie s
                            $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $_POST['maleDefault'], $_POST['addu_custom_id'], $_POST['addu_fulltime']);
                        }
                        else
                        {
                            $user->addUser($_POST['addu_login'], $_POST['addu_password'], $_POST['addu_email'], $_POST['addu_name'], $_POST['addu_surname'], $user->dep_id, $_POST['addu_role'], $_POST['femaleDefault'], $_POST['addu_custom_id'], $_POST['addu_fulltime']);
                        }

                    }   
                }
            ?>