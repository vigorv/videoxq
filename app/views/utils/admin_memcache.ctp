<?php
if (empty($_REQUEST["IMG"]))
{
	echo '<div align="left"><ul>Управление группами кэша:';
	foreach ($keys as $k)
	{
		$lnk = '';
		if ($k <> $key)
			$lnk = ' (<a href="/admin/utils/memcache/' . $k . '" onclick="return confirm(\'Вы уверены?\');">сбросить</a>)';
		echo'<li>группа <b>' . $k . '</b>' . $lnk . '</li>';
	}
	echo'</ul></div>';
}
/*
УСТАНОВКА ДЛЯ videoxq.com - требуется правка кода memcache.php
	- вставить в начало файла объявление global $MEMCACHE_SERVERS;
	- закомментировать password protect
	- добавить выход на входе getFooter и getHeader
	- заменить строку "$PHP_SELF=$PHP_SELF.'?';" на "global $PHP_SELF; $PHP_SELF='/admin/utils/memcache/?';";
*/
include_once($_SERVER["DOCUMENT_ROOT"] . '/app/vendors/memcache.php');