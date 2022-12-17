<?php
namespace App;
class Role
{
    //Generuje formularz do wyboru dla której roli będzie ustalany grafik
    public static function displaySelectRolesForUser()
    {
        //Połączenie z bazą danych
        $access_Connection = ConnectToDatabase::connAdminPass();
        //Zapytanie SQL wybierające wszystkie dostępne role
        $sql_Query_Selection = "SELECT * FROM roles;";
        //Przypisanie reszultatu wykoannia zapytania
        $result_Of_Selection = $access_Connection->query($sql_Query_Selection);
        //Stworzenie formularza w HTMLu 
        echo '<form method="post">';
        echo '<select id="selectRoleToShow" class="calendarFilterSelect" name="roleID">';

        while($row = $result_Of_Selection->fetch_assoc())
        {
            if($_SESSION["Role_Id"] == $row['id'])
            {
                echo '<option onclick="this.form.submit();" selected="selected" value='.$row['id'].">".$row['name'].'</option>';
            }
            else
            {
                echo '<option onclick="this.form.submit();" value='.$row['id'].">".$row['name'].'</option>';
            }
            //Stworzenie opcji w formularzu które po kliknięciu będą wysyłały wybraną opcję
            
        }
        echo '</select>';
        echo '</form>';              
    }

}


?>