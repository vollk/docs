<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Vollk
 * Date: 08/06/15
 * Time: 09:51
 */

class DateUtils {
    private static $rod_monthes = [
        'января',
        'февраля',
        'марта',
        'апреля',
        'мая',
        'июня',
        'июля',
        'августа',
        'сентября',
        'октября',
        'ноября',
        'декабря',
    ];

    // из дд.мм.гггг делаем гггг-мм-дд
    static function make_date($string)
    {
        if ($string)
        {
            $parts = explode('.',$string);
            return implode('-',array_reverse($parts));
        }
        return false;
    }

    // из гггг-мм-дд делаем дд.мм.гггг
    static function make_date_to_output($string)
    {
        if ($string)
        {
            $parts = explode('-',$string);
            $month_index = (int)$parts[1] -1;
            return $parts[2].' '.self::$rod_monthes[$month_index].' '.$parts[0];
        }
        return false;
    }

    static function getMonths()
    {
        return array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
    }
}