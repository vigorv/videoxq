<?php
	$fn = $vId . '_' . $tId . '.metalink';

	$content = '<?xml version="1.0" encoding="UTF-8"?>
<metalink version="3.0" xmlns="http://www.metalinker.org/">
<files>';

	foreach ($fLst as $f)
	{
//УКАЗАТЬ ВСЕ ВОЗМОЖНЫЕ ЗЕРКАЛА (РАЗДАЮЩИЕ) ДЛЯ ФАЙЛА ПОЛЬЗОВАТЕЛЮ ИЗ ДАННОЙ ЗОНЫ
//ОДИН ТЭГ file - ОДНО ЗЕРКАЛО
		$content .= '<file name="270_the_gold_rush.mp4">
<os>Linux-x86</os>
<size>392606717</size>
<resources>
<url type="http"
location="ru"
preference="100">
http://92.63.196.50/t/the_gold_rush/270/the_gold_rush.mp4
</url>
</resources>
</file>';
	}

	$content .= '</files>
</metalink>';
	header("Content-Length: " . strlen($content));
	//header("Content-Disposition: attachment; filename=" . $fn);
	//header("Content-Type: download/file");
	header("Content-Type: application/metalink+xml");
	//You can set a MIME type of application/metalink+xml for .metalink files on your Web server. If you don't change the MIME type, the Metalink will display as text in Web browsers.
	echo $content;
