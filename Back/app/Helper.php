<?php

namespace App;

use Illuminate\Support\Facades\Log;

class Helper
{

    static public function date_work_period($date_start, $period)
    {
        $plan_att = date_create($date_start);
        date_add($plan_att, date_interval_create_from_date_string($period . ' days'));
        $plan_att = date_parse(date_format($plan_att, 'Y-m-d'));
        $nameDay = date('D', mktime(0, 0, 0, $plan_att['month'], $plan_att['day'], $plan_att['year']));
        $plan_att = $plan_att['year'] . '-' . $plan_att['month'] . '-' . $plan_att['day'];
        if ($nameDay == 'Sat') {
            $plan_att = date_create($plan_att);
            date_add($plan_att, date_interval_create_from_date_string('2 days'));
            return date_format($plan_att, 'Y-m-d');
        }
        if ($nameDay == 'Sun') {
            $plan_att = date_create($plan_att);
            date_add($plan_att, date_interval_create_from_date_string('1 day'));
            return date_format($plan_att, 'Y-m-d');
        }
        return $plan_att;
    }

    static public function date_add_second_number($date)
    {
        $period_date = date_parse($date);
        if (strlen($period_date["day"]) == 1) {
            $period_date["day"] = substr_replace($period_date["day"], '0', 0, 0);
        }
        if (strlen($period_date["month"]) == 1) {
            $period_date["month"] = substr_replace($period_date["month"], '0', 0, 0);
        }
        return $period_date;
    }

    static function action_mounts($futureDays)
    {
        $checkMonth = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $actionInMounth = [];
        $result = [];
        foreach ($futureDays as $item) {
            $period_date = date_parse($item->fakt_att);
            array_push($actionInMounth, $period_date["month"]);
        }
        $diagramMonth = array_count_values($actionInMounth);
        foreach ($checkMonth as $month) {
            if (!array_key_exists($month, $diagramMonth)) {
                $diagramMonth[$month] = 0;
            }
        }
        ksort($diagramMonth);
        foreach ($diagramMonth as $item) {
            array_push($result, $item);
        }
        return $result;
    }

    static function fio_parse($secondName, $firstName, $thirdName)
    {
        return $secondName . ' ' . substr($firstName, 0, 2) . '.' . substr($thirdName, 0, 2) . '.';
    }

    static function date_parse_object($date)
    {
        return $date["year"] . '-' . $date["month"] . '-' . $date["day"];
    }

    static function is_null_rqst($item, $key)
    {
        $item = $item == 'null' ? null : $item;

        return $item;
    }

    static function format_date_dmY($date)
    {
        $date = date_create($date);
        $date = date_format($date, 'd.m.Y г.');
        return $date;
    }

    static function get_period_notice($date, $period)
    {
        $today = date_create('now');
        $date_start_notice = date_sub(date_create($date), date_interval_create_from_date_string($period . ' days'));
        $date_start_notice = date_parse(date_format($date_start_notice, 'Y-m-d'));
        $nameDay = date('D', mktime(0, 0, 0, $date_start_notice['month'], $date_start_notice['day'], $date_start_notice['year']));
        if ($nameDay == 'Sat') {
            $date_start_notice = date_create($date_start_notice['year'] . '-' . $date_start_notice['month'] . '-' . $date_start_notice['day']);
            date_sub($date_start_notice, date_interval_create_from_date_string('1 day'));
        }
        if ($nameDay == 'Sun') {
            $date_start_notice = date_create($date_start_notice['year'] . '-' . $date_start_notice['month'] . '-' . $date_start_notice['day']);
            date_sub($date_start_notice, date_interval_create_from_date_string('2 days'));
        }
        $test = Helper::date_work_period($date, $period);
        $date = date_create($date);
        $interval = date_diff($date, $today);
        if (($date_start_notice <= $today && $today <= $date) || ($date <= $today)) {
            return true;
        }
        return false;
    }

    function removeKeys(array $array, array $keysToRemove)
    {
        foreach ($keysToRemove as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
