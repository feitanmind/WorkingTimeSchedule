
function showAddAndRemoveControls(numberOfDay)
{
    document.getElementById('day'+numberOfDay).children[0].children[1].style.display = 'flex';
    document.getElementById('day'+numberOfDay).children[0].children[2].style.display = 'flex';
    document.getElementById('day'+numberOfDay).children[0].children[3].style.display = 'flex';
    document.getElementById('day'+numberOfDay).children[0].children[4].style.display = 'flex';
}
function hideAddAndRemoveControls(numberOfDay)
{
    document.getElementById('day'+numberOfDay).children[0].children[1].style.display = 'none';
    document.getElementById('day'+numberOfDay).children[0].children[2].style.display = 'none';
    document.getElementById('day'+numberOfDay).children[0].children[3].style.display = 'none';
    document.getElementById('day'+numberOfDay).children[0].children[4].style.display = 'none';
}
function hideAllAddAndRemoveControls()
{
    for(i = 1; i <= 31; i++)
    {
        let obj = document.getElementById('day'+i);
        if(obj === null) break;
        obj.children[0].children[1].style.display = 'none';
        obj.children[0].children[2].style.display = 'none';
        obj.children[0].children[3].style.display = 'none';
        obj.children[0].children[4].style.display = 'none';
    }
    
}
