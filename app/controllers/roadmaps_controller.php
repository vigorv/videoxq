<?php
App::import('Model', 'Alias');
App::import('Model', 'Referrer');
App::import('Model', 'Chain');
class RoadmapsController extends AppController
{
    public $name = 'Roadmaps';
    public $uses = array('Alias', 'Referrer', 'Chain');

    /**
     * Модель классификатора
     *
     * @var Alias
     */
    public $Alias;

    /**
     * Модель адресов (рефералов)
     *
     * @var Referrer
     */
    public $Referrer;


    /**
     * Модель цепочек переходов
     *
     * @var Chain
     */
    public $Chain;

    /**
     * Список опций для администрирования
     */
	public function index()
	{
	}

    /**
     * построение графа популярных переходов
     */
	public function graph()
	{
		//ВЫБИРАЕМ ТОЧКИ ВХОДА
		$enters = array();
		for ($external = 0; $external < 2; $external++)//ВЫБОРКИ И ВНУТРЕННИХ И ВНЕШНИХ РЕФЕРАЛОВ
		{
			$enters[$external] = $this->Referrer->getEnters($external);

			if (!empty($enters[$external]))
			{
				foreach ($enters[$external] as $key => $enter)
				{
					$chainEnters = $this->Referrer->getChainEnters($enter, $external);
					if (count($chainEnters) > 0)
					{
						$tree = array();
						foreach ($chainEnters as $ce)
						{
							$tree[] = array(
								"zveno"	=> $ce,
								"tree"	=> $this->Referrer->getChain($ce)
							);
						}
						$enters[$external][$key]['tree'] = $tree;
					}
				}
			}
		}
		$this->set('enters', $enters);
	}

    /**
     * Список алиасов классификатора
     */
	public function alist()
	{
		$aliases = $this->Alias->findAll(null, null, 'Alias.except desc, Alias.power desc, Alias.name asc');
		$this->set('aliases', $aliases);
	}

	/**
     * Форма редактирования/добавления алиасов
	 *
	 * @param integer $id - идентификатор алиаса
	 */
	public function aform($id = null)
	{
		$alias = null;
		if(!empty($id))
			$alias = $this->Alias->read(null, $id);
		$this->set('alias', $alias);
	}

	/**
     * Удалить алиас из классификатора и связанные с ним цепочки переходов
	 *
	 * @param integer $id			- идентификатор алиаса
	 * @param integer $dropChains	- удалить, связанные с рефералами алиаса цепочки или нет
	 */
	public function adel($id = null, $dropChains = false)
	{
		$result = 0;
		if (!empty($id))
		{
			if ($dropChains)
			{
				$result = $this->Referrer->deleteAll('Referrer.alias_id = ' . $id);
			}
			else
				$result = $this->Alias->del($id);
		}
		$this->set('result', $result);
	}

    /**
     * Добавление/сохранение Алиаса
     */
	public function asave()
	{
		$result = 0;
		if (!empty($this->data))
		{
			if (empty($this->data['Alias']['power']))
				$this->data['Alias']['power'] = 0;
			if (!isset($this->data['Alias']['except']))
				$this->data['Alias']['except'] = 0;
			$result = $this->Alias->save($this->data);
		}
		$this->set('result', $result);
	}

    /**
     * связать рефералы с новыми алиасами классификатора
     *
     */
    public function link()
    {
    	//ВЫБИРАЕМ НЕСВЯЗАННЫЕ С КЛАССИФИКАТОРОМ РЕФЕРАЛЫ
    	$refs = $this->Referrer->findAll(array('Referrer.alias_id' => null), null);
    	$updated = 0;//СКОЛЬКО РЕФЕРАЛОВ ОБНОВЛЕНО
    	$all = count($refs);//СКОЛЬКО РЕФЕРАЛОВ НАЙДЕНО
    	if ($all > 0)
    	{
    		foreach ($refs as $r)
    		{
    			$locationId = $this->getLocationId($r['Referrer']['url']);
    			if ($locationId > 0)
    			{
    				$r['Referrer']['alias_id'] = $locationId;
    				if ($this->Referrer->save($r))
    				{
    					$updated++;
    				}
    			}
    		}
    	}
    	$this->set("all", $all);
    	$this->set("updated", $updated);
    	$this->pageTitle = 'Восстановление соответствия рефералов и алиасов классификатора';
    	$this->set('pageTitle', $this->pageTitle);
    }

	/**
     * сброс связей рефералов с алиасами классификатора
	 *
	 * @param integer $dropChains - 1 удалить данные статистики по цепочкам переходов
	 */
    public function reset($dropChains = 0)
    {
    	//ВЫБИРАЕМ НЕСВЯЗАННЫЕ С КЛАССИФИКАТОРОМ РЕФЕРАЛЫ
    	$refs = $this->Referrer->updateAll(array('Referrer.alias_id' => null));

    	if ($dropChains)
    	{
    		$this->Chain->deleteAll('Chain.id > 0');
    	}
    	$this->set('dropChains', $dropChains);
    }

    /**
     * получить идентификатор маски адреса по твблице классификатора (алиасов)
     *
     * @param string $location
     * @return integer
     */
    private function getLocationId($location)
    {
    	$aliases = $this->Alias->findAll(null, null, 'except desc, power desc');
    	$locationId = 0;
    	//$referrerId = 0;
    	if (count($aliases) > 0)
    	{
    		foreach ($aliases as $alias)
    		{
    			//if (empty($referrerId))
    			//{
	    			//if (preg_match(preg_quote($alias['Alias']['url'], '/'), $location))
	    			if (preg_match('/' . str_replace('/', '\/', $alias['Alias']['url']) . '/', $location))
	    			{
	    				if (!$alias['Alias']['except'])
	    					$locationId = $alias['Alias']['id'];
	    				else
	    					$locationId = -1;//ИСКЛЮЧЕНИЯ НЕ УЧИТЫВАЕМ
	    				break;
	    			}
    			//}
    		}
    	}
/*
error_reporting(E_ALL);
//СОХРАНЕНИЕ В ЛОГ (ДЛЯ ТЕСТИРОВАНИЯ)
    	try {
    		$filename = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/roadmap.txt';
	    	$file = fopen($filename, 'a+');
	    	if ($file)
	    	{
	    		fwrite($file, "{{$location}\t{$locationId}\n");
				fclose($file);
	    	}
    	}
    	catch (Exception $e)
    	{

    	}
//*/
		return $locationId;
    }

    /**
     * сохранение звена цепочки переходов по страницам
     *
     * @param string $referrer - адрес реферала к страницу
     * @param string $location - адрес страницы
     */
    public function add($referrerIn = '', $locationIn = '')
    {
    	$external = (strpos($referrerIn, $_SERVER['HTTP_HOST']) === false);

    	$this->layout = "ajax";
    	$replace = array(
    		'-2F-'					=> '/',
    		'-3A-'					=> ':',
    		$_SERVER['HTTP_HOST']	=> ''
    	);
    	$referrer = strtr($referrerIn, $replace);
    	$location = strtr($locationIn, $replace);
    	if (empty($location) && !empty($referrer))//ЗНАЧИТ ЭТО ТОЧКА ВХОДА "ИЗ ЗАКЛАДОК"
    	{
    		$location = $referrer;
    		$referrer = '';
    	}
    	if (empty($location)) $location = '/';
    	//if (!empty($referrerIn) && empty($referrer)) $referrer = '/';

    	//ОПРЕДЕЛЯЕМ СОХРАНЯЛИ ИЛИ НЕТ
		$locationData = null;
		$referrerData = null;
		if (!empty($location))
			$locationData = $this->Referrer->find(array('Referrer.url' => $location));
		if (!empty($referrer))
		{
			$referrerData = $this->Referrer->find(array('Referrer.url' => $referrer));
			if ((empty($referrerData)) && ($external)) //ВНЕШНИЙ РЕФЕРАЛ НУЖНО СОХРАНИТЬ
			{
				$aliasId = $this->getLocationId($referrer);
				$referrerData['Referrer']['url'] = $referrer;
				$referrerData['Referrer']['external'] = intval($external);
				if (!empty($aliasId))
				{
					$referrerData['Referrer']['alias_id'] = $aliasId;
				}
				if ($this->Referrer->save($referrerData))
				{
					$referrerData['Referrer']['id'] = $this->Referrer->getLastInsertId();
				}
			}
		}

/*
//СОХРАНЕНИЕ В ЛОГ (ДЛЯ ТЕСТИРОВАНИЯ)
    	try {
    		$filename = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/roadmap.txt';
	    	$file = fopen($filename, 'a+');
	    	if ($file)
	    	{
	    		fwrite($file, "{$referrer}\t{$location}\t{$referrerIn}\t{$locationIn}\n");
				fclose($file);
	    	}
    	}
    	catch (Exception $e)
    	{

    	}
//*/
		if (!empty($location)) // НА СЛУЧАЙ НЕКОРРЕКТНОГО ВЫЗОВА
		{
			if (empty($locationData))
			{
		    	//ВЫБОРКА АЛИАСОВ ИЗ КЛАССИФИКАТОРА
				$locationId = $this->getLocationId($location);

				if ($locationId < 0) //ОТРИЦАТЕЛЬНОЕ ЗНАЧЕНИЕ ОЗНАЧАЕТ ИСКЛЮЧЕНИЕ (ПЕРЕХОД УЧИТЫВАТЬ НЕ НАДО)
				{
					return;
				}

		    	//СОХРАНЕНИЕ НОВОГО АДРЕСА
				$locationData['Referrer']['url'] = $location;
				if (!empty($locationId))
				{
					$locationData['Referrer']['alias_id'] = $locationId;
				}
				if ($this->Referrer->save($locationData))
				{
					$locationData['Referrer']['id'] = $this->Referrer->getLastInsertId();
				}
			}

			$data = array();
			//СОХРАНЕНИЕ НОВОЙ ЦЕПОЧКИ
			$data['Chain']['url_id']		= $locationData['Referrer']['id'];
			$data['Chain']['referrer_id']	= $referrerData['Referrer']['id'];
			if ($data['Chain']['url_id'] <> $data['Chain']['referrer_id'])//ЕСЛИ ЭТО НЕ ОБНОВЛЕНИЕ СТРАНИЦЫ
			{
			$data['Chain']['ip']			= ip2long($_SERVER['REMOTE_ADDR']);
			$data['Chain']['time']			= time();
			$data['Chain']['user_id']		= $this->authUser['userid'];
			if (isset($_COOKIE[session_name()]))
				$data['Chain']['session_id']	= $_COOKIE[session_name()];
			$data['Chain']['step']			= 0;
			$data['Chain']['parent_id']		= 0;
			$data['Chain']['enter_id']		= $data['Chain']['url_id'];
			$data['Chain']['external']		= $external;
/**
 * определение максимального ид для данного ip с учетом сессии
 *
 */
			if (!empty($data['Chain']['referrer_id']))//ЕСЛИ РЕФЕРАЛУ ПРОСТАВЛЕН АЛИАС
			{
				//ПЫТАЕМСЯ ОПРЕДЕЛИТЬ СВЯЗЬ С ПРЕДЫДУЩИМ ЗВЕНОМ ЦЕПОЧКИ
				$conditions = array(
					'Chain.url_id'	=> $data['Chain']['referrer_id'],
				);
				$conditions['and'] = array('or' => array(
					'Chain.ip'		=> $data['Chain']['ip']
				));
				if (isset($data['Chain']['session_id']))
				{
					$conditions['and']['or']['Chain.session_id']	= $data['Chain']['session_id'];
					$conditions['and']['or']['Chain.user_id']		= $data['Chain']['user_id'];
				}
				$lastChain = $this->Chain->find($conditions, null, 'id desc');
				if (!empty($lastChain))
				{
					$data['Chain']['parent_id']		= $lastChain['Chain']['id'];
					$data['Chain']['external']		= $lastChain['Chain']['external'];
					$data['Chain']['enter_id']		= $lastChain['Chain']['enter_id'];
					$data['Chain']['step']			= $lastChain['Chain']['step'] + 1;
					if (empty($data['Chain']['enter_id']))
					{
						$data['Chain']['enter_id'] = $lastChain['Chain']['url_id'];
					}
				}
			}

			$this->Chain->save($data);
			}
		}
    }
}
