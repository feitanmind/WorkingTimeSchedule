// window.history.pushState({}, document.title, "/" + "app/");
// let toast = document.getElementById('toast');
// toast.style.position = 'absolute';
// toast.style.display = 'flex';
// toast.style.opacity = 1;
// toast.innerHTML ='<div id="toastBody"><p>😢</p><p><strong>Warning</strong></p><p>You can\'t sign user to this day because user was signed on colliding shift</p></div><div id="toastLost"></div>';
// const toastLost =  document.getElementById('toastLost');
// let width = 0;
// const toastLostInterval = setInterval(()=>
// {
//     if(Math.abs(width) >= 19.5)
//     {
//         clearInterval(toastLostInterval);
//         let toastOpacity = 1;
//         const toastInterval = setInterval(()=>
//             {
//                 if(Math.abs(toastOpacity) < 0.2)
//                 {
//                     toast.style.display = "none";
//                     clearInterval(toastInterval);        
//                 }
//                 else
//                 {
//                     toastOpacity-= 0.02;
//                     toast.style.opacity = toastOpacity;
//                 }

//             },100);
//     } else
//     {
//         width+=0.3;
//         toastLost.style.width = width + 'vw';
//     }
// },40);

class Notification
{
    static createAndDisplayWarningAboutCantSignUserOnDay()
    {
        const toast = document.createElement("div");
        toast.setAttribute("id","toast");
        // form.style.display = "none";
        document.body.appendChild(toast);
        toast.style.position = 'absolute';
        toast.style.display = 'flex';
        toast.innerHTML ='<div id="toastBody"><p>😢</p><p><strong>Warning</strong></p><p>You can\'t sign user to this day because user was signed on colliding shift</p></div><div id="toastLost"></div>';
        // const toastLost = toast.appendChild(document.createElement("div"));
        // toastLost.setAttribute("id","toastLost");
        const toastLost =  document.getElementById('toastLost');
        let width = 0;
        const toastLostInterval = setInterval(()=>
        {
            if(Math.abs(width) >= 19.5)
            {
                clearInterval(toastLostInterval);
                let toastOpacity = 1;
                const toastInterval = setInterval(()=>
                    {
                        if(Math.abs(toastOpacity) < 0.2)
                        {
                            toast.style.display = "none";
                            clearInterval(toastInterval);        
                        }
                        else
                        {
                            toastOpacity-= 0.02;
                            toast.style.opacity = toastOpacity;
                        }
        
                    },100);
            } else
            {
                width+=0.3;
                toastLost.style.width = width + 'vw';
            }
        },40);
        // const inputForm = form.appendChild(document.createElement("input"));
        // inputForm.setAttribute("name","CALENDAR_SAVE");
        // inputForm.setAttribute("value","YES");
        // document.body.appendChild(form);
        // form.submit();
    }
}