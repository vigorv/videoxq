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
	 * значение тэга заголовка
	 *
	 * @var string
	 */
	public $titleTag;

	/**
	 * значение тэга описания
	 *
	 * @var string
	 */
	public $descriptionTag;

	/**
	 * значение тэга ключевых слов
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
		return $url;
	}

	/**
	 * инициализация компонента
	 *
	 * @param mixed $controller - вызывающий контроллер
	 */
	public function initialize(&$controller)
	{
		App::import('Model', 'MetaTag');
		$this->db = new MetaTag();
	}
}
