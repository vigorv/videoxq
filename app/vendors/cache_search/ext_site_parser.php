<?php
/**
 * парсер записей БД сайтов, участвующих в перекрестном поиске videoxq.com
 * для каждого сайта используется отдельный метод
 * формат входных данных: запись из БД в виде ассоциативного массива в кодировке исходной БД
 * формат выходных данных: ассоциативный массив следующей структуры
 * array(12) {
	["id_original"]			=> integer
  	["title"]				=> utf8 string, varchar(255)
	["title_original"]		=> utf8 string, varchar(255)
	["created_original"]	=> utf8 string, (в формате DATETIME: "YYYY-MM-DD HH:ii:ss")
	["modified_original"]	=> utf8 string, (в формате DATETIME: "YYYY-MM-DD HH:ii:ss")
	["hidden"]				=> integer
	["year"]				=> integer
	["country"]				=> utf8 string, varchar(30)
	["directors"]			=> utf8 string, varchar(255)
	["actors"]				=> utf8 string, varchar(255)
	["poster"]				=> utf8 string, varchar(255)
	["url"]					=> utf8 string, varchar(255)
}
 */

class extSiteParser
{
	/**
	 * имя текущего сайта, для которого выполняется разбор данных
	 *
	 * @var string
	 */
	protected $currentSite;

	/**
	 * имя текущего сайта
	 *
	 * @param string $site
	 */
	public function setCurrentSite($site = '')
	{
		$this->currentSite = $site;
	}

	/**
	 * единый метод разбора данных (для унифицированного вызова)
	 *
	 * @param mixed $row - запись БД внешнего сайта в виде: "поле таблицы" => "значение поля таблицы"
	 * @return mixed
	 */
	public function parseRow($row)
	{
		$data = array();
		switch ($this->currentSite)
		{
			case "rumedia":
				$data = $this->parseRumediaRow($row);
			break;

			case "animebar":
				$data = $this->parseAnimebarRow($row);
			break;
                    
			case "videoxq":
				$data = $this->parseVideoxqRow($row);
			break;                    
		}
		if (!empty($data))
		{
			$data['title'] = mb_substr($data['title'], 0, 254, 'utf-8');
			$data['title_original'] = mb_substr($data['title_original'], 0, 254, 'utf-8');
			$data['country'] = mb_substr($data['country'], 0, 29, 'utf-8');
			$data['actors'] = mb_substr($data['actors'], 0, 254, 'utf-8');
			$data['directors'] = mb_substr($data['directors'], 0, 254, 'utf-8');
			$data['poster'] = mb_substr($data['poster'], 0, 254, 'utf-8');
			$data['url'] = mb_substr($data['url'], 0, 254, 'utf-8');
		}
		return $data;
	}

	/**
	 * разбор записи с сайта rumedia
	 *
	 * @param mixed $row
	 * @return mixed
	 */
	public function parseRumediaRow($row)
	{

		$data = array();

		$matches = array();
		preg_match('/src="([^"]{1,})"/', $row['short_story'], $matches);
		$data['poster'] = '';
		if (!empty($matches[0]))
		{
        	$matches[0] = substr($matches[0],5);
			$data['poster'] = iconv('windows-1251', 'utf-8//IGNORE', $matches[0]);
		}
        //iconv сделать
		$data['id_original'] = $row['id'];
		$data['title'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title']);
		$data['title_original'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title2']);
		$data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
		$data['hidden'] = $row['approve'];
		$data['url'] = 'http://rumedia.ws/' . $row['alt_name'] . '.html';
        $data['year']			= 0;
		$data['country']		= '';
		$data['directors']		= '';
		$data['actors']			= '';
		$xfields = explode('||', $row['xfields']);
		foreach ($xfields as $xfield)
		{
			$xf = explode('|', $xfield);
			switch ($xf[0])
			{
				case "m_year":
				case "games_year":
				case "soft_year":
					$data['year'] = intval($xf[1]);
				break;
				case "m_country":
				case "games_country":
				case "soft_country":
					$data['country'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
				case "m_director":
					$data['directors'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
				case "m_actors":
					$data['actors'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
			}
		}
		return $data;
	}

	/**
	 * разбор записи с сайта animebar
	 *
	 * @param mixed $row
	 * @return mixed
	 */
	public function parseAnimebarRow($row)
	{
		$data = array();

		$matches = array();
		preg_match('/<img(.*?)src="([^"]{1,})"/', $row['short_story'], $matches);
		$data['poster'] = '';
		if (!empty($matches[2]))
			$data['poster'] = iconv('windows-1251', 'utf-8//IGNORE', $matches[2]);

		$data['year']			= 0;
		$data['country']		= '';
		$data['directors']		= '';
		$data['actors']			= '';
		$data['title_original']	= '';
		$xfields = explode('||', $row['xfields']);
		foreach ($xfields as $xfield)
		{
			$xf = explode('|', $xfield);
			switch ($xf[0])
			{
				case "a_year":
					$data['year'] = intval($xf[1]);
				break;
				case "a_country":
					$data['country'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
				case "a_director":
					$data['directors'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
				case "a_actors":
					$data['actors'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
			}
		}

		$data['id_original'] = $row['id'];
		$data['title'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title']);
		$data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
		$data['hidden'] = $row['approve'];
		$data['url'] = 'http://animebar.ru/' . $row['alt_name'] . '.html';
		return $data;
	}
        
        
	/**
	 * разбор записи с сайта videoxq
	 *
	 * @param mixed $row
	 * @return mixed
	 */
	public function parseVideoxqRow($row)
	{

		$data = array();

		$matches = array();
		preg_match('/src="([^"]{1,})"/', $row['short_story'], $matches);
		$data['poster'] = '';
		if (!empty($matches[0]))
		{
        	$matches[0] = substr($matches[0],5);
			$data['poster'] = iconv('windows-1251', 'utf-8//IGNORE', $matches[0]);
		}
        //iconv сделать
		$data['id_original'] = $row['id'];
		$data['title'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title']);
		$data['title_original'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title2']);
		$data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
		$data['hidden'] = $row['approve'];
		$data['url'] = 'http://rumedia.ws/' . $row['alt_name'] . '.html';
                $data['year']			= 0;
		$data['country']		= '';
		$data['directors']		= '';
		$data['actors']			= '';
		$xfields = explode('||', $row['xfields']);
		foreach ($xfields as $xfield)
		{
			$xf = explode('|', $xfield);
			switch ($xf[0])
			{
				case "m_year":
				case "games_year":
				case "soft_year":
					$data['year'] = intval($xf[1]);
				break;
				case "m_country":
				case "games_country":
				case "soft_country":
					$data['country'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
				case "m_director":
					$data['directors'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
				case "m_actors":
					$data['actors'] = iconv('windows-1251', 'utf-8//IGNORE', $xf[1]);
				break;
			}
		}
		return $data;
	}
        
}


/*
 
SELECT
`Film`.`id` AS `id_original`,
`Film`.`created` AS `created_original`,
`Film`.`modified` AS `modified_original`, 
(`Film`.`active` != 1) AS `hidden`,
`Film`.`title` AS `title`,
`Film`.`title_en` AS `title_original`,
`Film`.`year` AS `year`,
GROUP_CONCAT(DISTINCT `Country`.`title` SEPARATOR ", ") AS `country`,
GROUP_CONCAT(DISTINCT DirPerson.name SEPARATOR ", ") AS `directors`,
1 AS `actors`,
GROUP_CONCAT(DISTINCT Genres.title SEPARATOR ", ") AS `genres`,
3 AS `site_id`,
0 AS `poster`,
0 AS `url`,
`Film`.`is_license` AS `is_license`


FROM 
`films` AS `Film` 

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


LEFT JOIN 
`films_persons` AS `FilmsPersons` 
ON 
(`Film`.`id` = `FilmsPersons`.`film_id`) 
LEFT JOIN 
`persons` AS `DirPerson` 
ON 
(`FilmsPersons`.`person_id` = `DirPerson`.`id`)

LEFT JOIN 
`persons_professions` AS `DirPersonsProfessions` 
ON 
(`DirPersonsProfessions`.`person_id` = `DirPerson`.`id`  AND
 `DirPersonsProfessions`.`profession_id` = 1
) 

WHERE
`DirPersonsProfessions`.`profession_id` = 1  


GROUP BY `Film`.`id`


 */

/*
(SELECT distinct id_cost
  FROM Message108 where id_directory=39)

 */
/*
SELECT
`Film`.`id` AS `id_original`,
`Film`.`created` AS `created_original`,
`Film`.`modified` AS `modified_original`, 
(`Film`.`active` != 1) AS `hidden`,
`Film`.`title` AS `title`,
`Film`.`title_en` AS `title_original`,
`Film`.`year` AS `year`,
GROUP_CONCAT(DISTINCT `Country`.`title` SEPARATOR ", ") AS `country`,
GROUP_CONCAT(DISTINCT DirPerson.name SEPARATOR ", ") AS `directors`,
1 AS `actors`,
GROUP_CONCAT(DISTINCT Genres.title SEPARATOR ", ") AS `genres`,
3 AS `site_id`,
0 AS `poster`,
0 AS `url`,
`Film`.`is_license` AS `is_license`


FROM 
`films` AS `Film` 

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


LEFT JOIN 
`films_persons` AS `FilmsPersons` 
ON 
(`Film`.`id` = `FilmsPersons`.`film_id`) 
LEFT JOIN 
(
SELECT * FROM persons
)
 AS `DirPerson` 
ON 
(`FilmsPersons`.`person_id` = `DirPerson`.`id`)


GROUP BY `Film`.`id`
 */

