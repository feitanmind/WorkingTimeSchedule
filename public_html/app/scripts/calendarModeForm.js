function closeAddUser()
{
    if(document.getElementById("newPrompt")) $('#newPrompt').remove();
}
// Tworzenie formularza przeznaczonego do dodania osoby do zmiany
function createFormToAddPersonToDay(obj)
{
    // Sprawdzenie czy jest otwarty prompt na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newPrompt")) $('#newPrompt').remove();
    // Stworzenie nowego obiektu typu div
    var newPrompt = document.createElement("div")
    //Nadanie mu id
    newPrompt.setAttribute("id","ok");
    obj.disabled = true;
    //PObranie z dayBody listy użytkowników będących na konkretnym dniu
    var listOfUserOnDay = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[1].childNodes.forEach(elem => listOfUserOnDay += '<p>'+elem.innerHTML+'</p>')

    console.log(listOfUserOnDay)
    //Podmienienie go na przygotowany template prompta
    //console.log(obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText);
    newPrompt.innerHTML =" \
    <div id=\"newPrompt\"> \
        <h3>Add user to shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">x</p></h3> \
        <form method=\"get\">\
            \
            <div id=\"listOfUsers\"> \
                <p>List of Users</p>"+listOfUsers+"\
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
    obj.parentElement.appendChild(newPrompt);
}


function createFormToRemovePersonFormShift(obj)
{
    // Sprawdzenie czy jest otwarty prompt na innym dniu // Jeśli tak niszczymy go
    if(document.getElementById("newPrompt")) $('#newPrompt').remove();
    // Stworzenie nowego obiektu typu div
    var newPrompt = document.createElement("div")
    //Nadanie mu id
    newPrompt.setAttribute("id","ok");
    var listOfUserOnDayToRemove = "";
    obj.parentElement.parentElement.childNodes[1].childNodes[1].childNodes.forEach(elem => listOfUserOnDayToRemove += '<option value="'+elem.getAttribute("personId")+'">'+elem.innerHTML+'</option>')
    listOfUserOnDayToRemove = '<select name="userToRemove[]" multiple="multiple">'+listOfUserOnDayToRemove+'</select>';
    obj.disabled = true;
    //Podmienienie go na przygotowany template prompta
    newPrompt.innerHTML ="\
        <div id=\"newPrompt\">\
            <h3>Remove person from shift<p onclick=\"closeAddUser(); hideAllAddAndRemoveControls();\" id=\"closeAddPrompt\">x</p></h3>\
            \
            <form method=\"get\">\
                <div id=\"listOfUsers\" style=\"width: 100%;\"><p>Users working in this day</p>"+listOfUserOnDayToRemove+"</div>\
                <input type=\"text\" name=\"dayId\" value=\""+obj.parentElement.parentElement.childNodes[0].childNodes[0].innerText+"\"/>\
                <input style=\"background-color: pink;\" type=\"submit\" value=\"Remove\"/>\
            </form>\
        </div>";
    obj.parentElement.appendChild(newPrompt);
}