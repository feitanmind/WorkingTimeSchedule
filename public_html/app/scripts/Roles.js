class Role
{
    static AddRoleShow()
    {
        document.getElementById("AddRoleModule").style.display = 'block';
        document.getElementById("RemoveRoleModule").style.display = 'none';

        document.getElementById("Role_Add").style.backgroundColor = 'white';
        document.getElementById("Role_Remove").style.backgroundColor = '#cccccc';
    }
    static RemoveRoleShow()
    {
        document.getElementById("AddRoleModule").style.display = 'none';
        document.getElementById("RemoveRoleModule").style.display = 'block';

        document.getElementById("Role_Add").style.backgroundColor = '#cccccc';
        document.getElementById("Role_Remove").style.backgroundColor = 'white';
    }
    static AddRoleFormVerify()
    {
        let nameOfNewRole = document.getElementById("nameOfNewRole");
        if(nameOfNewRole.value == "")
        {
            nameOfNewRole.style.backgroundColor = "#e48080";
        }
        else
        {
            let formAddRole = document.getElementById("AddRoleModule");
            formAddRole.submit();
        }
    }
}