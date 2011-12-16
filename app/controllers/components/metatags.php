<?php
/**
 * работа с метатэгами сайта по адресам страниц
 *
 */
function trimVal($val)
{
	$val = trim($val);
	return !empty($val);
}

class MetatagsComponent extends Object
{
	/**
	 * модель для работы с таблицей тэгов
	 *
	 * @var AppModel
	 */
	public $db;

	/**
	 * значение тэга заголовка для запрошенного URL
	 *
	 * @var string
	 */
	public $titleTag;

	/**
	 * значение тэга описания для запрошенного URL
	 *
	 * @var string
	 */
	public $descriptionTag;

	/**
	 * значение тэга ключевых слов для запрошенного URL
	 *
	 * @var string
	 */
	public $keywordsTag;

	/**
	 * подготовка URL к "правильному" виду. Упорядочивание параметров и их значений. Удаление лишнего
	 *
	 * @param string $url - относительный! url
	 * @return string
	 */
	public function fixUrl($url, $argsMap = array())
	{
                if (!$argsMap){
                    //по умолчанию список следующий
                    $argsMap = array(//КАРТА ИСПОЛЬЗУЕМЫХ АРГУМЕНТОВ (ОСТАЛЬНЫЕ БУДЕМ ИГНОРИРОВАТЬ)
			'genre', 'country', 'type', 'year_start', 'year_end', 'imdb_start', 'imdb_end', 'sort');
                }
		$urlInfo = parse_url(Configure::read('App.siteUrl') . $url);
		$path = explode('/', $urlInfo['path']);
//ОПРЕДЕЛЯЕМ АРГУМЕНТЫ ДЛЯ СОРТИРОВКИ
		$args = array(); //СЮДА ОТБЕРЕМ АРГУМЕНТЫ
		$newPath = array(); //СЮДА ОТБЕРЕМ ОСТАЛЬНЫЕ ПАРАМЕТРЫ
		foreach ($path as $pth)
		{
			if (!empty($pth))
			{
				if (strpos($pth, ':'))
				{
					$p = explode(':', $pth);
					if (in_array(str_replace('%', '', $p[0]), $argsMap))
					{
						$vls = explode(',', $p[1]);
						sort($vls);
						$args[] = $p[0]. ':' . implode(',', $vls);//ЗНАЧЕНИЕ АРГУМЕНТА ТОЖЕ СОРТИРУЕМ
					}
				}
				else
				{
					$newPath[] = $pth;
				}
			}
		}
		sort($args);

//СОБИРАЕМ URL
		$original = $url;
		$url = '';
		if (!empty($newPath))
		{
			$url = '/' . implode('/', $newPath);
		}

		if (!empty($args))
		{
			$url .= '/' . implode('/', $args);
		}

		if (!empty($urlInfo['query']))
		{
			$url .= '?' . $urlInfo['query'];
		}
		if (!empty($urlInfo['fragment']))
		{
			$url .= '#' . $urlInfo['fragment'];
		}
		if (empty($url))
		{
			return $original;
		}
		return $url;
	}

	/**
	 * заместить метатэги новыми непустыми значениями.
	 *
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 */
	public function set($title, $keywords, $description)
	{
//ЗАМЕЩАЮТСЯ ТОЛЬКО НЕПУСТЫЕ ТЭГИ
		if (!empty($title))
		{
			$this->titleTag = $title;
		}

		if (!empty($keywords))
		{
			$this->keywordsTag = $keywords;
		}

		if (!empty($description))
		{
			$this->descriptionTag = $description;
		}
	}

	/**
	 * добавить метатэги (вставляются в начало).
	 *
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 */
	public function insert($title, $keywords, $description)
	{
		if (!empty($title))
		{
			$this->titleTag = $title . ' - ' . $this->titleTag;
		}

		if (!empty($keywords))
		{
			$this->keywordsTag = $keywords . ', ' . $this->keywordsTag;
		}

		if (!empty($description))
		{
			$this->descriptionTag = $description . ' ' . $this->descriptionTag;
		}
	}

	/**
	 * вставить тэги в конец (используется для общих тэгов)
	 *
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 */
	public function append($title, $keywords, $description)
	{
		if (!empty($title))
		{
			$this->titleTag .= ' - ' . $title;
		}

		if (!empty($keywords))
		{
			$this->keywordsTag .= ', ' . $keywords;
		}

		if (!empty($description))
		{
			$this->descriptionTag .= ' ' . $description;
		}
	}

	public function get($url, $lang = '')
	{
                /*pr('fixUrl("'.$url.'"): '.$this->fixUrl($url));*/
		$base = $this->db->getMetaTagByURL('');//! ОСНОВНЫЕ ТЭГИ (ПРИСУТСТВУЮЩИЕ НА ВСЕХ СТРАНИЦАХ САЙТА)
		//$tags = $this->db->getMetaTagByUrl($this->fixUrl($url));
                //echo "<br>get tags<br>\n";
                //
                //КАРТА ИСПОЛЬЗУЕМЫХ АРГУМЕНТОВ
                $argsMap = array('genre', 'country', 'type'); 
		$tags = $this->db->getMetaTagsByURLMask($this->fixUrl($url, $argsMap));
                
		$langFix = '';
		if (!empty($lang))
		{
			$langFix = '_' . $lang;
		}
		if (!empty($tags))
		{
			foreach ($tags as $t)
			{
				if ($t['MetaTag']['isbase'])
				{
					$this->set($t['MetaTag']['title' . $langFix], $t['MetaTag']['keywords' . $langFix], $t['MetaTag']['description' . $langFix]);
				}
				else
				{
					$this->insert($t['MetaTag']['title' . $langFix], $t['MetaTag']['keywords' . $langFix], $t['MetaTag']['description' . $langFix]);
				}
			}
		}

		if (!empty($base))
		{
//ДОБАВЛЯЕМ БАЗОВЫЕ ТЭГИ ДЛЯ ВСЕХ СТРАНИЦ
			$this->append($base[0]['MetaTag']['title' . $langFix], $base[0]['MetaTag']['keywords' . $langFix], $base[0]['MetaTag']['description' . $langFix]);
		}
	}

	/**
	 * удалить дубли из ключевых слов (слова разбиваются по запятой с пробелом", ")
	 */
	public function keywordsDups()
	{
		$lst = preg_split("/,[\s]+/", $this->keywordsTag);
		$lst = array_unique($lst);
		$lst = array_filter($lst, "trimVal");//УБИРАЕМ ПУСТЫЕ ЗНАЧЕНИЯ ИЗ МАССИВА
		$this->keywordsTag = implode(', ', $lst);
	}

	/**
	 * удалить дубли из описания (предложения разделяются по точке с пробелом". ")
	 */
	public function descriptionDups()
	{
		$lst = preg_split("/\.[\s]+/", $this->descriptionTag);
		$lst = array_unique($lst);
		$lst = array_filter($lst, "trimVal");//УБИРАЕМ ПУСТЫЕ ЗНАЧЕНИЯ ИЗ МАССИВА
		$this->descriptionTag =  implode('. ', $lst);
	}

	/**
	 * инициализация компонента
	 *
	 * @param mixed $controller - вызывающий контроллер
	 */
	public function initialize(&$controller)
	{
        $this->controller =& $controller;

        App::import('Model', 'MetaTag');//ИМПОРТИРУЕМ, ТК В ВЫЗЫВАЕМОМ КОНТРОЛЛЕРЕ МОДЕЛЬ МОЖЕТ БЫТЬ НЕ ПОДКЛЮЧЕНА
		$this->db = new MetaTag();

		$this->titleTag = '';
		$this->descriptionTag = '';
		$this->keywordsTag = '';
	}
}
