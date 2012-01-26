<?php
/**
 * парсер записей БД сайтов, участвующих в перекрестном поиске videoxq.com
 * для каждого сайта используется отдельный метод
 * формат входных данных: запись из БД в виде ассоциативного массива в кодировке исходной БД
 * формат выходных данных: ассоциативный массив следующей структуры
 * array(12) {

        ["id_original"]         => integer
        ["created_original"]	=> utf8 string, (в формате DATETIME: "YYYY-MM-DD HH:ii:ss")
	["modified_original"]	=> utf8 string, (в формате DATETIME: "YYYY-MM-DD HH:ii:ss")
        ["hidden"]		=> integer
  	["title"]		=> utf8 string, varchar(255)
	["title_original"]	=> utf8 string, varchar(255)
	["year"]		=> integer
	["country"]		=> utf8 string, varchar(30)
	["directors"]           => utf8 string, varchar(255)
	["actors"]		=> utf8 string, varchar(255)
 	["site_id"]             => integer
        ["poster"]		=> utf8 string, varchar(255)
	["url"]			=> utf8 string, varchar(255)
  	["is_license"]		=> integer
        ["media_rating"]	=> float
        ["imdb_rating"]		=> float(3,1) 	
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
	 * имя текущего сайта
	 *
	 * @param string $site
	 */
	public function getCurrentSite()
	{
            return $this->currentSite;
	}
        
	/**
	 * единый метод разбора данных (для унифицированного вызова)
	 *
	 * @param mixed $row - запись БД внешнего сайта в виде: "поле таблицы" => "значение поля таблицы"
	 * @return mixed
	 */
	public function parseRow($row)
	{
		$data_row = array();
                $data = array();
		switch ($this->currentSite)
		{
			case "rumedia":
				$data_row = $this->parseRumediaRow($row);
			break;

			case "animebar":
				$data_row = $this->parseAnimebarRow($row);
			break;
                    
			case "videoxq":
				$data_row = $this->parseVideoxqRow($row);
			break;                    
		}
		if (!empty($data_row))
		{
                        //создадим маасив с еужными значениями, отфильтровав некорретные данные
                        $data = array();
                        $data['id_original'] = intval($data_row['id_original']);
                        $data['created_original'] = $data_row['created_original'];
                        $data['modified_original'] = $data_row['modified_original'];
                        $data['hidden'] = $data_row['hidden'];
			$data['title'] = mb_substr($data_row['title'], 0, 254, 'utf-8');
			$data['title_original'] = mb_substr($data_row['title_original'], 0, 254, 'utf-8');
                        $data['year'] = intval($data_row['year']);
			$data['country'] = mb_substr($data_row['country'], 0, 29, 'utf-8');
			$data['directors'] = mb_substr($data_row['directors'], 0, 254, 'utf-8');
                        $data['actors'] = mb_substr($data_row['actors'], 0, 254, 'utf-8');
			$data['poster'] = mb_substr($data_row['poster'], 0, 254, 'utf-8');
			$data['url'] = mb_substr($data_row['url'], 0, 254, 'utf-8');
                        $data['is_license'] = intval($data_row['is_license']);
                        $data['media_rating'] = $data_row['media_rating'];
                        $data['imdb_rating'] = $data_row['imdb_rating'];
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

                $data['id_original'] = $row['id'];
                $data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
                $data['hidden'] = empty($row['approve'])? 1:0;
                $data['title'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title']);
		$data['title_original'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title2']);
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
                
                $data['poster'] = '';
		$matches = array();
		preg_match('/src="([^"]{1,})"/', $row['short_story'], $matches);
		if (!empty($matches[0]))
		{
        	$matches[0] = substr($matches[0],5);
			$data['poster'] = iconv('windows-1251', 'utf-8//IGNORE', $matches[0]);
		}
		$data['url'] = 'http://rumedia.ws/' . $row['id_original'] . '-'. $row['alt_name'] . '.html';
                $data['is_license'] = 0;
                $data['media_rating'] = 0;
                $data['imdb_rating'] = 0;
		
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
                $data['id_original'] = $row['id'];
                $data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
		$data['hidden'] = empty($row['approve'])? 1:0;
                $data['title'] = iconv('windows-1251', 'utf-8//IGNORE', $row['title']);
                $data['title_original']	= '';
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

		$data['poster'] = '';
		$matches = array();
		preg_match('/<img(.*?)src="([^"]{1,})"/', $row['short_story'], $matches);
		if (!empty($matches[2]))
			$data['poster'] = iconv('windows-1251', 'utf-8//IGNORE', $matches[2]);
		$data['url'] = 'http://animebar.ru/' . $row['id_original'] . '-'. $row['alt_name'] . '.html';
                $data['is_license'] = 0;
                $data['media_rating'] = 0;
                $data['imdb_rating'] = 0;
                
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
                
                $data['id_original'] = $row['id_original'];
                $data['created_original'] = $row['created_original'];
		$data['modified_original'] = $row['modified_original'];
                $data['hidden'] = $row['hidden'];
                $data['title'] = $row['title'];
		$data['title_original'] = $row['title_original'];
                $data['year'] = $row['year'];
		$data['country'] = $row['country'];
		$data['directors'] = $row['directors'];
		$data['actors']	= $row['actors'];
                $data['poster'] = 'http://videoxq.com/img/catalog/'.$row['poster'];
		$data['url'] = 'http://videoxq.com/media/view/' . $row['id_original'];
                $data['is_license'] = $row['is_license'];
                $data['media_rating'] = (empty($row['media_rating']))? 0 : $row['media_rating'];
                $data['imdb_rating'] = (empty($row['imdb_rating']))? 0 : $row['imdb_rating'];
		
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
GROUP_CONCAT(`dir_p`.`name` SEPARATOR ", ") AS `Directors`,
GROUP_CONCAT(`act_p`.`name` SEPARATOR ", ") AS `Actors`,
GROUP_CONCAT(DISTINCT Genres.title SEPARATOR ", ") AS `genres`,
0 AS `site_id`,
0 AS `poster`,
0 AS `url`,
`Film`.`is_license` AS `is_license`

FROM `films` `Film`

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


GROUP BY `Film`.`id`
 */