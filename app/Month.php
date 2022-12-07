<?php
namespace App;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 class Month
 {
    private string $name;
    private int $year;
    private int $numberInYear;
    private int $daysInMonth;
    private int $workingDays;
    private float $hoursToBeWorked;
    private int $dep_id;
    private $month_db;
    private $nameOfMonths = array('January','February','March','April','May','June','July','August','September','October','November','December');
    private $user;

    function __construct(int $numberInYear,int $year = 1990, User $user = new User(1)) 
    {
        $this->numberInYear = $numberInYear;
        $this->name = $this->getName();        
        $this->year = $year;
        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN,$numberInYear,$year);
        $this->dep_id = $user->dep_id;
        $this->month_db = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year));
        //DATE in SQL = 1999-09-09

        
    }
    //Funkcja zwracająca nazwę miesiąca
    public function getName()
    {
        if($this->numberInYear > 12 || $this->numberInYear < 1){
            throw new \Exception('Invalid Month Number');
        }else{
            return $this -> nameOfMonths[$this->numberInYear-1];
        }
    }
    //Funkcja zwracająca rok
    public function getYear()
    {
        return $this->year;
    }
    //Funkcja sprawdzająca czy istnieje rekord zawierający tworzony miesiąc w bazie danych 
    public function checkMonthInDatabase()
    {
        $month = $this->month_db;
        
        $expire = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year+2));
        $dep_id = $this->dep_id;

        // fwrite(STDERR, print_r($expire, TRUE)); //Kontrolka do sprawdzania

        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $selectMonthInDB = "SELECT month, dep_id FROM calendar WHERE month = '$month' AND dep_id = $dep_id;";
        $result = $mysqliAdm ->query($selectMonthInDB);

        if($result->num_rows <= 0)
        {
            $result->free();
            unset($conn);
            return false;
        }
        else
        {
            $result->free();
            unset($conn);
            return true;
        }
    }

    public function howManyRoles()
    {
        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $selectAllRoles = "SELECT id FROM roles;";
        $result = $mysqliAdm->query($selectAllRoles);
        // Można później dodać pomijanie niektórych ról
        return $result->num_rows;
    }


    //Funkcja tworząca plik json dla kolumny days w tabeli month
    function createNewDaysBodyJson()
    {
        $dayBody = array();
        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $sqlSelectAllRoles = "SELECT id FROM shifts;";
        $res = $mysqliAdm->query($sqlSelectAllRoles);
        
        $newValueShift = "[";
        while($row = $res->fetch_assoc())
        {   $newValueShift = $newValueShift . '{"shift"' . ':'. $row['id'] . ',"calendar":[';
            
            for($i = 1; $i < $this->daysInMonth; $i++)
            {
                $newValueShift = $newValueShift . '{"day":'.$i.',"body":""},';
            }
            $newValueShift = $newValueShift . '{"day":'.$this->daysInMonth.',"body":""}]},';
        }
        $newValueShift = substr($newValueShift,0,-1);
        $newValueShift = $newValueShift . ']';
        
        $res->free();
        unset($conn1);
        return $newValueShift;
    }

    //Funkcja tworząca czysty miesiąc w bazie danych
    public function createMonthInDatabase()
    {
        $month = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year));
        $expire = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year+2));
        $dayBody = $this->createNewDaysBodyJson();
        $dep_id = $this->dep_id;
        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        //Sprawdzenie ile jest dni pracujących / nie będących sobotą i inedzielą
        $countWorkingDays = 0;
            for($k = 1; $k <= $this->daysInMonth; $k++)
            {
                if(date("w", mktime(0, 0, 0, $this->numberInYear, $k, $this->year)) > 0 && date("w", mktime(0, 0, 0, $this->numberInYear, $k, $this->year)) < 6)
                {
                    $countWorkingDays++;
                }
            }
            
        // How many roles ?
        for($r = 1; $r <= $this->howManyRoles(); $r++)
        {
            $createNewMonth = "INSERT INTO calendar (month,dep_id,role_id,days,expire,working_days) VALUES ('$month',$dep_id,$r,'$dayBody','$expire',$countWorkingDays)";
            if($mysqliAdm ->query($createNewMonth) !== TRUE)
            {
                unset($conn);
                return false;
            }
        }
        unset($conn);
        return true;
    }





    private function drawMonthAccept()
    {
                    //Ile p[]
                    $drawing_fields = 42;
        //Sprawdzamy ilość dni
                    //Sprawdzamy jakim dniem jest pierwszy dzień miesiąca
                    $dayOfTheWeek = date("w", mktime(0, 0, 0, $this->numberInYear, 1, $this->year)); //sunday =0, saturday =6
                    //Jaka odległość dzieli pierwszy dzień miesiąca od poniedziałku
                    $spaceFromMonday = $dayOfTheWeek != 0 ? $dayOfTheWeek-1 : 6;
                    //Jaka odległość dzieli koniec miesiąca od końca tabeli
                    $daysOut = $drawing_fields - ($spaceFromMonday + $this->daysInMonth);
                    //Początek rysowania kalendarza
                    
                    echo '<div class="mainCalendar">';
                    echo '<table>
                        <tr>
                        <td>Monday</td>
                        <td>Tuesday</td>
                        <td>Wednesday</td>
                        <td>Thursday</td>
                        <td>Friday</td>
                        <td>Saturday</td>
                        <td>Sunday</td>                  
                        </tr>
                        </table>';
                    //Rysujemy ilość pól odstępu od poniedziałku// W przyszłości ponumerowane
                    for ($i = $spaceFromMonday; $i > 0; $i--)
                    {
                        echo '<div class="dayOfTheWeek outDay"></div>';
                    }
                    //Rysujemy wszystkie pola miesiąca
                    for($j = 1; $j <= $this->daysInMonth; $j++)
                    {
                        echo '<div class="dayOfTheWeek"  id="day'.$j.'">'
                                .'<div class="numberOfDay"><p>'.$j.'</p>
                                    <div id="addP" onclick="createFormToAddPersonToDay(this);">ADD</div>
                                    <div id="remP" onclick="createFormToRemovePersonFormShift(this);">REMOVE</div>
                                  </div>'
                                .'<div class="dayBody">'.$this->getDayBody($j, $_SESSION['shift_id'],$_SESSION['role_id']).'</div>'
                                
                             .'</div>';
                    }
                    //Rysujemy dni po końcu miesiąca
                    for($l = $daysOut; $l > 0; $l--)
                    {
                        echo '<div class="dayOfTheWeek outDay"></div>';
                    }

                    //Koniec rysowania kalendarza
                    echo '</div>';
                    // echo '<script>console.log(document.getElementById("day1").id);</script>';

    }


    public function getDayBody($day, $shift_id,$role_id)
    {
        $conn = new ConnectToDatabase;
        $month = $this->month_db;
        $dep_id = $this->dep_id;
        //Przekazywane przez SESSION
        
        $mysqliAdm = $conn -> connAdminPass();
        //Role id będzie poprzez SESSION
        $sqlSelectDayBody = "SELECT days FROM calendar WHERE month = '$month' AND dep_id = $dep_id and role_id = 1;";
        $res = $mysqliAdm->query($sqlSelectDayBody);
        $row = $res->fetch_assoc();
        $json = $row['days'];
        $showNames = true;
        $showIDs = false;
        $showUserIDs = false;
        $arrayDays = json_decode($json,true);
        foreach($arrayDays as $arr)
        {
            foreach($arr as $a)
            {
                if(is_numeric($a))
                {
                    if($a == $shift_id)
                    {
                        //Wyciągnięcie wartości body z konkretnego dnia
                        $dbody = $arrayDays[$a-1]['calendar'][$day-1]['body'];
                        //Tworzenie tabeli z użytkowników w daym dniu 
                        $workers = explode("$",$dbody);
                        $ret = "";
                        foreach($workers as $worker)
                        {
                            // W - working at this day
                            // V - vacation at this day
                            // if($worker[0] == 'W');
                            // if($worker[0] == 'V');
                                $uid = intval(substr($worker,1));
                                $selectUserFromDb = 'SELECT name, surname, usr_id, custom_id FROM user_data WHERE usr_id='.$uid.';';
                                $resU = $mysqliAdm->query($selectUserFromDb);
                                $rowU = $resU->fetch_assoc();
                                
                                if($rowU != null)
                                {
                                    if($showNames)
                                    {
                                        //cant be return here
                                        $ret = $ret.'<p>'.$rowU['name'].' '.$rowU['surname'][0].'</p>';
                                    }
                                    elseif($showIDs)
                                    {
                                        $ret = $ret. '<p>'.$rowU['custom_id'].'</p>';
                                    }
                                    elseif($showUserIDs)
                                    {
                                        $ret = $ret. '<p>'.$rowU['usr_id'].'</p>';
                                    }
                                }         
                        }
                        return $ret;
                    }
                }
            }
        }
    }
    //Funkcja rysująca miesiąc w postaci tabeli
    public function drawMonth()
    {
        $daysInMonth = $this->daysInMonth;
        $month = $this->numberInYear;
        $year = $this->year;
        //Sprawdzenie czy miesiąc jest w bazie danych 
        if($this->checkMonthInDatabase())
        {
            $this->drawMonthAccept();
        }
        else
        {
            $this->createMonthInDatabase();
            $this->drawMonthAccept();
        }
    }



 }

?>