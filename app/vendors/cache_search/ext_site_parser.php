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
$row['short_story'] = '<!--TBegin--><a href="http://wsmedia.su/uploads/posts/2009-11/1257220486_comedy-club.jpg" onclick="return hs.expand(this)" ><img align="left" src="http://wsmedia.su/uploads/posts/2009-11/thumbs/1257220486_comedy-club.jpg" style="border: none;" width="150" height="214" alt=\'Comedy Club (2005-2010) SATRip\' title=\'Comedy Club (2005-2010) SATRip\'  /></a><!--TEnd-->Коллекция выпусков знаменитого юмористического шоу. Реклама, футбол, секс, офисные интриги, начальники и подчиненные, новости, светская жизнь, отношения – все это просто предмет для шуток для ребят из Comedy Club. Осторожно, они опасны! Дерзкие молодые талантливые комики новой волны не признают никаких авторитетов, приличий и правил. Они запросто могут обсмеять любую звездную персону – даже если она сидит в зале. Несмотря на этот факт, на представлениях Comedy Club всегда много знаменитостей. Ведь здесь не только артисты, но и зрители в зале – находчивы и остроумны. И часто в процессе шоу рождаются новые шутки, которые потом подхватят офисы и улицы.
<div align="center"><span style="color:#009900"><span style="font-family:Georgia"><b>Trance: Добавил выпуск #213</b></span></span></div>';

		$data = array();

		$matches = array();
		preg_match('/<img /', $row['short_story'], $matches);

		$data['id_original'] = $row['id'];
		$data['title'] = $row['title'];
		$data['title_original'] = $row['title2'];
		$data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
		$data['hidden'] = $row['approve'];
		$data['year'] = '2010';
		$data['country'] = '';
		$data['directors'] = '';
		$data['actors'] = '';
		$data['poster'] = '';
		$data['url'] = '';
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
			$data['poster'] = $matches[2];

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
					$data['country'] = iconv('windows-1251', 'utf-8', $xf[1]);
				break;
				case "a_director":
					$data['directors'] = iconv('windows-1251', 'utf-8', $xf[1]);
				break;
				case "a_actors":
					$data['actors'] = iconv('windows-1251', 'utf-8', $xf[1]);
				break;
			}
		}

		$data['id_original'] = $row['id'];
		$data['title'] = iconv('windows-1251', 'utf-8', $row['title']);
		$data['created_original'] = $row['date'];
		$data['modified_original'] = $row['date'];
		$data['hidden'] = $row['approve'];
		$data['url'] = 'http://animebar.ru/' . $row['alt_name'] . '.html';
		return $data;
	}
}