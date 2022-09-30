<?php
namespace App;

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

        // wyciąganie nazwy z tabeli po id
        static private function selectName($id, $tableName)
        {
            //połączenie z bazą danych
            $conn = new ConnectToDatabase;
            $connectionWithAdminCredentials = $conn-> connAdminPass();
            //zapytanie do bazy
            $sql = "SELECT name FROM $tableName WHERE id=$id";
            $res = $connectionWithAdminCredentials -> query($sql);
            //zamienienie wyniku na tablicę asocjacyjną
            $row = $res->fetch_assoc();
            //wyciągnięcie wyniku pod indexem name
            $nameOfObj = $row['name'];
            //zakończenie połączenia z bazą danych
            $res->free();
            unset($conn);
            //zwrócenie wartości
            return $nameOfObj;

        }

        public function __construct($user_id)
        {
            // Tworzymy nowe połączenie do bazy danych 
            $connectionToDatabase = new ConnectToDatabase;
            // Łączymy się przy pomocy poświadczeń administratora
            $connectionWithAdminCredentials = $connectionToDatabase -> connAdminPass();

            //Zapytanie do bazy danych w języku SQL 
            $selectAllUserData = "SELECT * FROM users INNER JOIN user_data ON users.id = user_data.usr_id WHERE users.id = $user_id";
            //Wywołanie polecenia poprzez połączenie z poświadczeniami Administratora
            $resultUserData = $connectionWithAdminCredentials -> query($selectAllUserData);

            //Sprawdzenie czy nie otrzymaliśmy pustej odpowiedzi sprawdzając pole obiektu zawierające liczbę wierszy odpowiedzi
            if($resultUserData->num_rows > 0)
            {
                //Pewność że otrzymamy tylko jeden wiersz gwarantuje nam że nie musimy zapętlać całej operacji
                //Konwertujemy odpowiedź bazy danych na tablicę asocjacyjną
                $row = $resultUserData->fetch_assoc();
                $this->name =                   $row['name'];
                $this->surname =                $row['surname'];
                $this->encrypted_password =     $row['password'];
                $this->email =                  $row['email'];
                $this->avatar =                 $row['avatar'];
                $this->user_id =                $row['id'];
                $this->custom_id =              $row['custom_id'];
                $this->full_time =              $row['full_time'];
                $this->dep_id =                 $row['dep_id'];
                $dep_id =                       $row['dep_id'];
                $role_id =                      $row['role_id'];

                $this->department =             $this->selectName($dep_id,'department');
                $this->role =                   $this->selectName($role_id,'roles');
                
                
            }
            else
            {
                echo "Error: Can't find user in database";
            }
            $resultUserData->free();
            unset($connectionToDatabase);

        }

        // Zwraca dane użytkownika 
        public function getUserData()
        {
            $userCredentials = '<img src="data:image/png;base64,'.base64_encode($this->avatar).'"/>'
            .'<h2>'.$this->name. ' '. $this->surname. '</h2>'
            .'<p>UserID: '.$this->user_id.'</p>'
            .'<p>Department: '.$this->department.'</p>'
            .'<p>Role: '.$this->role.'</p>'
            ;


            return $userCredentials;
        }

        public function getListOfUsers()
        {
            // Tworzymy nowe połączenie do bazy danych 
            $connectionToDatabase = new ConnectToDatabase;
            // Łączymy się przy pomocy poświadczeń administratora
            $connectionWithAdminCredentials = $connectionToDatabase -> connAdminPass();
            $selectListOfUserSQL = "SELECT name, surname, usr_id FROM user_data WHERE dep_id = $this->dep_id";
            $resultWorkersData = $connectionWithAdminCredentials -> query($selectListOfUserSQL);
            echo '<select id="usersToAdd" style="width: 100%;height:80%;" name="usersToAdd" multiple>';
            while($row = $resultWorkersData->fetch_assoc())
            {
                echo '<option value="'.$row['usr_id'].'">'.$row['name']. ' '. $row['surname'].'</option>';
            }
            echo '</select>';
            unset($connectionToDatabase);
        }

        public function addUser($login, $password, $email, $name, $surname, $dep_id, $role_id, $avatar, $custom_id, $full_time)
        {
            // Tworzymy nowe połączenie do bazy danych 
            $connectionToDatabase = new ConnectToDatabase;
            // Dodawanie szyfrowania 
            require_once "Encrypt.php";
            $cipher = new Encrypt;
            // Łączymy się przy pomocy poświadczeń administratora
            $connectionWithAdminCredentials = $connectionToDatabase -> connAdminPass();
            $encrypted_password = $cipher->encryptString($password);
            $sqlCreateUserInDataBase = "CREATE USER '$login'@'localhost' IDENTIFIED BY '$password'";
            $sqlInsertIntoUsers = "INSERT INTO users(login,email,password) VALUES('$login','$email','$encrypted_password');";
            $selectUserId = "SELECT id FROM users WHERE login = '$login'";


            try
            {
                $connectionWithAdminCredentials->query($sqlCreateUserInDataBase);
                $connectionWithAdminCredentials->query($sqlInsertIntoUsers);

                $resultIdOfUser = $connectionWithAdminCredentials->query($selectUserId);
                $row = $resultIdOfUser->fetch_assoc();
                $idOfUser = $row['id'];
                $sqlInsertIntoUserData = "INSERT INTO user_data(name,surname,dep_id,role_id,avatar,usr_id,custom_id, full_time, hours_of_work, days_of_holiday) VALUES('$name','$surname',$dep_id,$role_id,'$avatar',$idOfUser,$custom_id,$full_time,'[]','[]');";

                $connectionWithAdminCredentials->query($sqlInsertIntoUserData);
                
                unset($connectionToDatabase);
                return true;
                
            }
            catch (Exception $e)
            {
                unset($connectionToDatabase);
                return $e;
            }
        }



    }
?>