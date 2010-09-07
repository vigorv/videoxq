<?php
/**
 * преобразование UTF в Unicode
 * requires mb_string suport
 *
 */
class uuConverter
{

	function utfToUnicode($text)
	{
//return htmlentities($text, ENT_QUOTES);
//return htmlspecialchars($text);
//return $text;
		$length = mb_strlen($text);
		$result='';
		for ($i = 0; $i < $length; $i++)
		{
			$c = mb_substr($text, $i, 1);
			if ($c > 'z')
			{
				$result .= '&#' . $this->ordUTF8($c). ';';
			}
			else
				$result .= $c;
		}
		return $result;
	}

	function unicodeToUtf($text) {
//return $text;
		$result = preg_replace('/(&#[0-9];)/', "mb_convert_encoding('\\1', 'UTF-8', 'HTML-ENTITIES')", $text);
		return $result;
	}

	function ordUTF8($c)
	{
		$ud = 0;
		if (ord($c{0})>=0 && ord($c{0})<=127)
			$ud = ord($c{0});
		if (ord($c{0})>=192 && ord($c{0})<=223)
			$ud = (ord($c{0})-192)*64 + (ord($c{1})-128);
		if (ord($c{0})>=224 && ord($c{0})<=239)
			$ud = (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
		if (ord($c{0})>=240 && ord($c{0})<=247)
			$ud = (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
		if (ord($c{0})>=248 && ord($c{0})<=251)
			$ud = (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
		if (ord($c{0})>=252 && ord($c{0})<=253)
			$ud = (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
		if (ord($c{0})>=254 && ord($c{0})<=255) //error
			$ud = false;
		return $ud;
	}
}