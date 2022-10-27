let check1 = 0;
let info2 = document.getElementById("info2");
let changePassSend = document.getElementById("changePassSend");
function checkPasswordsAreSameFieldTwo()
    {
        check1 = 1;
        let field1 = document.getElementById("newpass");
        let field2 = document.getElementById("confpass");
        
        
        if(field1.value == field2.value)
        {
            changePassSend.disabled = false;
            changePassSend.style.backgroundColor = "#0A85ED";
                        field1.style.borderColor = "#caccce";
                        field2.style.borderColor = "#caccce";
                        field1.style.backgroundColor = "#fff";
                        field2.style.backgroundColor = "#fff";
                        info2.innerHTML = " ";
                        
                    }
                    else
                    {
                        changePassSend.disabled = true;
                        changePassSend.style.backgroundColor = "gray";
                        field1.style.borderColor = "red";
                        field2.style.borderColor = "red";
                        field1.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                        field2.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                        info2.innerHTML = "Hasła nie są takie same!";
                        info2.style.color = "red";
                    }

                }
                
function checkPasswordsAreSameFieldOne()
            {
                if( check1 != 0 )
                {
                    field1 = document.getElementById("newpass");
                    field2 = document.getElementById("confpass");
                    if(field1.value == field2.value)
                    {
                        changePassSend.disabled = false;
                        changePassSend.style.backgroundColor = "#0A85ED";
                        field1.style.borderColor = "#caccce";
                        field2.style.borderColor = "#caccce";
                        field1.style.backgroundColor = "#fff";
                        field2.style.backgroundColor = "#fff";
                        info2.style.innerHTML = " ";

                    }
                    else
                    {
                        changePassSend.disabled = true;
                        changePassSend.style.backgroundColor = "gray";
                        field1.style.borderColor = "red";
                        field2.style.borderColor = "red";
                        field1.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                        field2.style.backgroundColor = "rgba(255, 0, 0, 0.233)";
                        info2.style.color = "red";
                        info2.innerHTML = "Hasła nie są takie same!";
                    }
                }

                
            }
            