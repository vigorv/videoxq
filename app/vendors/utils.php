<?php
class Utils
{
    static $numberReportPoint;
    static $startMemoryInUse;
    static $lastMemoryInUse;

    public static function iconvRecursive($object, $in = 'windows-1251', $out = 'utf-8', $skip = array())
    {
        if (is_string($object))
           return iconv($in, $out, $object);
		if (!is_array($object))return false;
        foreach ($object as $key => $value)
        {
            if (!empty($skip) && in_array($key, $skip))
                continue;
            $object[$key] = self::iconvRecursive($value, $in, $out, $skip);
        }
        return $object;
    }

    /**
     * Convert seconds into h:m:s format
     *
     * @param int $secs
     * @return array ($h, $m, $s)
     */
    public static function secs2hms($secs)
    {
        if ($secs<0)
            return false;

        $m = (int)($secs / 60);
        $s = $secs % 60;
        $h = (int)($m / 60);
        $m = $m % 60;

        return array($h, $m, $s);
    }

    static function getMemoryReport($type = 'text', $return = false) {
        $trace = debug_backtrace();
//        if (count($trace) > 1) {
//            array_shift($trace);
//        }
        $line = $trace[0]['line'];
        $file = $trace[0]['file'];

        if (!self::$numberReportPoint) self::$numberReportPoint = 1;
        if (!self::$startMemoryInUse) self::$startMemoryInUse = self::$lastMemoryInUse = memory_get_usage();
        $curentMemoryInUse = memory_get_usage();
        $allMemory = ($curentMemoryInUse - self::$startMemoryInUse);
        $diffMemory = ($curentMemoryInUse - self::$lastMemoryInUse);
        $result = self::$numberReportPoint . ". Memory all: " . self::sizeFormat($allMemory) . ". Diff: " . self::sizeFormat($diffMemory) . ". At " . $file . " [ " . $line . " ]";
        self::$lastMemoryInUse = $curentMemoryInUse;
        self::$numberReportPoint ++;
        if ($type == 'text') $result = "\n ============================= \n" . $result . "\n ============================= \n";
        if ($type == 'html') $result = "<div class=\"memory_report\">" . $result . "</div>\n";
        if ($return)
            return $result;
        echo $result;
    }

    static function sizeFormat($size) {
        if (abs($size) > pow(1024, 3)) return round(($size / pow(1024, 3)), 2) . " Gb";
        if (abs($size) > pow(1024, 2)) return round(($size / pow(1024, 2)), 2) . " Mb";
        if (abs($size) > pow(1024, 1)) return round(($size / pow(1024, 1)), 2) . " kb";
        return $size . " b";
    }

    /**
     * рекурсивно вырезать UBB тэги
     * (до 10 вложений)
     *
     * @param string $str
     * @return string
     */
    static function stripUbbTags($str)
    {
    	//return $str;
    	$str1 = ''; $i=0;
    	while ($str1 <> $str)//РЕЖЕМ ВЛОЖЕННЫЕ ТЭГИ
    	{
    		$str1 = $str;
    		$str = preg_replace('@\[([a-zA-Z]*)[^\]]*?\](.*?)\[/\\1\]@si', '\\2', $str);
    		if ($i++ > 10)
    		{
    			$str = '';
    			break;
    		}
    	}
    	return $str;
    }

    /**
     * преобразовать UBB тэги согласно списку разрешенных тэгов
     *
     * @param string $str
     * @param array $tags	- список разрешенных тэгов
     * @return string
     */
	static function transUbbTags($str, $tags = array('URL', 'IMG', 'B'))
	{
		if (!empty($tags))
		{
			foreach ($tags as $tag)
			{
				$matches = array();
				if (preg_match_all('@\[(' . $tag . ')[^\]]*\](.*?)\[/\\1\]@si', $str, $matches, PREG_OFFSET_CAPTURE))
				{
					foreach ($matches[0] as $m)
						$str = str_replace($m[0], Utils::transUbbTag($tag, $m[0]), $str);
				}
			}
		}
		return Utils::stripUbbTags($str);
	}

	static function transUbbTag($tag, $str)
	{
		$map = array(
			'url' => array('<a href', '>', '</a>'),
			'b' => array('<b', '>', '</b>'),
			'img' => array('<img src="', '', '" />'),
		);
		$matches = array();
   		if (preg_match('@(\[(' . $tag . '))([^\]]*)(\])(.*?)\[/\\2\]@si', $str, $matches, PREG_OFFSET_CAPTURE))
			$str = $map[strtolower($tag)][0] . $matches[3][0] . $map[strtolower($tag)][1] .
				$matches[5][0] . $map[strtolower($tag)][2];

		return $str;
	}

	/**
	 * отрезать строку по границе слова
	 *
	 * @param string $data
	 * @param integer $len
	 * @return string
	 */
	static function substrWord($data, $len)
	{
		if (mb_strlen($data) <= $len)
		{
			return $data;
		}

		$words = mb_split('[ ]+', $data);
/*o
echo '<pre>';
pr($words);
echo '</pre>';
*/
		$str = '';
		foreach($words as $w)
		{
			if (mb_strlen($str . $w . ' ') > $len)
			{
				break;
			}
			$str .= $w . ' ';
		}

		if (empty($str))
		{
			$str = mb_substr($data, 0, $len);
		}

		return $str;
	}
}



?>