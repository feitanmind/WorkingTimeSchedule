class Statistics
{
    static displayUserStats(user_id)
    {

        const form = document.createElement("form");
        form.setAttribute("method","post");
        form.style.display = "none";
        const inputForm = form.appendChild(document.createElement("input"));
        inputForm.setAttribute("name","usr_stats");
        inputForm.setAttribute("value",user_id);
        document.body.appendChild(form);
        form.submit();
    }
}