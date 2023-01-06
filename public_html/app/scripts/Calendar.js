class Calendar
{
    static saveCalendar()
    {
        const form = document.createElement("form");
        form.setAttribute("method","post");
        form.style.display = "none";
        const inputForm = form.appendChild(document.createElement("input"));
        inputForm.setAttribute("name","CALENDAR_SAVE");
        inputForm.setAttribute("value","YES");
        document.body.appendChild(form);
        form.submit();
    }
    static changeMonth($flag)
    {
        const areYouSure = document.createElement("div");
        areYouSure.setAttribute("id","areYouSure");
        // form.style.display = "none";
        
        document.body.appendChild(areYouSure);
        areYouSure.style.position = 'absolute';
        areYouSure.style.display = 'flex';
        areYouSure.style.fontSize = '1vw';
        areYouSure.style.left = ''
        areYouSure.style.flexDirection = 'column';
        areYouSure.style.borderRadius = '1vw';
        areYouSure.style.boxShadow = '0.2vw 0.2vw 0.2vw black';
        areYouSure.style.backgroundColor = '#ffffff';
        areYouSure.style.boxSizing = 'border-box';
        areYouSure.style.padding = '1vw';
        areYouSure.innerHTML = `
                                <p>Are you sure you want to change the month? All unsaved changes will be lost</p>
                                <div style="display: flex;">
                                    <button class="button1" onclick="Calendar.changeMonthAccept('`+$flag+`')">Yes</button>
                                    <button class="button1" onclick="Calendar.changeMonthRefuse()">No</button>
                                </div>
                            `;
            
    
    }
    static changeMonthAccept($flag)
    {
        const form = document.createElement("form");
        form.setAttribute("method","get");
        form.style.display = "none";
        const inputForm = form.appendChild(document.createElement("input"));
        inputForm.setAttribute("name","CHANGE_MONTH");
        inputForm.setAttribute("value",$flag);
        document.body.appendChild(form);
        form.submit();
    }
    static changeMonthRefuse()
    {
        document.getElementById("areYouSure").remove();
    }
}
