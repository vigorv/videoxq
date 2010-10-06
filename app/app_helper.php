<?php

class AppHelper extends Helper
{

    /**
     * Enter description here...
     *
     * @param string $glue
     * @param array $array
     * @param array|string $fields
     * @param string $fieldGlue
     * @param int $limit
     * @return string
     */
    function implodeWithParams($glue = '', $array = array(), $fields = 'title', $fieldGlue = ' ', $limit = null)
    {
        $objects = array();
        $i = 1;
        foreach ($array as $key => $element)
        {
            if (is_array($fields))
            {
                $tmp = array();
                foreach ($fields as $field)
                {
                     $tmp[] = $element[$field];
                }
                $objects[$key] = implode($fieldGlue, $tmp);
            }
            else
                $objects[$key] = $element[$fields];
           if ($limit && $i >= $limit)
               break;
           $i++;
        }
        return implode($glue, $objects);
    }


    function truncateText($text, $numWords = 100)
    {
        $textArray = explode(' ', $text, $numWords + 1);
        $wordCount = count($textArray);

        if ($wordCount <= $numWords && $wordCount == 1) //если вдруг умники будут делать оченьдлинныесловакоторыенеразбить
            return substr($text, 0, $numWords);
        elseif ($wordCount <= $numWords && $wordCount != 1)
            return $text;

        unset($textArray[$numWords]);
        return implode(' ', $textArray) . '...';
    }


    function sizeFormat($size)
    {
        if (abs($size) > pow(1024, 4)) return round(($size / pow(1024, 4)), 2) . "&nbsp;Tb";
        if (abs($size) > pow(1024, 3)) return round(($size / pow(1024, 3)), 2) . "&nbsp;Gb";
        if (abs($size) > pow(1024, 2)) return round(($size / pow(1024, 2)), 2) . "&nbsp;Mb";
        if (abs($size) > pow(1024, 1)) return round(($size / pow(1024, 1)), 2) . "&nbsp;kb";
        return $size . "&nbsp;b";
    }

    function timeFormat($size)
    {
    	$t = array();
    	$y = 365*24*60*60;
    	$o = $size % $y;
    	$y = intval($size/$y);
    	if ($y) $t[] = $this->pluralForm($y, array(__('year', true), __('yeara', true), __('years', true)));

    	$size = $o;
    	$m = 30*24*60*60;
    	$o = $size % $m;
    	$m = intval($size/$m);
    	if ($m) $t[] = $this->pluralForm($m, array(__('month', true), __('montha', true), __('months', true)));

    	$size = $o;
    	$d = 24*60*60;
    	$o = $size % $d;
    	$d = intval($size/$d);
    	if ($d) $t[] = $this->pluralForm($d, array(__('day', true), __('daya', true), __('days', true)));

    	$size = $o;
    	$h = 60*60;
    	$o = $size % $h;
    	$h = intval($size/$h);
    	if ($h) $t[] = $this->pluralForm($h, array(__('hour', true), __('houra', true), __('hours', true)));

    	$size = $o;
    	$m = 60;
    	$o = $size % $m;
    	$m = intval($size/$m);
    	if ($m) $t[] = $this->pluralForm($m, array(__('minute', true), __('minuta', true), __('minutes', true)));

    	$size = $o;
    	if ($size) $t[] = $this->pluralForm($size, array(__('second', true), __('seconda', true), __('seconds', true)));

    	$m = intval($size/365/24/60/60);
    	if ($m) $t[] = $m;
    	$y = intval($size/365/24/60/60);
    	if ($y) $t[] = $y;
        return implode(' ', $t);
    }


    /**
     * Функция склонения числительных в русском языке
     *
     * @param int    $number Число которое нужно просклонять
     * @param array  $titles Массив слов для склонения
     * @return string
     **/
    function pluralForm($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return number_format($number, 0 , ',', ' ' ) . " " . $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
    }


    function getUserProfileUrl($userId)
    {
        return $userId ? Configure::read('App.forumPath') . 'member.php?u=' . $userId : '';
    }

    function getUserPMUrl($userId)
    {
        return $userId ? Configure::read('App.forumPath') . 'private.php':'';
    }

    function timeShort($time = null, $separator = ', ')
    {
        setlocale(LC_ALL, 'ru_RU.utf8');

        $time = $time ? $this->timeFromString($time) : time();

        return strftime('%e %b %Y'.$separator.'%H:%M', $time);
    }


    function timeLong($time = null, $separator = ', ')
    {
        setlocale(LC_ALL, 'ru_RU.utf8');

        $time = $time ? $this->timeFromString($time) : time();

        return strftime('%e %b %Y'.$separator.'%T', $time);
    }

/**
 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
 *
 * @param string $dateString Datetime string
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 */
    function timeFromString($dateString, $userOffset = null) {
        if (is_integer($dateString) || is_numeric($dateString)) {
            $date = intval($dateString);
        } else {
            $date = strtotime($dateString);
        }
        if ($userOffset !== null) {
            return $this->convert($date, $userOffset);
        }
        return $date;
    }

/**
 * Returns either a relative date or a formatted date depending
 * on the difference between the current time and given datetime.
 * $datetime should be in a <i>strtotime</i>-parsable format, like MySQL's datetime datatype.
 *
 * Options:
 *  'format' => a fall back format if the relative time is longer than the duration specified by end
 *  'end' =>  The end of relative time telling
 *  'userOffset' => Users offset from GMT (in hours)
 *
 * Relative dates look something like this:
 *  3 weeks, 4 days ago
 *  15 seconds ago
 * Formatted dates look like this:
 *  on 02/18/2004
 *
 * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
 * like 'Posted ' before the function output.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param array $options Default format if timestamp is used in $dateString
 * @return string Relative time string.
 */
    function timeAgoInWords($dateTime, $options = array()) {
        $userOffset = null;
        if (is_array($options) && isset($options['userOffset'])) {
            $userOffset = $options['userOffset'];
        }
        $now = time();
        if (!is_null($userOffset)) {
            $now =  $this->convert(time(), $userOffset);
        }
        $in_seconds = $this->timeFromString($dateTime, $userOffset);
        $backwards = ($in_seconds > $now);

        $format = 'Y-m-d';
        $end = '+1 month';

        if (is_array($options)) {
            if (isset($options['format'])) {
                $format = $options['format'];
                unset($options['format']);
            }
            if (isset($options['end'])) {
                $end = $options['end'];
                unset($options['end']);
            }
        } else {
            $format = $options;
        }

        if ($backwards) {
            $future_time = $in_seconds;
            $past_time = $now;
        } else {
            $future_time = $now;
            $past_time = $in_seconds;
        }
        $diff = $future_time - $past_time;

        // If more than a week, then take into account the length of months
        if ($diff >= 604800) {
            $current = array();
            $date = array();

            list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $future_time));

            list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $past_time));
            $years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

            if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
                $months = 0;
                $years = 0;
            } else {
                if ($future['Y'] == $past['Y']) {
                    $months = $future['m'] - $past['m'];
                } else {
                    $years = $future['Y'] - $past['Y'];
                    $months = $future['m'] + ((12 * $years) - $past['m']);

                    if ($months >= 12) {
                        $years = floor($months / 12);
                        $months = $months - ($years * 12);
                    }

                    if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
                        $years --;
                    }
                }
            }

            if ($future['d'] >= $past['d']) {
                $days = $future['d'] - $past['d'];
            } else {
                $days_in_past_month = date('t', $past_time);
                $days_in_future_month = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

                if (!$backwards) {
                    $days = ($days_in_past_month - $past['d']) + $future['d'];
                } else {
                    $days = ($days_in_future_month - $past['d']) + $future['d'];
                }

                if ($future['m'] != $past['m']) {
                    $months --;
                }
            }

            if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)){
                $months = 11;
                $years --;
            }

            if ($months >= 12) {
                $years = $years + 1;
                $months = $months - 12;
            }

            if ($days >= 7) {
                $weeks = floor($days / 7);
                $days = $days - ($weeks * 7);
            }
        } else {
            $years = $months = $weeks = 0;
            $days = floor($diff / 86400);

            $diff = $diff - ($days * 86400);

            $hours = floor($diff / 3600);
            $diff = $diff - ($hours * 3600);

            $minutes = floor($diff / 60);
            $diff = $diff - ($minutes * 60);
            $seconds = $diff;
        }
        $relative_date = '';
        $diff = $future_time - $past_time;

        if ($diff > abs($now - $this->timeFromString($end))) {
            $relative_date = date($format, $in_seconds);
        } else {
            if ($years > 0) {
                // years and months and days
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($years, array('год', 'года', 'лет'));
                $relative_date .= $months > 0 ? ($relative_date ? ', ' : '') .  $this->pluralForm($months, array('месяц', 'месяца', 'месяцев')) : '';
                $relative_date .= $weeks > 0 ? ($relative_date ? ', ' : '') . $this->pluralForm($weeks, array('неделя', 'недели', 'недель')) : '';
                $relative_date .= $days > 0 ? ($relative_date ? ' и ' : '') . $this->pluralForm($days, array('день', 'дня', 'дней')) : '';
            } elseif (abs($months) > 0) {
                // months, weeks and days
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($months, array('месяц', 'месяца', 'месяцев'));
                $relative_date .= $weeks > 0 ? ($relative_date ? ', ' : '') . $this->pluralForm($weeks, array('неделя', 'недели', 'недель')) : '';
                $relative_date .= $days > 0 ? ($relative_date ? ' и ' : '') . $this->pluralForm($days, array('день', 'дня', 'дней')) : '';
            } elseif (abs($weeks) > 0) {
                // weeks and days
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($weeks, array('неделя', 'недели', 'недель'));
                $relative_date .= $days > 0 ? ($relative_date ? ' и ' : '') . $this->pluralForm($days, array('день', 'дня', 'дней')) : '';
            } elseif (abs($days) > 0) {
                // days and hours
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($days, array('день', 'дня', 'дней'));
                $relative_date .= $hours > 0 ? ($relative_date ? ' и ' : '') . $this->pluralForm($hours, array('час', 'часа', 'часов')) : '';
            } elseif (abs($hours) > 0) {
                // hours and minutes
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($hours, array('час', 'часа', 'часов'));
                $relative_date .= $minutes > 0 ? ($relative_date ? ' и ' : '') . $this->pluralForm($minutes, array('минута', 'минуты', 'минут')) : '';
            } elseif (abs($minutes) > 0) {
                // minutes only
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($minutes, array('минута', 'минуты', 'минут'));
            } else {
                // seconds only
                $relative_date .= ($relative_date ? ', ' : '') . $this->pluralForm($seconds, array('секунда', 'секунды', 'секунд'));
            }

            if (!$backwards) {
                $relative_date = sprintf(__('%s назад', true), $relative_date);
            }
        }
        return $this->output($relative_date);
    }


    function purifyHtml($data)
    {
        //uses('HTMLPurifier.auto');
        App::import('Vendor', 'HTMLPurifier', false, array(), 'HTMLPurifier.standalone.php');
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML', 'Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML', 'SafeEmbed', 1);
        $config->set('HTML', 'SafeObject', 1);
        $config->set('HTML', 'Allowed', 'a[href|title],img[src|alt|width|height|align],p[style],b,
                                         s,u,i,pre,abbr[title],acronym[title],blockquote[cite],
                                         font[color|size],span[style],strong,em,strike,br
                                         embed[src|type|width|height],param[name|value],object[width|height]');
        $config->set('CSS', 'AllowedProperties', array('text-align', 'font-size', 'color', 'text-decoration'));
//        $config->set('AutoFormat', 'AutoParagraph', true);
        $config->set('AutoFormat', 'Linkify', true);
        $config->set('Output', 'Newline', "\n");

        $parser = new HTMLPurifier($config);
        $data = $parser->purify($data, $config);
        $data = nl2br($data);
        return $data;
    }


}
?>