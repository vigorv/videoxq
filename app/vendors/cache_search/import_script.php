<?php
/**
 *
 */

if (!class_exists('DATABASE_CONFIG'))
{
	die('ERROR. Not configured. Start import data with import.php');
}

$dbConfig = new DATABASE_CONFIG();
$parser = new extSiteParser();

if (empty($dbConfig->cachedSites))
{
	die('ERROR. Not configured. Setup info about cached sites');
}

function generateInsertSql($tableName, $info)
{
 	$fields = '`' . implode('`,`', array_keys($info)) . '`';
 	$values = '';
 	$z = '';
 	foreach ($info as $i)
 	{
 		$values .= $z;
 		if (!is_integer($i))
 			$values .= '"' . $i . '"';
 		else
 			$values .= intval($i);
 		$z = ',';
 	}
	$sql = 'insert into ' . $tableName . ' (' . $fields . ') values (' . $values . ')';
	return $sql;
}

$add = 0; $update = 0; //СТАТИСТИКА ПО ДОБАВЛЕННЫМ И ОБНОВЛЕННЫМ ЗАПИСЯМ В КЭШЭ
//ПОДКЛЮЧАЕМСЯ К ЛОКАЛЬНОЙ БАЗЕ
$loc = mysql_connect($dbConfig->defaultMedia['host'], $dbConfig->defaultMedia['login'], $dbConfig->defaultMedia['password'], true);
if (!$loc)
{
	die('Unable connect to ' . $dbConfig['defaultMedia']['host'] . '@' . $dbConfig['defaultMedia']['login'] . '. Error: ' . mysql_error());
}
mysql_select_db($dbConfig->defaultMedia['database'], $loc);
mysql_query('set NAMES "UTF8"', $loc);

//ПЕРЕБИРАЕМ БАЗЫ ВНЕШНИХ САЙТОВ
foreach ($dbConfig->cachedSites as $site)
{
	$ext = mysql_connect($dbConfig->{$site['dbconfig']}['host'], $dbConfig->{$site['dbconfig']}['login'], $dbConfig->{$site['dbconfig']}['password'], true);
	if (!$ext)
	{
		echo 'Unable connect to ' . $dbConfig->{$site['dbconfig']}['host'] . '. Error: ' . mysql_error() . "\r\n";
		continue;
	}

	$parser->setCurrentSite($site['sitename']);

	mysql_select_db($dbConfig->{$site['dbconfig']}['database'], $ext);
	if (!empty($dbConfig->$site['dbconfig']['encoding']))
	{
		mysql_query('set NAMES "' . $dbConfig->{$site['dbconfig']}['encoding'] . '"', $ext);
	}

	$sql = 'SELECT * FROM ' . $site['tablename'] . ';';
	$extQ = mysql_query($sql, $ext);

	while ($extR = mysql_fetch_assoc($extQ))
	{
		$data = $parser->parseRow($extR);
		if (empty($data['id_original']))
		{
			echo "ERROR. While processing {$site['sitename']} DB. Unexpected parser response\r\n";
			break;
		}
		$sql = 'SELECT * FROM cache_search WHERE id_original = ' . $data['id_original'];
		$locQ = mysql_query($sql, $loc);
		$locR = mysql_fetch_assoc($locQ);

		if (empty($locR))
		{
			//ДОБАВЛЕНИЕ
			$add++;
			$data['modified'] = date('Y-m-d H:i:s');
			$data['site_id'] = $site['id'];
			$sql = generateInsertSql('cache_search', $data);
			mysql_query($sql, $loc);
		}
		else
		{
			if ($locR['modified_original'] < $extR['date'])
			{
				//ОБНОВЛЕНИЕ
				$update++;
			}
		}
//*
echo "\r\n" . $sql . "\r\n";
break;
//*/
	}

	mysql_close($ext);
}

mysql_close($loc);
echo "Done. Processed site count - " . count($dbConfig->cachedSites) . ".\r\nAdded records total - {$add}.\r\nUpdated records total - {$update}.\r\n";