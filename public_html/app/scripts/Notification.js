class Notification
{
    static displayNotification(xmlAsText, typeOfNotification, subjectNotification)
    {
        //window.history.pushState({}, document.title, "/" + "app/");
        let xmlParser = new DOMParser();
        let xm = xmlAsText.trim();
        let xmlObj = xmlParser.parseFromString(xm,"text/xml");

        const notification = document.createElement("div");
        notification.setAttribute("id",xmlObj.getElementById(subjectNotification).getAttribute("notificationType"));
        notification.setAttribute("class","Notification")
        notification.innerHTML = xmlObj.getElementById(subjectNotification).children[0].innerHTML + 
                                 xmlObj.getElementById(subjectNotification).children[1].innerHTML;

        document.body.appendChild(notification);
        const notificationHide =  document.getElementById('progressBarToHideNotification');
        notificationHide.style.backgroundColor = typeOfNotification;
        let width = 0;
        const hideNotificationInterval = setInterval(()=>
        {
            if(Math.abs(width) >= 21.5)
            {
                clearInterval(hideNotificationInterval);
                let notificationOpacity = 1;
                const notificationInterval = setInterval(()=>
                    {
                        if(Math.abs(notificationOpacity) < 0.2)
                        {
                            notification.style.display = "none";
                            notification.remove();
                            clearInterval(notificationInterval);        
                        }
                        else
                        {
                            notificationOpacity-= 0.02;
                            notification.style.opacity = notificationOpacity;
                        }
        
                    },100);
            } else
            {
                width+=0.3;
                notificationHide.style.width = width + 'vw';
            }
        },40);
    }

    static askUserAboutShiftForSingningUser()
    {
        var b = document.getElementById('selectShiftForAddingUser');
        b.style.position = 'absolute';


        b.style.backgroundColor = '#00000070';
        b.style.width = '100vw';
        b.style.height = '100vh';
        b.style.display = 'flex';
        b.style.justifyContent = 'center';
        b.style.alignItems = 'center';
        const header = document.getElementsByClassName('selectShift')[0].childNodes[0];
        console.log(header);
        header.innerText = 'Wybierz zmianę na którą chcesz zapisać użytkownika';
        header.style.fontSize = '3vw';
        header.style.color = 'white';

        s = document.getElementsByClassName('calendarFilterSelect')[0];
        s.parentElement.style.display = 'flex';
        s.parentElement.style.height = '30vh';
        s.parentElement.style.flexDirection = 'column';
        s.parentElement.style.alignItems = 'center';
        s.parentElement.style.gap = '2vh';
        s.setAttribute('class','selectShift_AddU');
        
        s.style.all = 'unset';
        s.style.marginTop = '10vh';
        s.style.backgroundColor = 'white';
        s.style.borderRadius = '1vw';
        s.style.padding = '0.1vw';
        s.style.color = 'black';
        s.style.position = 'absolute';
        s.style.width = '15vw';
        s.style.height = '5vh';
        s.style.paddingLeft = '2vw';
        s.style.fontSize = '1vw';

        s.childNodes.forEach(opt => { if (opt.value == 'all') {opt.innerText= 'Wybierz zmianę';opt.disabled = true;}});
    }
}