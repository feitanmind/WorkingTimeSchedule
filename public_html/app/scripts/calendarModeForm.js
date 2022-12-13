function closeAddUser()
{
    if(document.getElementById("newWindow")) $('#newWindow').remove();
    open = 2;
}
// Tworzenie formularza przeznaczonego do dodania osoby do zmiany
function createFormToAddPersonToDay(obj)
{
    // Sprawdzenie czy jest otwarty prompt na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newWindow")) $('#newWindow').remove();
    // Stworzenie nowego obiektu typu div
    var newWindow = document.createElement("div")
    //Nadanie mu id
    newWindow.setAttribute("id","ok");
    obj.disabled = true;
    //PObranie z dayBody listy użytkowników będących na konkretnym dniu
    var listOfUserOnDay = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[1].childNodes.forEach(elem => listOfUserOnDay += '<p>'+elem.innerHTML+'</p>')

    newWindow.innerHTML =" \
    <div id=\"newWindow\" class=\"addingUserWindow\"> \
        <div class=\"newWindowHeader\">Add user to shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div> \
        <form method=\"get\">\
            \
            <div class=\"listOfUsers\" id=\"listOfUsersToAddToDay\"> \
                <div id=\"headerListOfUsers\">List of Users</div>"+listOfUsersToAdd+"\
            </div>\
            \
            <div id=\"currentlyOnShift\">\
                <p>Current Users</p>"+listOfUserOnDay+"\
            </div>\
            <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
            \
            <input style=\"background-color: #0A85ED;\" type=\"submit\" value=\"Add\"/>\
        </form>\
    </div>";
    obj.parentElement.appendChild(newWindow);
}


function createFormToRemovePersonFormShift(obj)
{
    // Sprawdzenie czy jest otwarty prompt na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newWindow")) $('#newWindow').remove();
    // Stworzenie nowego obiektu typu div
    var newWindow = document.createElement("div")
    //Nadanie mu id
    newWindow.setAttribute("id","ok");
    var listOfUserOnDayToRemove = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[1].childNodes.forEach(elem => listOfUserOnDayToRemove += '<option value="'+elem.getAttribute("personId")+'">'+elem.innerHTML+'</option>')
    listOfUserOnDayToRemove = '<select name="userToRemove[]" multiple="multiple">'+listOfUserOnDayToRemove+'</select>';
    obj.disabled = true;
    //Podmienienie go na przygotowany template prompta
    newWindow.innerHTML ="\
        <div id=\"newWindow\" class=\"removingWindow\">\
        <div class=\"newWindowHeader\">Remove person from shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div>\
            \
            <form method=\"get\">\
                <div class=\"listOfUsers\" id=\"listOfUsersToRemoveFromDay\" style=\"width: 100%;\"><div id=\"headerListOfUsers\" >Users working in this day</div>"+listOfUserOnDayToRemove+"</div>\
                <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
                <input type=\"submit\" value=\"Remove\"/>\
            </form>\
        </div>";
    obj.parentElement.appendChild(newWindow);
}
function createFormToAddVacationToPerson(obj)
{
    // Sprawdzenie czy jest otwarty prompt na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newWindow")) $('#newWindow').remove();
    // Stworzenie nowego obiektu typu div
    var newWindow = document.createElement("div")
    //Nadanie mu id
    newWindow.setAttribute("id","ok");
    obj.disabled = true;
    //PObranie z dayBody listy użytkowników będących na konkretnym dniu
    var listOfUserOnDay = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[1].childNodes.forEach(elem => listOfUserOnDay += '<p>'+elem.innerHTML+'</p>')
    var userVacationing = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[2].childNodes.forEach(elem => listOfUserOnDay += '<p>'+elem.innerHTML+'</p>')

    //Podmienienie go na przygotowany template prompta
    newWindow.innerHTML =" \
    <div id=\"newWindow\" class=\"addingVacationWindow\"> \
        <div class=\"newWindowHeader\">Grant vacation<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div> \
        <form method=\"get\">\
            \
            <div class=\"listOfUsers\" id=\"listOfUsersToGrantVacation\"> \
                <div id=\"headerListOfUsers\">List of Users</div>"+listOfUsersToGrantVacation +"\
            </div>\
            \
            <div id=\"currentlyOnShift\">\
                <p>Current Users</p>"+listOfUserOnDay+"<p>Vacation</p>"+userVacationing+"\
            </div>\
            <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
            \
            <input type=\"submit\" value=\"Grant Vacation\"/>\
        </form>\
    </div>";
    obj.parentElement.appendChild(newWindow);
}
function createFormToRevokePersonVacation(obj)
{
    // Sprawdzenie czy jest otwarte okno na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newWindow")) $('#newWindow').remove();
    // Stworzenie nowego obiektu typu div
    var newWindow = document.createElement("div")
    //Nadanie mu id
    newWindow.setAttribute("id","ok");
    var listOfUserOnDayToRevokeVacation = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[3].childNodes.forEach(elem => listOfUserOnDayToRevokeVacation += '<option value="'+elem.getAttribute("personId")+'">'+elem.innerHTML+'</option>')
    listOfUserOnDayToRevokeVacation = '<select name="userToRevokeVacation[]" multiple="multiple">'+listOfUserOnDayToRevokeVacation+'</select>';
    obj.disabled = true;
    //Podmienienie go na przygotowany template okna
    newWindow.innerHTML ="\
        <div id=\"newWindow\" class=\"revokingWindow\">\
        <div class=\"newWindowHeader\">Revoke vacation<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div>\
            \
            <form method=\"get\">\
                <div class=\"listOfUsers\" id=\"listOfUsersToRevokeVacation\" style=\"width: 100%;\"><div id=\"headerListOfUsers\">Vacationing users:</div>"+listOfUserOnDayToRevokeVacation+"</div>\
                <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
                <input type=\"submit\" value=\"Remove\"/>\
            </form>\
        </div>";
    obj.parentElement.appendChild(newWindow);
}
