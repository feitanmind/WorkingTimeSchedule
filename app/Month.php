<?php
namespace App;

 class Month
 {
    private const NUMBER_DAYS_IN_WEEK = 7;
    private int $drawing_fields = 42;
    private string $name;
    private int $year;
    private int $numberInYear;
    private int $daysInMonth;
    private int $workingDays;
    private float $hoursToBeWorked;
    private int $dep_id;
    private $nameOfMonths = array('January','February','March','April','May','June','July','August','September','October','November','December');
    

    function __construct(int $numberInYear, int $year = 1990, User $user = new User(1)) 
    {
        $this->numberInYear = $numberInYear;
        $this->name = $this->getName();        
        $this->year = $year;
        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN,$numberInYear,$year);
        $this->dep_id = $user->dep_id;
        //DATE in SQL = 1999-09-09

        
    }
    //Funkcja zwracająca nazwę miesiąca
    public function getName()
    {
        if($this->numberInYear > 12 || $this->numberInYear < 1){
            throw new Exception('Invalid Month Number');
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
        $month = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year));
        $expire = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year+2));
        $dep_id = $this->dep_id;

        // fwrite(STDERR, print_r($expire, TRUE)); //Kontrolka do sprawdzania

        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();
        $selectMonthInDB = "SELECT month, dep_id FROM month WHERE month = '$month' AND dep_id = $dep_id;";
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




    //Funkcja tworząca czysty miesiąc w bazie danych
    public function createMonthInDatabase()
    {
        $month = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year));
        $expire = date("Y-m-d",mktime(0,0,0,$this->numberInYear,1,$this->year+2));

        $dep_id = $this->dep_id;
        $conn = new ConnectToDatabase;
        $mysqliAdm = $conn -> connAdminPass();

        // How many roles ?
        for($r = 1; $r <= $this->howManyRoles(); $r++)
        {
            $createNewMonth = "INSERT INTO month (month,dep_id,role_id,days,expire) VALUES ('$month',$dep_id,$r,'[]','$expire')";
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
       
        //Sprawdzamy ilość dni
                    //Sprawdzamy jakim dniem jest pierwszy dzień miesiąca
                    $dayOfTheWeek = date("w", mktime(0, 0, 0, $this->numberInYear, 1, $this->year)); //sunday =0, saturday =6
                    //Jaka odległość dzieli pierwszy dzień miesiąca od poniedziałku
                    $spaceFromMonday = $dayOfTheWeek != 0 ? $dayOfTheWeek-1 : 6;
                    //Jaka odległość dzieli koniec miesiąca od końca tabeli
                    $daysOut = $this->drawing_fields - ($spaceFromMonday + $this->daysInMonth);
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
                                .'<div class="dayBody"></div>'
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