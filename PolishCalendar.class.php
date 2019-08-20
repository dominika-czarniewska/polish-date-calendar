<?php
class PolishCalendar
{
    private $transfer_date;
    private $year;
    private $month;
    private $day;
    
    public function __construct($transfer_date)
    {
        $this->transfer_date = strtotime($transfer_date);
        $this->day           = date('j', strtotime($transfer_date));
        $this->month         = date('n', strtotime($transfer_date));
        $this->year          = date('Y', strtotime($transfer_date));
        $this->week_day      = date('N', strtotime($transfer_date));
    }
    
    public function getEasterDate()
    {
        $year = $this->year;
        
        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;
        $d = ($a * 19 + 24) % 30;
        $e = (2 * $b + 4 * $c + 6 * $d + 5) % 7;
        
        if ($d == 29 && $e == 6)
            $d -= 7;
        if ($d == 28 && $e == 6 && $a > 10)
            $d -= 7;
        
        $control_date = $year . '-03-22';
        $sum          = $d + $e;
        $easter_date  = date('y-m-d', strtotime($control_date . ' +' . $sum . 'day'));
        
        return $easter_date;
    }
    
    public function isHoliday()
    {
        $day   = $this->day;
        $month = $this->month;
        
        $easter_date = $this->getEasterDate();
        
        $easter_day   = date('j', strtotime($easter_date));
        $easter_month = date('n', strtotime($easter_date));
        
        $christ_date  = date('y-m-d', strtotime($this->transfer_date . '- 60 day'));
        $christ_day   = date('j', strtotime($christ_date));
        $christ_month = date('n', strtotime($christ_date));
        
        if ($month == 1 && $day == 1)
            return true; // Nowy Rok
        if ($month == 1 && $day == 6)
            return true; //trzech kroli
        if ($month == 5 && $day == 1)
            return true; // 1 maja
        if ($month == 5 && $day == 3)
            return true; // 3 maja
        if ($month == 8 && $day == 15)
            return true; // Wniebowzięcie Najświętszej Marii Panny, Święto Wojska Polskiego
        if ($month == 11 && $day == 1)
            return true; // Dzień Wszystkich Świętych
        if ($month == 11 && $day == 11)
            return true; // Dzień Niepodległości 
        if ($month == 12 && $day == 25)
            return true; // Boże Narodzenie
        if ($month == 12 && $day == 26)
            return true; // Boże Narodzenie
        if ($month == $easter_month && ($day - 1) == $easter_day)
            return true; // poniedziałek Wielkanocny
        if ($month == $christ_month && $day == $christ_day)
            return true; // Boże Ciało
        
        return false;
    }
    
    public function translateMonthName()
    {
        $months = array(
            '',
            'Styczeń',
            'Luty',
            'Marzec',
            'Kwiecień',
            'Maj',
            'Czerwiec',
            'Lipiec',
            'Sierpień',
            'Wrzesień',
            'Październik',
            'Listopad',
            'Grudzień'
        );
        return $months[$this->month];
    }
    
    public function translateDayName()
    {
        $days = array(
            '',
            'Poniedziałek',
            'Wtorek',
            'Środa',
            'Czwartek',
            'Piątek',
            'Sobota',
            'Niedziala'
        );
        return $days[$this->week_day];
    }
}

$calendar = new PolishCalendar('2010-04-05'); //deklaracja przykladowej daty we wskazanym formacie

if ($calendar->isHoliday() == true) { //isHoliday sprawdza czy jest jakies swieto które nie odbywa się domyślnie w niedziele
    echo 'jest jakies swieto';
} else {
    echo 'niestety nie ma swieta';
}

echo $calendar->getEasterDate(); //zwraca datę wielkanocy w danym roku

echo $calendar->translateMonthName(); // nazwa danego miesiaca

echo $calendar->translateDayName(); //nazwa danego dnia tygodnia
