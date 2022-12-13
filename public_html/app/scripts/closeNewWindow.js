var open=0;
$(document).click(function(event) { 
    var $target = $(event.target);  
    if(!$target.closest('#newWindow').length && $('#newWindow') && open == 1)
    {
        $('#newWindow').remove();
        open = 0;
        return;
    }  
    if(open==0)
    {
        open++;
    }
    if(open==2)
    {
        open-=2;
    }
});