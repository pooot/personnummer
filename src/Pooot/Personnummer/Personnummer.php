<?php

namespace Pooot\Personnummer;

use Carbon\Carbon;

class Personnummer
{

    private static function luhn( $string )
    {
        $value = 0;
        $sum   = 0;

        for ($i = 0; $i < strlen( $string ); $i ++) {
            $value = intval( $string[$i] );
            $value *= 2 - ( $i % 2 );

            if ($value > 9) {
                $value -= 9;
            }

            $sum += $value;
        }

        return intval( ceil( $sum / 10 ) * 10 - $sum );
    }

    private static function testDate( $year, $month, $day )
    {
        try {
            date_default_timezone_set( 'Europe/Stockholm' );
            $date = new \DateTime( $year . '-' . $month . '-' . $day );

            if (strlen( $month ) < 2) {
                $month = '0' . $month;
            }

            if (strlen( $day ) < 2) {
                $day = '0' . $day;
            }

            return ! (
                substr( $date->format( 'Y' ), 2 ) !== strval( $year )
                || $date->format( 'm' ) !== strval( $month )
                || $date->format( 'd' ) !== strval( $day )
            );
        } catch ( \Exception $e ) {
            return false;
        }
    }

    private static function format( $string )
    {
        if ( ! is_numeric( $string ) && ! is_string( $string )) {
            return false;
        }

        $regexp = '/^(\d{2}){0,1}(\d{2})(\d{2})(\d{2})([\-|\+]{0,1})?(\d{3})(\d{0,1})$/';

        $string = strval( $string );

        preg_match( $regexp, $string, $match );

        if ( ! isset( $match ) || count( $match ) < 7) {
            return false;
        }

        return [
            'century'   => $match[1],
            'year'      => $match[2],
            'month'     => $match[3],
            'day'       => $match[4],
            'separator' => $match[5],
            'number'    => $match[6],
            'control'   => $match[7],
        ];
    }

    public static function validate( $string )
    {
        $formatted = self::format( $string );

        $valid = self::luhn( $formatted['year'] . $formatted['month'] . $formatted['day'] . $formatted['number'] ) === intval( $formatted['control'] );

        if ($valid && self::testDate( $formatted['year'], $formatted['month'], $formatted['day'] )) {
            return $valid;
        }

        return $valid && self::testDate( $formatted['year'], $formatted['month'],
            ( intval( $formatted['day'] ) - 60 ) );
    }

    public static function gender( $string )
    {
        if ( ! self::validate( $string )) {
            throw new \InvalidArgumentException();
        }

        $formatted = self::format( $string );

        return ( $formatted['number'] % 2 == 0 ) ? 'F' : 'M';
    }

    public static function formatted( $string )
    {
        if ( ! self::validate( $string )) {
            throw new \InvalidArgumentException();
        }

        $array = self::format( $string );

        $sep = ( $array['separator'] != '' ) ? $array['separator'] : '-';
        $str = $array['year'] . $array['month'] . $array['day'] . $sep . $array['number'] . $array['control'];

        return $str;
    }

    public static function age( $string )
    {
        if ( ! self::validate( $string )) {
            throw new \InvalidArgumentException();
        }

        $array = self::format( $string );

        $dob = $array['year'] . $array['month'] . $array['day'];
        $dt    = Carbon::createFromFormat( 'ymd', $dob, 'Europe/Stockholm' );
        $today = Carbon::now( 'Europe/Stockholm' );

        $age = $dt->diffInYears( $today );

        return $age;
    }
}
