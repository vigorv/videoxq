<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mobile_controller
 *
 * @author snowing
 */

class MainController extends AppController {
    var $name ='Main';

    //var $layout ='default';
   var $viewPath = 'main';

    var $helpers = array('Html', 'Form', 'Rss', 'Text', 'PageNavigator','App');
   var $components = array('Captcha', 'Cookie', 'RequestHandler'/*,'DebugKit.toolbar'*/);
   var $uses = array(
       'Direction','News',

       'Film', 'Basket', 'FilmComment', 'SearchLog', 'Feedback', 'Thread', 'Vbpost', 'Vbgroup',
    'Forum', 'Userban', 'Transtat', 'Genre', 'Bookmark', 'CybChat', 'Smile', 'Migration',
    //'DlePost',
    'SimilarFilm','User', 'Zone', 'Server', 'Page',
    'OzonProduct'
    );


function beforeFilter()
    {
       $geoInfo = array();
        $geoInfo = $this->Session->read('geoInfo');
       	$ip = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
       	if (!empty($geoInfo) && $geoInfo['ip'] <> $ip)
       	{
       		$geoInfo = array();//ЕСЛИ ИЗМЕНИЛСЯ АДРЕС ГЕО ДАННЫЕ ОПРЕДЕЛЯЕМ ПО НОВОЙ
       	}
//*
//КОНФИГУРИРУЕМ СПИСОК СЕРВЕРОВ
        $servers = Cache::read('servers', 'block');
        if (empty($servers))
        {
        	$servers = $this->Server->findAll(array('Server.is_active' => 1), null, 'Server.priority DESC');
        	Cache::write('servers', $servers, 'block');
        }
        $configServers = array();
		foreach ($servers as $server)
		{
			$configServers[] = array(
				'server'	=> 'http://' . $server['Server']['addr'] . '/',
				'share'		=> 'http://' . $server['Server']['addr'] . '/',
				'letter'	=> $server['Server']['letter'],
				'zone'		=> $server['Server']['zone'],
			);
		}
        Configure::write('Catalog.downloadServers', $configServers);//Эмулируем старый способ

//КОНФИГУРИРУЕМ СПИСОК ЗОН
		$zones = Cache::read('zones', 'block');
        if (empty($zones))
        {
        	$zones = $this->Zone->findAll(null, null, 'Zone.priority DESC');
        	Cache::write('zones', $zones, 'block');
        }
        $configZones = array();
		foreach ($zones as $zone)
		{
			$configZones[$zone['Zone']['zone']]['zone'] = trim($zone['Zone']['zone']);
			$configZones[$zone['Zone']['zone']]['ip'][] = trim($zone['Zone']['addr']) . '/' . intval($zone['Zone']['mask']);
		}
        Configure::write('Catalog.allowedIPs', $configZones);//Эмулируем старый способ
//*/
//*
        if (empty($geoInfo))
        {
        	//$geoInfo = $this->Geoip->find(array('Geoip.ip1 <=' . $ip, 'Geoip.ip2 >=' . $ip), array('Geoip.city_id', 'Geoip.region_id'));
        	$geoInfo = $this->Geoip->find('all', array(
        				'conditions' => array('Geoip.ip1 <=' . $ip, 'Geoip.ip2 >=' . $ip),
//        				'fields' => array('Geoip.city_id', 'Geoip.region_id', 'MIN(Geoip.ip2 - Geoip.ip1) as R'),
        				'fields' => array('Geoip.city_id', 'Geoip.region_id', 'MIN(Geoip.ip_diff) as R'),
        				'order' => 'R ASC',
        				'recursive' => 0,
        				'group' => 'Geoip.id',
        				)
        			);
        	if (!empty($geoInfo))
        	{
        		$geoInfo = $geoInfo[0];
    	    	$cityInfo = $this->Geocity->read(array('name'), $geoInfo['Geoip']['city_id']);
	        	$regionInfo = $this->Georegion->read(array('name'), $geoInfo['Geoip']['region_id']);
        		$geoInfo['city'] = $cityInfo['Geocity']['name'];
	        	$geoInfo['region'] = $regionInfo['Georegion']['name'];
        	}
        	else
        		$geoInfo['Geoip']['region_id'] = 0; //ЗНАЧИТ НЕ ОПРЕДЕЛЕНО
        	$geoInfo['ip'] = $ip;

			$this->Session->write('geoInfo', $geoInfo);
        }
/*
//ДЛЯ ОТЛАДКИ
$geoInfo['Geoip']['region_id'] = 1;
$geoInfo['Geoip']['city_id'] = 1;
$geoInfo['region'] = 'region';
$geoInfo['city'] = 'city';
$this->Session->write('geoInfo', $geoInfo);
*/
//*/
		$lang = $this->Session->read("language");
		if (empty($lang))
		{
			if (empty($geoInfo['Geoip']['region_id']))
			{
				$regionLang = _ENG_;
	//$regionLang = _RUS_;
			}
			else
			{
				$regionLang = _RUS_;
			}
	//$lang = 0;
			$lang = $regionLang;
			$this->Session->write("language", $lang);
		}

        Configure::write('Config.language', $lang);
		uses('L10n');
        $this->L10n = new L10n();
        $this->L10n->get($lang);

		Configure::write('App.siteName', __("Patent Media", true));
		Configure::write('App.mailFrom', __("Patent Media", true) . ' ' . Configure::read('App.mailFrom'));

		Configure::write('descPerMonth', __("for a month", true));
		Configure::write('descPerWeek', __("for a week", true));
		Configure::write('descPerDay', __("for a day", true));
/*
$config['']	= 'на месяц'; //плата за VIP доступ на месяц
$config['']	= 'на неделю'; //плата за VIP доступ на неделю
$config['descPerDay']	= 'на день'; //плата за VIP доступ на день
*/

		if(isset($this->params['pass'][0])
           && $this->params['pass'][0] == 'attachments')
        {
            return true;
        }

		$this->Cookie->name = Configure::read('App.cookieName');
        $this->Cookie->path = Configure::read('App.cookiePath');
        $this->Cookie->domain = Configure::read('App.cookieDomain');

        $redirect = '/';
        $referer = $this->referer();

        //if ($referer && $referer != '/users/login' && $referer != '/users/register' && $referer != '/')
//            $redirect = $referer;

       	//$this->Auth2->loginRedirect = $redirect;
       	//$this->Auth2->logoutRedirect = $redirect;
        //$this->Auth2->loginAction = '/users/login';
        //$this->Auth2->autoRedirect = false;
//        $this->Auth2->authorize = 'controller';
//        $this->Auth2->userScope = array('User.usergroupid != 3 AND User.usergroupid != 4');

//        $this->_checkLoginCookie();
        //this improves performance
     //   $user = $this->Auth2->user();
        //$this->authUser = $user['User'];
		//if (!empty($this->authUser['userid']))
		//{
//ПРОВЕРКА СОГЛАСЕН ЛИ С ПОЛЬЗОВАТЕЛЬСКИМ СОГЛАШЕНИЕМ
			//$isAgree = $this->Useragreement->read(null, $this->authUser['userid']);
			//if (!empty($isAgree))
			//{/
				//$isAgree = $isAgree["Useragreement"]['agree'];
			//}
			//else
		//	{
//				$isAgree = 0;
	//		/}
			//$this->authUser['agree'] = $isAgree;
		//}
        //$this->set('geoInfo', $geoInfo);

//ПОДГОТОВКА РОКЕТ БЛОКА
        if ($this->authUser['userid'])
        {
	   		$comeBack = $this->Session->read('comeBack');
	   		if (empty($comeBack))//ФИКСИРУЕМ ПОСЛЕДНИЙ ВИЗИТ
	   		{
	   			$this->Session->del('rocketInfo');//ПОСЛЕ АВТОРИЗАЦИИ СБРАСЫВАЕМ СОСТОЯНИЕ РОКЕТ-БЛОКА
		   		$comeBack = $this->Session->write('comeBack', 1);
		   		$this->User->save(array('User' => array('userid' => $this->authUser['userid'], 'lastvisit' => time())));
	   		}
        }

   		$rocketInfo = $this->Session->read('rocketInfo');
   		if ((empty($rocketInfo)) && (!empty($this->authUser['userid']))) //ДЛЯ АВТОРИЗОВАННЫХ ЧИТАЕМ ИЗ КЭША
   		{
			$rocketInfo = Cache::read('Catalog.rocket_' . $this->authUser['userid'], 'rocket');
			if (empty($rocketInfo))
			{
				$rocketInfo['flipOn'] = 0;
		   		$this->Session->write('rocketInfo', $rocketInfo);
			}
   		}
		if (empty($rocketInfo['rocketPage']))
		{
			$rocketInfo['rocketPage'] = 'favorites';
		}

//ПОЛУЧАЕМ ДАННЫЕ ПО ПОСЛЕДНЕМУ POPUP-АНОНСУ
		$rocketAnnons = Cache::read('Catalog.annons', 'rocket');
		if (empty($rocketAnnons))
		{
			$pageInfo = $this->Page->find(array('Page.layout' => 'rocket'), null, 'Page.modified DESC', 0);
			if ($pageInfo['Page']['id'])
			{
				$rocketAnnons['date'] = date('Y-m-d H:i:s');
				$rocketAnnons['page'] = $pageInfo;
				Cache::write('Catalog.annons', $rocketAnnons, 'rocket');
			}
		}
		$this->rocketAnnons = $rocketAnnons;
		$this->set('rocketAnnons', $rocketAnnons);

		if (empty($rocketInfo['lastAnnonsDate']))
		{
			$rocketInfo['lastAnnonsDate'] = '0000-00-00 00:00:00';
		}

		if (($this->authUser['userid']) && ($rocketAnnons['date'] > $rocketInfo['lastAnnonsDate']))
		{
//echo $rocketAnnons['date'] . ' > ' . $rocketInfo['lastAnnonsDate'];
//exit;
			//*
			$rocketInfo['flipOn'] = 1;
			$rocketInfo['lastAnnonsDate'] = $rocketAnnons['date'];
			$rocketInfo['rocketPage'] = 'annons';
	   		$this->Session->write('rocketInfo', $rocketInfo);
	   		//*/
		}

		$this->rocketInfo = $rocketInfo;
		$this->set('rocketInfo', $rocketInfo);
		if ($this->action == 'rocket') return;
//КОНЕЦ ПОДГОТОВКИ РОКЕТ БЛОКА

		$blockStatuses = $this->Session->read('blockStatuses');
		if (empty($blockStatuses))
		{
			$blockStatuses = serialize(array('slidersort' => 1, 'slidergenres' => 1));
			$this->Session->write('blockStatuses', $blockStatuses);
		}
		$this->set('blockStatuses', unserialize($blockStatuses));

        if (isset($this->params[Configure::read('Routing.admin')]))
        {
            $this->layout = 'admin';
            ini_set('memory_limit', '1G');
            set_time_limit(5000000000);
            Configure::write('debug', 2);
        }

        $this->_constructBlocks();
        if (!empty($this->passedArgs))
        	$this->set('passedParams', $this->passedArgs);//ДЛЯ ИСПОЛЬЗОВАНИЯ В ОТОБРАЖЕНИЯХ (НАПРИМЕР БЛОК РАСШИРЕННОГО ПОИСКА)
    	//$isWS = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
//    	$isWS = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), (empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_X_FORWARDED_FOR"]), 1);
    	$isWS = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER["REMOTE_ADDR"], 1);
//$isWS = 'OPERA-MINI';
    	$isOpera = false;
    	if ($isWS == 'OPERA-MINI')
    	{
    		$isWS = false;
    		$isOpera = true;
    	}
    	$this->isWS = $isWS;

//echo $_SERVER['REMOTE_ADDR'] . ' - isWS = ' . $isWS;

       	$this->set('isOpera', $isOpera);//ОПРЕДЕЛИЛИ ТУРБО
       	$this->set('isWS', $isWS);//ОПРЕДЕЛИЛИ ВЕБСТРИМ или нет
        $this->set('here', $this->here);
          
   }


        private  function film_list()  {
            Configure::write('debug', 0);
            $films = array();
            $pagination = array('Film' => array('contain' =>
                                       array('FilmType',
                                             'Genre',
                                     		 'FilmVariant' => array('VideoType'),
                                             'FilmPicture' => array('conditions' => array('type' => 'smallposter')),
                                             'Country',
                                              'Person' => array('conditions' => array('FilmsPerson.profession_id' => array(1, 3, 4))),
                                             'MediaRating'),
                                        'order' => 'Film.modified DESC',
                                        'conditions' => array('Film.active' => 1),
                                        'group' => 'Film.id',
                                        'limit' => 30));
		$films = $this->Film->find('all', $pagination["Film"]);
                
		if(isset($this->params['requested'])) {
                    return $films;
                }
        }


private function GetView($view,$path,$paramname=false,$params=false){
    $this->autoRender = false;
    //$view = new View($this, false);
    if(is_array($params)){
            $view->set($paramname,$params);
    }
    $view->layout = false;

    $view->viewPath = 'main';
    return $view->render($path);
}


private function GetFilms(){
    $view = new View($this,false);
    
    if (empty($view->passedArgs['direction']))
            $view->passedArgs['direction'] = 'desc';

$lang = Configure::read('Config.language');
$langFix = '';
if ($lang == _ENG_) $langFix = '_' . _ENG_;
$view->set('lang', $lang);
$view->set('langFix', $langFix);


    $this->pageTitle = __('Video catalog', true);
    $this->Film->recursive = 1;
     if ($this->isWS)
       	$order=array('Film.modified' => 'desc');
     else
      	$order=array('Film.year' => 'desc');
    
    $conditions = array();
    $conditions['Film.active'] = 1;
    $postFix = '';
     if (!$this->isWS && empty($this->params['named']['search']))
	{
            $conditions['Film.is_license'] = 1;//ВНЕШНИМ ПОКАЗЫВАЕМ ТОЛЬКО ЛИЦЕНЗИЮ
            $postFix = 'Licensed';
	}
       $pagination = array('Film' => array('contain' =>
                                       array('FilmType',
                                             'Genre',
                                     		 'FilmVariant' => array('VideoType'),
                                             'FilmPicture' => array('conditions' => array('type' => 'smallposter')),
                                             'Country',
                                              'Person' => array('conditions' => array('FilmsPerson.profession_id' => array(1, 3, 4))),
                                             'MediaRating'),
                                        'order' => $order,
                                        'conditions' => $conditions,
                                        'group' => 'Film.id',
                                        'limit' => 3));
    $films = $this->Film->find('all', $pagination["Film"]);

    return $this->GetView($view,'block/films','films',$films);
}

function GetCatNews($pos){
    	$lang = $this->Session->read("language");
        $view= new View($this,false);
    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
            reset($dirs);
        while ($pos>0) {
                if(count($dirs)<=$pos) return null;
                next($dirs);
                $pos--;
        }
        $dir= current($dirs);
        $dir_id = $dir['id'];
       	$conditions = array('News.hidden' => 0);
    	if (!empty($dir_id))
    		$conditions['News.direction_id'] = $dir_id;
    	$lst = $this->News->findAll($conditions,null, 'News.created DESC','3');
    	return $this->GetView($view,'block/catnews','news',$lst);
}


function index() {
    $this->set('block_films',$this->GetFilms());
    $this->set('block_left_news',$this->GetCatNews(0));
    $this->set('block_right_news',$this->GetCatNews(1));
   //$this->set('block_films','test');
    // $this->render(false);
    $this->autoRender = true;
    }



}