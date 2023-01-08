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
}