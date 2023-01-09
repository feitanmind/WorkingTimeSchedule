class User
{
    static AddUserShow()
    {
        document.getElementById("UserAdd_UserModule").style.display = 'flex';
        document.getElementById("RemoveUser_UserModule").style.display = 'none';

        document.getElementById("User_Add").style.backgroundColor = 'white';
        document.getElementById("User_Remove").style.backgroundColor = '#cccccc';
    }
    static RemoveUserShow()
    {
        document.getElementById("UserAdd_UserModule").style.display = 'none';
        document.getElementById("RemoveUser_UserModule").style.display = 'flex';

        document.getElementById("User_Add").style.backgroundColor = '#cccccc';
        document.getElementById("User_Remove").style.backgroundColor = 'white';
    }
}