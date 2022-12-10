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

    console.log(listOfUserOnDay)
    //Podmienienie go na przygotowany template prompta
    //console.log(obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText);
    newWindow.innerHTML =" \
    <div id=\"newWindow\"> \
        <div id=\"newWindowHeader\">Add user to shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div> \
        <form method=\"get\">\
            \
            <div id=\"listOfUsers\"> \
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
        <div id=\"newWindow\">\
        <div id=\"newWindowHeader\">Remove person from shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div>\
            \
            <form method=\"get\">\
                <div id=\"listOfUsers\" style=\"width: 100%;\"><p>Users working in this day</p>"+listOfUserOnDayToRemove+"</div>\
                <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
                <input style=\"background-color: pink;\" type=\"submit\" value=\"Remove\"/>\
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

    console.log(listOfUserOnDay)
    //Podmienienie go na przygotowany template prompta
    //console.log(obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText);
    newWindow.innerHTML =" \
    <div id=\"newWindow\"> \
        <div id=\"newWindowHeader\">Grant holiday<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div> \
        <form method=\"get\">\
            \
            <div id=\"listOfUsers\"> \
                <div id=\"headerListOfUsers\">List of Users</div>"+listOfUsersToGrantVacation +"\
            </div>\
            \
            <div id=\"currentlyOnShift\">\
                <p>Current Users</p>"+listOfUserOnDay+"<p>Vacation</p>"+userVacationing+"\
            </div>\
            <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
            \
            <input style=\"background-color: #0A85ED;\" type=\"submit\" value=\"Add\"/>\
        </form>\
    </div>";
    obj.parentElement.appendChild(newWindow);
}
function createFormToRevokePersonVacation(obj)
{
    // Sprawdzenie czy jest otwarty prompt na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newWindow")) $('#newWindow').remove();
    // Stworzenie nowego obiektu typu div
    var newWindow = document.createElement("div")
    //Nadanie mu id
    newWindow.setAttribute("id","ok");
    var listOfUserOnDayToRevokeVacation = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[3].childNodes.forEach(elem => listOfUserOnDayToRevokeVacation += '<option value="'+elem.getAttribute("personId")+'">'+elem.innerHTML+'</option>')
    listOfUserOnDayToRevokeVacation = '<select name="userToRevokeVacation[]" multiple="multiple">'+listOfUserOnDayToRevokeVacation+'</select>';
    obj.disabled = true;
    //Podmienienie go na przygotowany template prompta
    newWindow.innerHTML ="\
        <div id=\"newWindow\">\
        <div id=\"newWindowHeader\">Remove person from shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">❌</p></div>\
            \
            <form method=\"get\">\
                <div id=\"listOfUsers\" style=\"width: 100%;\"><p>Vacationing users:</p>"+listOfUserOnDayToRevokeVacation+"</div>\
                <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
                <input style=\"background-color: pink;\" type=\"submit\" value=\"Remove\"/>\
            </form>\
        </div>";
    obj.parentElement.appendChild(newWindow);
}
