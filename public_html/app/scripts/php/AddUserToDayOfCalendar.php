<?php
namespace App;
if(isset($_GET['usersToAdd']) && isset($_GET['dayId']))
{
    
    $dayId = $_GET['dayId'];
    $users = $_GET['usersToAdd'];
    $month_Number = $_SESSION['Month_Number'];
    $year = $_SESSION['Year_Number'];
    $department_ID = 1;
    $shiftId = $_SESSION['Shift_Id'];
        if ($shiftId == 'all'){
            $_SESSION['shiftIsAll'] = true;
            echo '<div id="selectShiftForAddingUser">';
            Shift::GenerateFormSelectForShifts($department_ID);
            echo '</div>';
            echo "
            <script>
            b = document.getElementById('selectShiftForAddingUser');
            b.style.position = 'absolute';


            b.style.backgroundColor = '#00000070';
            b.style.width = '100vw';
            b.style.height = '100vh';
            b.style.display = 'flex';
            b.style.justifyContent = 'center';
            b.style.alignItems = 'center';
            const header = document.getElementsByClassName('selectShift')[0].childNodes[0];
            console.log(header);
            header.innerText = 'Wybierz zmianę na którą chcesz zapisać użytkownika';
            header.style.fontSize = '3vw';
            header.style.color = 'white';

            s = document.getElementsByClassName('calendarFilterSelect')[0];
            s.parentElement.style.display = 'flex';
            s.parentElement.style.height = '30vh';
            s.parentElement.style.flexDirection = 'column';
            s.parentElement.style.alignItems = 'center';
            s.parentElement.style.gap = '2vh';
            s.setAttribute('class','selectShift_AddU');
            
            s.style.all = 'unset';
            s.style.marginTop = '10vh';
            s.style.backgroundColor = 'white';
            s.style.borderRadius = '1vw';
            s.style.padding = '0.1vw';
            s.style.color = 'black';
            s.style.position = 'absolute';
            s.style.width = '15vw';
            s.style.height = '5vh';
            s.style.paddingLeft = '2vw';
            s.style.fontSize = '1vw';

            

            s.childNodes.forEach(opt => { if (opt.value == 'all') {opt.innerText= 'Wybierz zmianę';opt.disabled = true;}});
            </script>
            ";
            
        }
    else
    {
        
        $calend = json_decode($_SESSION['calendar']);

        $calend2 = Calendar::DecodeJsonCalendar($month_Number, $year, $department_ID, $calend);
        foreach($users as $user)
        {
            $user2 = new User($user);
                $canAdd = $calend2->CanUserBeSignOnDay($user2, $dayId, $shiftId);
                $ifHoursOfWorkLeft = HoursOfWork::IfUserHaveHoursToSign($user2, $shiftId, $department_ID);
            //throw new Exception("dfas");
                if(!$ifHoursOfWorkLeft)
                {
                    
                    echo '<script>Notification.createAndDisplayWarningAboutNoHoursLeft();</script>';
                        $_SESSION['calendar'] = json_encode($calend2);
                }
                else
                {
                    if(!$canAdd)
                    {
                        
                        echo '<script>Notification.createAndDisplayWarningAboutCantSignUserOnDay();</script>';
                        $_SESSION['calendar'] = json_encode($calend2);

                    }
                    else
                    {
                        $calend2->SignUserToWorkInDay($user2, $dayId, $shiftId);
                        $c = array();
                        $a = json_decode($_SESSION['arrayOfHoursOfWorkForCurrentMonth'],0);
                        foreach ($a as $b)
                        {
                            $user3 = new User($b->User->user_id);
                            if ($b->Month == $month_Number && $b->Year == $year)
                            {
                                $how = new HoursOfWork($user3, $b->Month, $b->Year, $user3->hours_per_shift);

                                $how->ActualizeTimeAndHours($b->Hours);
                                if($user3->user_id == $user2->user_id)
                                {
                                    $how->SubstractTimeOfWork($user3);
                                }
                            }
                            array_push($c, $how);
                        }


                        $_SESSION['arrayOfHoursOfWorkForCurrentMonth'] = json_encode($c);
                        $_SESSION['calendar'] = json_encode($calend2);
                        if(isset($_SESSION['shiftIsAll']))
                        {
                            unset($_SESSION['shiftIsAll']);
                            $_SESSION['Shift_Id'] = 'all';
                        }
                        //czyszczenie Get
                        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                        header("Location: $actual_link");
                    }

                }
        }
        
    }
    $_SESSION['Module'] = 1;
}




?>