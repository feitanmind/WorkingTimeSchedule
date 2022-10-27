let num = 9;
let numCount = document.getElementsByTagName("h2")[2];
setInterval(() => {
    num--;
    if(num > 0){
        numCount.innerHTML = num;
    }
    else
    {
        document.location = "../";
    }
    
}, 1000);