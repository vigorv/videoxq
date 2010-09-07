<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app/config/database.php');

$cfg = new DATABASE_CONFIG();

$connectInfo = $cfg->productionMedia;


if ($connect = mysql_connect($connectInfo['host'], $connectInfo['login'], $connectInfo['password']))
{
	mysql_select_db($connectInfo['database']);

	$sql = array('
CREATE TEMPORARY TABLE sl_temp LIKE search_logs;
	',
	'
INSERT into sl_temp SELECT * from search_logs;
	',
	'
UPDATE search_logs set search_logs.hits = (select count(sl_temp.id)+sum(sl_temp.hits)-1 from sl_temp where sl_temp.keyword = search_logs.keyword group by sl_temp.keyword);
	',
	'
UPDATE search_logs set updated = (select max(sl_temp.created) from sl_temp where sl_temp.keyword = search_logs.keyword group by sl_temp.keyword);
	',
	'
TRUNCATE sl_temp;
	',
	'
INSERT into sl_temp SELECT * from search_logs group by keyword order by id;
	',
	'
UPDATE sl_temp set hits = 1 where hits <= 0;
	',
	'
truncate search_logs;
	',
	'
INSERT into search_logs SELECT * from sl_temp;
	');

	foreach ($sql as $s)
	{
		mysql_query($s, $connect);
		$err = mysql_errno($connect);
		if ($err)
		{
			echo 'Error.';
			break;
		}
	}

	if ($err == 0)
	{
		echo 'Done.';
	}

	mysql_close($connect);
}
else
{
	die('Db connection failed');
}
