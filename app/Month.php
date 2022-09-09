<?php
namespace App;

 class Month
 {
    private const NUMBER_DAYS_IN_WEEK = 7;
    private const DRAWING_FIELDS = 42;
    private string $name;
    private int $year;
    private int $numberInYear;
    private int $daysInMonth;
    private int $workingDays;
    private float $hoursToBeWorked;
    private $nameOfMonths = array('January','February','March','April','May','June','July','August','September','October','November','December');
    

    function __construct(int $numberInYear, int $year = 1990, User $user = new User(1)) 
    {
        $this->numberInYear = $numberInYear;
        $this->name = $this->getName();        
        $this->year = $year;
        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN,$numberInYear,$year);

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
    //Funkcja sprawdzająca czy istnieje rekord zawierający tworzony miesiąc w bazie danych 
    public function checkMonthInDatabase()
    {

    }
    //Funkcja tworząca czysty miesiąc w bazie danych
    public function createMonthInDatabase()
    {
        //Utwórz nowy miesiąc w bazie danych
                    // Numer miesiąca 
                    // Rok
                    // Oddzial
                    // Sprawdź liczbę dni
                    // Dla każdego dnia ustaw wartość pustą jsona []
    }

    //Funkcja rysująca miesiąc w postaci tabeli
    public function drawMonth()
    {
        $daysInMonth = $this->daysInMonth;
        $month = $this->numberInYear;
        $year = $this->year;
        //Sprawdzenie czy miesiąc jest w bazie danych 
            //Jeśli tak
                //Sprawdzamy ilość dni
                    //Sprawdzamy jakim dniem jest pierwszy dzień miesiąca
                    $dayOfTheWeek = date("w", mktime(0, 0, 0, $month, 1, $year)); //sunday =0, saturday =6
                    //Jaka odległość dzieli pierwszy dzień miesiąca od poniedziałku
                    $spaceFromMonday = $dayOfTheWeek != 0 ? $daysOfTheWeek-1 : 6;
                    //Jaka odległość dzieli koniec miesiąca od końca tabeli
                    $daysOut = DRAWING_FIELDS - ($spaceFromMonday + $daysInMonth);
                    //Początek rysowania kalendarza
                    echo '<div class="mainCalendar">';

                    //Rysujemy ilość pól odstępu od poniedziałku// W przyszłości ponumerowane
                    for ($i = $dayOfTheWeek; $i > 0; $i--)
                    {
                        echo '<div class="dayOfTheWeek outDay"></div>';
                    }
                    //Rysujemy wszystkie pola miesiąca
                    for($j = 1; $j <= $daysInMonth; $j++)
                    {
                        echo '<div class="dayOfTheWeek day'.$j.'">'
                                .'<div class="numberOfDay">'.$j.'</div>'
                                .'<div class="dayBody">'.$j.'</div>'
                             .'</div>';
                    }
                    //Rysujemy dni po końcu miesiąca
                    for($l = $daysOut; $l > 0; $l--)
                    {
                        echo '<div class="dayOfTheWeek outDay"></div>';
                    }

                    //Koniec rysowania kalendarza
                    echo '</div>';


            //Jeśli nie
                //createMonthInDatabase();
                



    }


 }

?>