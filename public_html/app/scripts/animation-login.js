$(document).ready(function(){

    $("#pong").css("display","block")
    $("#pong2").css("display","block")
    $("#pong3").css("display","block")

    setInterval(() => {
        $("#pong").animate({width: "0vw", height: "0vw", top: "60vh",left: "21.7vw"},5000);
        $("#pong").animate({width: "1.4vw",height: "1.4vw",top:"58vh", left: "21vw"},5000);
    }, 10000);
        
    setInterval(() => {
        $("#handguy").animate({rotate: "30deg",top:"19.2vh", left:"21vw"},2000);
        $("#handguy").animate({rotate: "30deg",top:"19.2vh", left:"21vw"},2000);
        $("#handguy").animate({rotate: "-10deg",top:"17.8vh", left:"20.2vw"},2000);
        $("#handguy").animate({rotate: "-10deg",top:"17.8vh", left:"20.2vw"},2000);
        
    }, 3000);

    
    // left:25.4vw;
    // top: 13vh;
    setInterval(() => {
        $("#pong2").animate({width: "0.8vw", height: "0.8vw", top: "13vh",left: "25.4vw"},7000);
        $("#pong2").animate({width: "0vw", height: "0vw", top: "13.3vh",left: "26vw"},4000);
        $("#pong2").animate({width: "0.8vw",height: "0.8vw",top:"13vh", left: "25.4vw"},4000);
    }, 7000);

    // left:58vw;
    // top: 10vh;
    setInterval(() => {
        $("#pong3").animate({width: "1.2vw", height: "1.2vw", top: "10vh",left: "58vw"},1000);
        $("#pong3").animate({width: "0vw", height: "0vw", top: "10.9vh",left: "58.9vw"},4000);
        $("#pong3").animate({width: "1.2vw",height: "1.2vw",top:"10vh", left: "58vw"},4000);
    }, 7000);
   
    setInterval(() => {
        $("#dotguy").animate({width:"0vw"},600);
        $("#dotguy").animate({width:"1vw"},0);
        $("#dotguy").animate({width:"1vw"},600);
        $("#dotguy").animate({width:"2vw"},0);
        $("#dotguy").animate({width:"2vw"},600);
        $("#dotguy").animate({width:"3vw"},0);
        $("#dotguy").animate({width:"3vw"},600);
        $("#dotguy").animate({width:"0vw"},0);

    }, 2400);



})