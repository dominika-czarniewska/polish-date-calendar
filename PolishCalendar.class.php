<?php

class PolishCalendar {
    private $transfer_date;
    private $year;
    private $month;
    private $day;

    public function __construct( string $transfer_date ) {
        $this->transfer_date = strtotime( $transfer_date );
        $this->day           = date( 'j', strtotime( $transfer_date ) );
        $this->month         = date( 'n', strtotime( $transfer_date ) );
        $this->year          = date( 'Y', strtotime( $transfer_date ) );
        $this->week_day      = date( 'N', strtotime( $transfer_date ) );
    }

    public function getEasterDate() {
        $year = $this->year;

        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;
        $d = ( $a * 19 + 24 ) % 30;
        $e = ( 2 * $b + 4 * $c + 6 * $d + 5 ) % 7;

        if ( $d == 29 && $e == 6 ) {
            $d -= 7;
        }
        if ( $d == 28 && $e == 6 && $a > 10 ) {
            $d -= 7;
        }

        $control_date = $year . '-03-22';
        $sum          = $d + $e;
        $easter_date  = date( 'y-m-d', strtotime( $control_date . ' +' . $sum . 'day' ) );

        return $easter_date;
    }

    public function isHoliday(): bool {
        $easter_date  = $this->getEasterDate();
        $easter_day   = date( 'j', strtotime( $easter_date ) );
        $easter_month = date( 'n', strtotime( $easter_date ) );
        $christ_date  = date( 'y-m-d', strtotime( $this->transfer_date . '- 60 day' ) );
        $christ_day   = date( 'j', strtotime( $christ_date ) );
        $christ_month = date( 'n', strtotime( $christ_date ) );

        $nowy_rok          = $this->month == 1 && $this->day == 1;
        $trzech_kroli      = $this->month == 1 && $this->day == 6;
        $pierwszy_maja     = $this->month == 5 && $this->day == 1;
        $trzeci_maja       = $this->month == 5 && $this->day == 3;
        $sw_wojska_pl      = $this->month == 8 && $this->day == 15;
        $sw_zmarlych       = $this->month == 11 && $this->day == 1;
        $sw_niepodleglosci = $this->month == 11 && $this->day == 11;
        $boze_narodzenie_1 = $this->month == 12 && $this->day == 25;
        $boze_narodzenie_2 = $this->month == 12 && $this->day == 26;
        $boze_cialo        = $this->month == $christ_month && $this->day == $christ_day;
        $pon_wielkanocny   = $this->month == $easter_month && ( $this->day - 1 ) == $easter_day;

        return $nowy_rok
               || $trzech_kroli
               || $pierwszy_maja
               || $trzeci_maja
               || $sw_wojska_pl
               || $sw_zmarlych
               || $sw_niepodleglosci
               || $boze_narodzenie_1
               || $boze_narodzenie_2
               || $boze_cialo
               || $pon_wielkanocny;
    }

    public function translateMonthName(): string {
        $months = [
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
            'Grudzień',
        ];

        return $months[ $this->month ];
    }

    public function translateDayName(): string {
        $days = [
            '',
            'Poniedziałek',
            'Wtorek',
            'Środa',
            'Czwartek',
            'Piątek',
            'Sobota',
            'Niedziala',
        ];

        return $days[ $this->week_day ];
    }
}
