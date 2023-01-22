
var timeLogout = document.getElementById("timeToLogout");
var now = new Date().getTime();
var end= new Date(now + 20*60000).getTime();

var inter = setInterval(() => {
    var now = new Date().getTime();
    var dis = end - now;
    console.log(dis)
    timeLogout.innerHTML = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60)) + " min";
    if(dis < 0)
    {
        clearInterval(inter);
        window.location = "./modules/LoginClasses/Logout.php";
    }
}, 1000);