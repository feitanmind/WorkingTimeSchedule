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
}
