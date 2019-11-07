<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('return_datetime')) {

    function return_datetime($string = '') {
        $str   = strtotime($string);
        $date  = date('d', $str);
        $month = date('m', $str);
        $year  = date('Y', $str);
        $h     = date('h', $str);
        $m     = date('i', $str);
        $ampm  = date('a', $str);

        $shortmonth = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );
        $longmonth  = array(
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        );

        return $date . ' ' . $shortmonth[ltrim($month, 0) - 1] . ' ' . $year . ' &nbsp; ' . $h . ':' . $m . ' ' . $ampm;
    }

}