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
    static VerificationAddForm()
    {
        const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

        let au_name = document.getElementById("addu_name");
        let au_surname = document.getElementById("addu_surname");
        let au_login = document.getElementById("addu_login");
        let au_email = document.getElementById("addu_email")
        let au_custom_id = document.getElementById("addu_custom_id");
        let au_pass = document.getElementById("addu_password");
        let au_role = document.getElementById("role");
        
        let roleDep = au_role.options[au_role.options.selectedIndex].getAttribute("dep");
        
        let isGood = true;

        if(au_name.value == "") {isGood = false; au_name.style.backgroundColor = "#e48080";}
        if(au_surname.value == "") {isGood = false; au_surname.style.backgroundColor = "#e48080";}
        if(au_login.value == "") {isGood = false; au_login.style.backgroundColor = "#e48080";}
        if(!regex.test(au_email.value)) {isGood = false; au_email.style.backgroundColor = "#e48080";}
        if(au_custom_id.value == "") {isGood = false;au_custom_id.style.backgroundColor = "#e48080";}
        if(au_custom_id.value != "" && parseInt(au_custom_id.value) <= 0) {isGood = false; au_custom_id.style.backgroundColor = "#e48080";}
        if(au_pass.value == "") {isGood = false; au_pass.style.backgroundColor = "#e48080";}

        if(document.getElementById("newUserDepId")!= null)
        {
            let dep1 = document.getElementById("newUserDepId");
            let dep1id = dep1.options[dep1.options.selectedIndex].text;
            console.log(dep1id)
            if(roleDep != dep1id) {isGood = false; document.getElementById("newUserDepId").style.backgroundColor = "#e48080";}
        }

        if(isGood == true)
        {
            let userAddForm = document.getElementById("UserAdd_UserModule");
            userAddForm.submit();
        }
        
    }
}