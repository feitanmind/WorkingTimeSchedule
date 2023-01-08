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
}