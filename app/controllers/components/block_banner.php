<?php
App::import('component', 'BlocksParent');

class BlockBannerComponent extends BlocksParentComponent
{
	public $components = array('Session');
    public $uses = array('Banner');

	/**
	 * код для вставки перед тэгом </BODY>
	 * каждый баннер добавляет свой код в это свойство
	 * @var string
	 */
	protected $tailHtml = '';

	/**
	 * признак, является ли пользователь абонентом WebStream
	 *
	 * @var integer
	 */
	public $isWS = 0;

	/**
	 * Модель баннеров
	 *
	 * @var Banner
	 */
	public $Banner;

	/**
	 * счетчик переходов по страницам
	 *
	 * @var integer
	 */
	public $hitCnt;

	/**
	 * признак: блокирован ли показ баннеров
	 *
	 * @var boolean
	 */
	protected $lock = false;

	/**
	 * управление блокировкой банеров
	 *
	 * @param boolean $lock true/false блокировать/разблокировать
	 */
	public function lock($lock = false)
	{
		$this->lock = $lock;
	}

	/**
	 * установить признак, является ли пользователь абонентом WebStream
	 *
	 * @param int $isWS
	 */
	public function setIsWS($isWS)
	{
		$this->isWS = $isWS;
	}

	/**
	 * получить код для очередного баннера в ротации для баннероместа
	 * Вызов можно осуществлять прямо из отображения (*.ctp)
	 * например, так: echo $BlockBanner->getBanner('header');
	 *
	 * @param string $placeName
	 * @return string
	 */
	public function getBanner($placeName = '', $ignoreLock = false)
	{
		if (empty($placeName))
		{
			return '';
		}
		$html = '';

		//$banners = Cache::read('Catalog.banners4' . $placeName . '_' . $this->isWS, 'searchres');
		if (empty($banners))
		{
			$conditions = array('Banner.place' => $placeName);
			if (!empty($this->isWS))
			{
				$conditions['Banner.is_webstream'] = 1;
			}
			else
			{
				$conditions['Banner.is_internet'] = 1;
			}
			$banners = $this->Banner->findAll($conditions, null, 'Banner.srt desc');
			Cache::write('Catalog.banners4' . $placeName . '_' . $this->isWS, $banners, 'searchres');
		}
		if (!empty($banners))
		{
			$bannersPlace = array(); $curTime = time();
			foreach ($banners as $b) //ОТБИРАЕМ СПИСОК ВСЕХ АКТУАЛЬНЫХ БАННЕРОВ
			{
				if ($b['Banner']['forever']) //ЕСЛИ БЕССРОЧНЫЙ
				{
					$start = 0; $stop = $curTime;
				}
				else
				{
					$start = strtotime($b['Banner']['start']);
					$stop = strtotime($b['Banner']['stop']);
				}

				if (($curTime < $start) || ($curTime > $stop))
				{
					continue;
				}

				if ($b['Banner']['fixed'])//ЕСЛИ НЕ УЧАСТВУЕТ В РОТАЦИИ, ОТПРАВЛЯЕМ НА ВЫВОД
				{
					if (!$this->lock || $ignoreLock)
					{
						$html .= $b['Banner']['code'];
						$this->tailHtml .= $b['Banner']['tail'];
					}
					continue;
				}
				for ($i = 0; $i < $b['Banner']['priority']; $i++)//ДУБЛИРУЕМ СОГЛАСНО ПРИОРИТЕТУ
				{
					$bannersPlace[] = $b['Banner'];
				}
			}

			if (!empty($bannersPlace))
			{
				$viewIndex = $this->hitCnt % count($bannersPlace);
				if (!$this->lock || $ignoreLock)
				{
					$html .= $bannersPlace[$viewIndex]['code'];
					$this->tailHtml .= $bannersPlace[$viewIndex]['tail'];
				}
			}
		}

		return $html;
	}

	/**
	 * получить код для вывода перед тэгом </BODY>
	 * и инкрементировать счетчик переходов по страницам
	 * метод обязателен к вызову, иначе ротация осуществляться не будет
	 * @var $dec integer - не изменять счетчик переходов (применяется для для нескольких вызовов из вне)
	 * @return string
	 */
	public function getTail($dec = 0)
	{
		if (!$dec)
    		$this->hitCnt++;
    	$this->Session->write('hitCnt', $this->hitCnt);
		return $this->tailHtml;
	}

	function initialize(&$controller)
    {
    	$this->hitCnt = $this->Session->read('hitCnt');
    	if (empty($this->hitCnt)) $this->hitCnt = time('s');
        $this->Banner = ClassRegistry::init('Banner');
        //$this->controller = $controller;
        //return parent::initialize($controller);
    }
}