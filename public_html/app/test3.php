<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            background-color: pink;
        }
    </style>
</head>
<body>
<select class="calendarFilterSelect" name="shiftID"><option onclick="this.form.submit();" selected="selected" value="all">All</option><option onclick="this.form.submit();" selected="selected" value="1">7-15 (adminShift)</option><option onclick="this.form.submit();" value="2">7-14:35 (informatykShift)</option><option onclick="this.form.submit();" value="3">19-7 (nocnaZmiana)</option></select> 
  <script>
        const selectShift = document.createElement("form");
        const s = document.getElementsByClassName('calendarFilterSelect')[0];
        selectShift.setAttribute("id","selectedShift");
        // form.style.display = "none";
        
        document.body.appendChild(selectShift);
        selectShift.style.position = 'absolute';
        selectShift.style.display = 'flex';
        selectShift.style.flexDirection = 'column';
        selectShift.style.backgroundColor = 'white';
        selectShift.style.width = '30vw';
        selectShift.style.height = "30vh";
        selectShift.style.left = '35vw';
        selectShift.style.boxSizing = 'border-box';
        selectShift.style.padding = '1vw';
        h2 = document.createElement("div");
        h2.innerHTML = "Wybierz zmianę na którą chcesz zapisać użytkownika";
        h2.style.fontSize = '1.3vw';
        selectShift.appendChild(h2);
        selectShift.appendChild(s);
        console.log(selectShift)
        selectShift[0].style.height = '5vh';
        selectShift.childNodes[1].childNodes.forEach(opt => { if (opt.value == "all") {opt.disabled = true;}});

    </script>
</body>
</html>