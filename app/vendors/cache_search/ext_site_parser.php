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
		}
		if (!empty($data))
		{
			$data['title'] = mb_substr($data['title'], 0, 254, 'utf-8');
			$data['title_original'] = mb_substr($data['title_original'], 0, 254, 'utf-8');
			$data['country'] = mb_substr($data['country'], 0, 29, 'utf-8');
			$data['actors'] = mb_substr($data['actors'], 0, 254, 'utf-8');
			$data['directors'] = mb_substr($data['directors'], 0, 254, 'utf-8');
			$data['poster'] = mb_substr($data['poster'], 0, 254, 'utf-8');
			$data['url'] = mb_substr($data['poster'], 0, 254, 'utf-8');
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
}