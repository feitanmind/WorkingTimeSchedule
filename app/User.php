<?php
namespace App;
require "ConnectToDatabase.php";
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
            $userCredentials = '<div id="nameAndSurname">'.$this->name. ' '. $this->surname. '</div>'
            .'<p>UserID: '.$this->user_id.'</p>'
            .'<p>Department: '.$this->department.'</p>'
            .'<p>Role: '.$this->role.'</p>'
            ;


            return $userCredentials;
        }




    }
?>