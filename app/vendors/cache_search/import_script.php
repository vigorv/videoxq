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
    die('ERROR. Not configured. Set up info about cached sites');
}

function generateInsertSql($tableName, $info, $db)
{
    $fields = '`' . implode('`,`', array_keys($info)) . '`';
    $values = '';
    $z = '';
    foreach ($info as $i)
    {
            $values .= $z;
            if (!is_integer($i))
                    $values .= '"' . mysql_real_escape_string($i, $db) . '"';
            else
                    $values .= intval($i);
            $z = ',';
    }
    $sql = 'insert into ' . $tableName . ' (' . $fields . ') values (' . $values . ')';
    return $sql;
}

function generateUpdateSql($id_original, $tableName, $info, $db)
{
    $sql = 'UPDATE ' . $tableName . ' SET ';
    $z = '';
    foreach ($info as $field => $value)
    {
            $sql .= $z . '`' . $field . '` = ';
            if (!is_integer($value))
                    $sql .= '"' . mysql_real_escape_string($value, $db) . '"';
            else
                    $sql .= intval($value);
            $z = ',';
    }
    $sql .= ' WHERE id_original = ' . $id_original;
    return $sql;
}

//------------------------------------------------------------------------------

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
	if (!empty($dbConfig->{$site['dbconfig']}['encoding']))
	{
		$sql = 'set NAMES "' . strtoupper($dbConfig->{$site['dbconfig']}['encoding']) . '"';
		mysql_query($sql, $ext);
//echo $sql;
	}
        if ($parser->getCurrentSite() == 'videoxq'){
            $sql = '
            SELECT
            `Film`.`id` AS `id_original`,
            `Film`.`created` AS `created_original`,
            `Film`.`modified` AS `modified_original`, 
            (`Film`.`active` != 1) AS `hidden`,
            `Film`.`title` AS `title`,
            `Film`.`title_en` AS `title_original`,
            `Film`.`year` AS `year`,
            GROUP_CONCAT(DISTINCT `Country`.`title` SEPARATOR ", ") AS `country`,
            GROUP_CONCAT(`dir_p`.`name` SEPARATOR ", ") AS `Directors`,
            GROUP_CONCAT(`act_p`.`name` SEPARATOR ", ") AS `Actors`,
            GROUP_CONCAT(DISTINCT Genres.title SEPARATOR ", ") AS `genres`,
            3 AS `site_id`,
            `FimlPictures`.`file_name` AS `poster`,
            "url" AS `url`,
            `Film`.`is_license` AS `is_license`

            FROM `films` `Film`


            LEFT JOIN
            `film_pictures` AS `FimlPictures`
            ON 
            (`Film`.`id` = `FimlPictures`.`film_id` AND `FimlPictures`.`type` = `poster`)
            

            LEFT JOIN
            `films_persons` AS `FimlsPersons`
            ON 
            (`Film`.`id` = `FimlsPersons`.`film_id` AND `FimlsPersons`.`profession_id` IN (1,3))

            LEFT JOIN
            `persons` AS `dir_p`
            ON 
            (`dir_p`.`id` = `FimlsPersons`.`person_id` AND `FimlsPersons`.`profession_id` = 1 AND `Film`.`id` = `FimlsPersons`.`film_id`)

            LEFT JOIN
            persons AS `act_p`
            ON 
            (`act_p`.`id` = `FimlsPersons`.`person_id` AND `FimlsPersons`.`profession_id` = 3 AND `Film`.`id` = `FimlsPersons`.`film_id`)

            LEFT JOIN 
            `films_genres` AS `FilmsGenres` 
            ON 
            (`Film`.`id` = `FilmsGenres`.`film_id`) 
            LEFT JOIN 
            `genres` AS `Genres` 
            ON 
            (`FilmsGenres`.`genre_id` = `Genres`.`id`) 

            LEFT JOIN 
            `countries_films` AS `FilmsCountries` 
            ON 
            (`Film`.`id` = `FilmsCountries`.`film_id`) 
            LEFT JOIN 
            `countries` AS `Country` 
                ON 
            (`FilmsCountries`.`country_id` = `Country`.`id`)

            GROUP BY `Film`.`id`';
        }
        else{
            $sql = 'SELECT * FROM ' . $site['tablename'] . ';';
        }
	
        
	$extQ = mysql_query($sql, $ext);
        // перебор всех записей из таблицы текущей БД
	while ($extR = mysql_fetch_assoc($extQ))
	{
            $data = $parser->parseRow($extR);
            if (empty($data['id_original']))
            {
                    echo "ERROR. While processing {$site['sitename']} DB. Unexpected parser response\r\n";
                    break;
            }
            
            // смотрим таблиццу кэша на наличие такого фильма в ней
            $sql = 'SELECT * FROM cache_search WHERE site_id = ' . $site['id'] . ' AND id_original = ' . $data['id_original'];
            $locQ = mysql_query($sql, $loc);
            $locR = mysql_fetch_assoc($locQ);
            
            // если фильма еще нет в таблице то делаем вставку записи
            if (empty($locR))
            {
                    //ДОБАВЛЕНИЕ
                    $data['modified'] = date('Y-m-d H:i:s');
                    $data['site_id'] = $site['id'];
                    $sql = generateInsertSql('cache_search', $data, $loc);
                    if (!mysql_query($sql, $loc))
                    {
                            echo 'ERROR. SQL Error - ' . mysql_error($loc) . "\r\n";
                    }
                    else
                            $add++;
            }
            // если фильм уже есть в таблице, то апдейтим 
            else
            {
                    if ($locR['modified_original'] < $extR['date'])
                    {
                            //ОБНОВЛЕНИЕ
                            $data['modified'] = date('Y-m-d H:i:s');
                            $sql = generateUpdateSql($locR['id_original'], 'cache_search', $data, $loc);
                            if (!mysql_query($sql, $loc))
                            {
                                    echo 'ERROR. SQL Error - ' . mysql_error($loc) . "\r\n";
                            }
                            else
                                    $update++;
                    }
            }
//*
//echo "\r\n" . $sql . "\r\n";
//if ($add + $update > 50)
//break;
//*/
	}

	mysql_close($ext);
}

mysql_close($loc);
echo "Done. Processed site count - " . count($dbConfig->cachedSites) . ".\r\nAdded records total - {$add}.\r\nUpdated records total - {$update}.\r\n";