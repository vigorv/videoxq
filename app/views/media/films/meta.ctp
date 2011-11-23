<?php
//*
	$content = '<?xml version="1.0" encoding="UTF-8"?>
<metalink version="3.0" xmlns="http://www.metalinker.org/">
<files>';
//*/
	if (!empty($fLst))
	{
		$servers = Film::set_input_server($film['Film']['dir'], false, true);
		if (!empty($servers))
		{
			$prefStep = intval(100/count($servers));
			foreach ($fLst as $f)
			{
				$preference = 100;
				$content .= '<file name="' . basename($f['file_name']) . '">
					<os>Linux-x86</os>
					<size>' . $f['size'] . '</size>
					<resources>
				';
				foreach ($servers as $s)
				{
//УКАЗАТЬ ВСЕ ВОЗМОЖНЫЕ ЗЕРКАЛА (РАЗДАЮЩИЕ) ДЛЯ ФАЙЛА ПОЛЬЗОВАТЕЛЮ ИЗ ДАННОЙ ЗОНЫ
//ОДИН ТЭГ file - ОДНО ЗЕРКАЛО
					$content .= '
<url type="http" location="ru" preference="' . $preference . '">' . $s . '/' . $f['file_name'] . '</url>
';
					$preference = $preference - $prefStep;
				}
				$content .= '</resources></file>';
			}
		}
	}

	$content .= '</files>
</metalink>';
header("Content-Length: " . strlen($content));
	//header("Content-Disposition: attachment; filename=" . $fn);
	//header("Content-Type: download/file");
header("Content-Type: application/metalink+xml");
	//You can set a MIME type of application/metalink+xml for .metalink files on your Web server. If you don't change the MIME type, the Metalink will display as text in Web browsers.
	echo $content;
