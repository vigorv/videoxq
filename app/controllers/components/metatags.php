<?php
/**
 * работа с метатэгами сайта по адресам страниц
 *
 */
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
	 * @param string $url
	 * @return string
	 */
	public function fixUrl($url)
	{
		$argsMap = array(//КАРТА ИСПОЛЬЗУЕМЫХ АРГУМЕНТОВ (ОСТАЛЬНЫЕ БУДЕМ ИГНОРИРОВАТЬ)
			'genre', 'country', 'type', 'year', 'sort'
		);
		$urlInfo = parse_url($url);
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
					if (in_array($p[0], $argsMap))
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

	public function get($url, $lang = '')
	{
		$base = $this->db->getMetaTagByURL('');//! БАЗОВЫЕ ТЭГИ (ПРИСУТСТВУЮЩИЕ НА ВСЕХ СТРАНИЦАХ САЙТА)
		$tags = $this->db->getMetaTagByURL($this->fixUrl($url));
		if (!empty($tags))
		{
			if (!empty($lang))
			{
				$langFix = '_' . $lang;
			}
			foreach ($tags as $t)
			{
				if ($t['MetaTag']['isbase'])
				{
//ЗАМЕЩАЮТСЯ ТОЛЬКО НЕПУСТЫЕ ТЭГИ
					if (!empty($t['MetaTag']['title' . $langFix]))
					{
						$this->titleTag = $t['MetaTag']['title' . $langFix];
					}

					if (!empty($t['MetaTag']['keywords' . $langFix]))
					{
						$this->keywordsTag = $t['MetaTag']['keywords' . $langFix];
					}

					if (!empty($t['MetaTag']['description' . $langFix]))
					{
						$this->descriptionTag = $t['MetaTag']['description' . $langFix];
					}
				}
				else
				{
					$this->titleTag .= $t['MetaTag']['title' . $langFix];
					$this->keywordsTag .= $t['MetaTag']['keywords' . $langFix];
					$this->descriptionTag .= $t['MetaTag']['description' . $langFix];
				}

				if (!empty($t['MetaTag']['title' . $langFix]))
				{
					$this->titleTag .= ' ';
				}

				if (!empty($t['MetaTag']['keywords' . $langFix]))
				{
					$this->keywordsTag .= ', ';
				}

				if (!empty($t['MetaTag']['description' . $langFix]))
				{
					$this->descriptionTag .= ' ';
				}
			}

			if (!empty($base))
			{
//ДОБАВЛЯЕМ БАЗОВЫЕ ТЭГИ ДЛЯ ВСЕХ СТРАНИЦ
				$this->titleTag .= $base[0]['MetaTag']['title' . $langFix];
				$this->keywordsTag = implode(', ', array($base[0]['MetaTag']['keywords' . $langFix], $this->keywordsTag));
				$this->descriptionTag .= $base[0]['MetaTag']['description' . $langFix];
			}
			$this->keywordsTag = $this->keywordsDups();
			$this->descriptionTag = $this->descriptionDups();
		}
	}

	/**
	 * удалить дубли из ключевых слов (слова разбиваются по запятой ",")
	 *
	 * @return string $keywords
	 */
	public function keywordsDups()
	{
		$lst = preg_split("/,[\s]?/", $this->keywordsTag);
		$lst = array_unique($lst);
		return implode(', ', $lst);
	}

	/**
	 * удалить дубли из описания (предложения разделяются по точке ".")
	 *
	 * @return  string $description
	 */
	public function descriptionDups()
	{
		$lst = preg_split("/.[\s]?/", $this->descriptionTag);
		$lst = array_unique($lst);
		return implode('. ', $lst);
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
