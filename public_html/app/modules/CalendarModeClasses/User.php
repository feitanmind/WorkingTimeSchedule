<?php
namespace App;
use \Exception as Ex;

    class User
    {
        public $name;
        public $surname;
        private $encrypted_password;
        public $email;
        public $department;
        public $role;
        public $avatar;
        public $user_id;
        public $custom_id;
        public $full_time;
        public $dep_id;
        public $role_id;
    public $hours_per_shift;


        // Wyciąganie nazwy z tabeli po id
        static private function selectName($id, $tableName)
        {
            //Połączenie z bazą danych
            $access_Connection = ConnectToDatabase::connAdminPass();
            //Zapytanie SQL do bazy 
            $sql = "SELECT name FROM $tableName WHERE id=$id";
            $res = $access_Connection -> query($sql);
            //zamienienie wyniku na tablicę asocjacyjną
            $row = $res->fetch_assoc();
            //Wyciągnięcie wyniku pod indexem name
            $nameOfObj = $row['name'];
            //Zwrócenie wartości
            return $nameOfObj;

        }

        public function __construct($user_id)
        {
            //Połączenie z bazą danych
            $access_Connection = ConnectToDatabase::connAdminPass();

            //Zapytanie do bazy danych w języku SQL 
            $sql_Query_Selection= "SELECT * FROM users INNER JOIN user_data ON users.id = user_data.usr_id WHERE users.id = $user_id";
            //Wywołanie polecenia poprzez połączenie z poświadczeniami Administratora
            $result_User_Data = $access_Connection -> query($sql_Query_Selection);

            //Sprawdzenie czy nie otrzymaliśmy pustej odpowiedzi sprawdzając pole obiektu zawierające liczbę wierszy odpowiedzi
            if($result_User_Data->num_rows > 0)
            {
                //Pewność że otrzymamy tylko jeden wiersz gwarantuje nam że nie musimy zapętlać całej operacji
                //Konwertujemy odpowiedź bazy danych na tablicę asocjacyjną
                $row = $result_User_Data->fetch_assoc();
                $this->name =                   $row['name'];
                $this->surname =                $row['surname'];
                $this->encrypted_password =     $row['password'];
                $this->email =                  $row['email'];
                $this->avatar =                 $row['avatar'];
                $this->user_id =                $row['id'];
                $this->custom_id =              $row['custom_id'];
                $this->full_time =              $row['full_time'];
                $this->dep_id =                 $row['dep_id'];
                $this->hours_per_shift =        $row['hours_per_shift'];
                $dep_id =                       $row['dep_id'];
                $role_id =                      $row['role_id'];
                $this->role_id = $row['role_id'];
                $this->department =             $this->selectName($dep_id,'department');
                $this->role =                   $this->selectName($role_id,'roles');      
            }
            else
            {
                echo "Error: Can't find user in database";
            }
        }

        // Zwraca dane użytkownika 
        public function getUserData()
        {
            //data:image/png;base64,'.base64_encode($this->avatar).'
            $userAvatar = $this->avatar;
            $userCredentials = '<img src="/app/style/img/avatars/'.$userAvatar.'"/>'
            .'<h2>'.$this->name. ' '. $this->surname. '</h2>'
            .'<p>UserID: '.$this->user_id.'</p>'
            .'<p>Department: '.$this->department.'</p>'
            .'<p>Role: '.$this->role.'</p>'
            ;


            return $userCredentials;
        }

        public function createOptionsListOfAllUsers()
        {
            // Tworzymy nowe połączenie do bazy danych 
            $access_Connection = ConnectToDatabase::connAdminPass();
            $role_id = $_SESSION['Role_Id'];
            $selectListOfUserSQL = "SELECT name, surname, usr_id FROM user_data WHERE dep_id = $this->dep_id AND role_id = $role_id";
            $resultWorkersData = $access_Connection -> query($selectListOfUserSQL);
            // echo '<select id="usersToAdd" style="width: 100%;height:80%; font-size: 1vw;" name="usersToAdd[]" multiple="multiple">';
            while($row = $resultWorkersData->fetch_assoc())
            {
                echo '<option value="'.$row['usr_id'].'">'.$row['name']. ' '. $row['surname'].'</p></option>';
            }
            // echo '</select>';
        }


        public function addUser($login, $password, $email, $name, $surname, $dep_id, $role_id, $avatar, $custom_id, $full_time, $hoursPerShift)
        {
            // Tworzymy nowe połączenie do bazy danych 
            $access_Connection = ConnectToDatabase::connAdminPass();
            
            $cipher = new Encrypt;
            try
            {
                $encrypted_password = $cipher->encryptString($password);
            $sqlCreateUserInDataBase = "CREATE USER '$login'@'localhost' IDENTIFIED BY '$password'";
            $sqlInsertIntoUsers = "INSERT INTO users(login,email,password) VALUES('$login','$email','$encrypted_password');";
            $selectUserId = "SELECT id FROM users WHERE login = '$login'";



                $access_Connection->query($sqlCreateUserInDataBase);
                $access_Connection->query($sqlInsertIntoUsers);

                $resultIdOfUser = $access_Connection ->query($selectUserId);
                $row = $resultIdOfUser->fetch_assoc();
                $idOfUser = $row['id'];
                //$sqlInsertIntoUserData = "INSERT INTO user_data(name,surname,dep_id,role_id,avatar,usr_id,custom_id, full_time, hours_of_work, days_of_holiday) VALUES('$name','$surname',$dep_id,$role_id,'$avatar',$idOfUser,$custom_id,$full_time,'[]','[]');";

                $sqlInsertIntoUserData = "INSERT INTO app_commercial.user_data".
                "(name, surname, dep_id, role_id, avatar, usr_id, custom_id, full_time, hours_of_work, days_of_vacation, hours_per_shift)".
                "VALUES('$name', '$surname', $dep_id, $role_id, '$avatar', $idOfUser, $custom_id, $full_time, '[]', '[]', '$hoursPerShift');";
   


                $access_Connection->query($sqlInsertIntoUserData);
                $xmlFile = fopen("templatesNotification.xml", "r");
                $tempateNotyfication = fread($xmlFile,filesize("templatesNotification.xml"));
                echo "<script>";
                    echo 'window.history.pushState({}, document.title, "/" + "app/");';
                    echo "Notification.displayNotification(`$tempateNotyfication`,TypeOfNotification.Success,SubjectNotification.UserWasAdded);";
                echo "</script>";
            }
            catch(Ex $e)
            {
                
            }
             
                

        }


        public function createListOfUsersToCheckStatistics()
        {
            $accessConnection = ConnectToDatabase::connAdminPass();
            $sql = "SELECT name, surname, usr_id, ";
            
        }



    }
?>