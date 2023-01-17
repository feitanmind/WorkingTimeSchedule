class Shift
{
    static AddShiftShow()
    {
        document.getElementById("AddShiftModule").style.display = 'flex';
        document.getElementById("RemoveShiftModule").style.display = 'none';

        document.getElementById("Shift_Add").style.backgroundColor = 'white';
        document.getElementById("Shift_Remove").style.backgroundColor = '#cccccc';
    }
    static RemoveShiftShow()
    {
        document.getElementById("AddShiftModule").style.display = 'none';
        document.getElementById("RemoveShiftModule").style.display = 'flex';

        document.getElementById("Shift_Add").style.backgroundColor = '#cccccc';
        document.getElementById("Shift_Remove").style.backgroundColor = 'white';
    }
    static AddShiftVerify()
    {
        const regexShift = /^\d{2}\:\d{2}/;
        let shiftName = document.getElementById("nameOfShift_addShift");
        let startH = document.getElementById("startHour_addShift");
        let endH = document.getElementById("endHour_addShift");
        let isShiftGood = true;
        if(shiftName.value == ""){isShiftGood=false;shiftName.style.backgroundColor = "#e48080";}
        if(!regexShift.test(startH.value)){isShiftGood=false;startH.style.backgroundColor = "#e48080";}
        if(!regexShift.test(endH.value)){isShiftGood=false;endH.style.backgroundColor = "#e48080";}

        if(isShiftGood == true) document.getElementById("addShiftFormPost").submit();
        
    }
}