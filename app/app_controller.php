<?php
DEFINE("_RUS_", "ru");
DEFINE("_ENG_", "en");
define('DEFAULT_LANGUAGE', _RUS_);

class AppController extends Controller
{
    var $components = array('Auth2', 'Acl', 'Cookie', 'Vb', 'BlockBanner', 'Session');
    var $helpers = array('Javascript', 'Html', 'Form'/*, 'Validation'*/, 'App', 'Ajax', 'PageNavigator');
//    var $uses = array('User', 'Bookmark', 'Film');
    var $uses = array('User', 'Bookmark', 'Film', 'Pay', 'Geoip', 'Geocity', 'Georegion', 'Useragreement');
    var $blocksData = array();
    var $blockContent;
    var $authUser;
	var $adminAtribs=array();
    var $_adminAtribs=array(
						'ManageOpions'=>array('New %'=>'add','List %'=> 'index'),
						'editRowsSettings'=>array()
	);

    /**
     * вывод баннеров
     *
     * @var BlockBannerComponent
     */
	public $BlockBanner;

	public $onlineUsers = array();

	/**
     * Выставляем основные настройки сайта
     *
     * @return void
     */
    function beforeFilter()
    {
		Configure::write('App.siteName', __("Patent Media", true));
		Configure::write('App.mailFrom', __("Patent Media", true) . ' ' . Configure::read('App.mailFrom'));
        $geoInfo = array();
        $geoInfo = $this->Session->read('geoInfo');
//*
        if (empty($geoInfo))
        {
        	$ip = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        	$geoInfo = $this->Geoip->find(array('Geoip.ip1 <=' . $ip, 'Geoip.ip2 >=' . $ip), array('Geoip.city_id', 'Geoip.region_id'));
        	$geoInfo = $this->Geoip->find('all', array(
        				'conditions' => array('Geoip.ip1 <=' . $ip, 'Geoip.ip2 >=' . $ip),
        				'fields' => array('Geoip.city_id', 'Geoip.region_id', 'MIN(Geoip.ip2 - Geoip.ip1) as R'),
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

			$this->Session->write('geoInfo', $geoInfo);
        }
//*/
		if (empty($geoInfo['Geoip']['region_id']))
		{
			$regionLang = _ENG_;
			$regionLang = _RUS_;
		}
		else
		{
			$regionLang = _RUS_;
		}
		$lang = $this->Session->read("language");
		if (empty($lang))
		{
			$lang = $regionLang;
			$this->Session->write("language", $lang);
		}

        Configure::write('Config.language', $lang);
		uses('L10n');
        $this->L10n = new L10n();
        $this->L10n->get($lang);

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

        if ($referer && $referer != '/users/login' && $referer != '/users/register' && $referer != '/')
            $redirect = $referer;

       	$this->Auth2->loginRedirect = $redirect;
       	$this->Auth2->logoutRedirect = $redirect;
        $this->Auth2->loginAction = '/users/login';
        $this->Auth2->autoRedirect = false;
        $this->Auth2->authorize = 'controller';
        $this->Auth2->userScope = array('User.usergroupid != 3 AND User.usergroupid != 4');

        $this->_checkLoginCookie();
        //this improves performance
        $user = $this->Auth2->user();
        $this->authUser = $user['User'];
		if (!empty($this->authUser['userid']))
		{
//ПРОВЕРКА СОГЛАСЕН ЛИ С ПОЛЬЗОВАТЕЛЬСКИМ СОГЛАШЕНИЕМ
			$isAgree = $this->Useragreement->read(null, $this->authUser['userid']);
			if (!empty($isAgree))
			{
				$isAgree = $isAgree["Useragreement"]['agree'];
			}
			else
			{
				$isAgree = 0;
			}
			$this->authUser['agree'] = $isAgree;
		}
        $this->set('geoInfo', $geoInfo);

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

    	$isWS = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
       	$this->set('isWS', $isWS);//ОПРЕДЕЛИЛИ ВЕБСТРИМ или нет
        $this->set('here', $this->here);
    }

    public function getOnlineUsersCnt()
    {
    	$users = 0;
    	$guests = 0;
    	$names = array();
    	foreach ($this->onlineUsers as $u)
    	{
    		if ($u['id'] > 0)
    		{
    			$users++;
    			$names[] = $u['name'];
    		}
    		else
    			$guests++;
    	}
    	$cnt = array('users' => $users, 'guests' => $guests, 'names' => $names);
//pr($cnt);
    	return $cnt;
    }

    /**
     * Выставляем дополнительные данные для View
     *
     */
    function beforeRender()
    {
        if ($this->layout == 'admin' || $this->layout == 'ajax')
            return;

        if (isset($this->authUserGroups) && in_array(Configure::read('VIPgroupId'), $this->authUserGroups))
        {
        	$this->BlockBanner->lock(true);
			$perMonth	= Configure::read('costPerMonth');
			$this->set('perMonth', $perMonth);
			$perWeek	= Configure::read('costPerWeek');
			$this->set('perWeek', $perWeek);
			$perDay		= Configure::read('costPerDay');
			$this->set('perDay', $perDay);
			$payDesc = array(
				$perDay		=> Configure::read('descPerDay'),
				$perWeek	=> Configure::read('descPerWeek'),
				$perMonth	=> Configure::read('descPerMonth'),
			);
			$this->set('payDesc', $payDesc);
	        $payInfo = $this->User->Pay->find(array('Pay.user_id' => $this->authUser['userid'], 'Pay.status' => _PAY_DONE_, 'Pay.findate > ' => time()), null, 'Pay.findate DESC');
	        if (empty($payInfo['Pay']['id']))//ЕСЛИ КОНЧИЛАСЬ ОПЛАТА
	        {
	        	//админов из випов не исключаем
		        if (isset($this->authUserGroups) && in_array(Configure::read('VIPgroupId'), $this->authUserGroups) && (!in_array(1, $this->authUserGroups)) && (count($this->authUserGroups) > 1))
		        {
/*
if ($this->authUser['username'] == 'vanoveb')
{
	echo '<h1>vanoveb</h1>';
exit;
}
//*/
		        	//если принадлежит к другим группам, кроме ВИП, исключаем из ВИПов и не админ
			    	//$db = &ConnectionManager::getDataSource($this->Pay->useDbConfig);
/*
			    	$sql = '
						delete from groups_users where user_id = ' . $this->authUser['userid']. ' and group_id = ' . Configure::read('VIPgroupId') . ';
    				';
    				$this->Pay->query($sql);
*/
    				$key = array_search(Configure::read('VIPgroupId'), $this->authUserGroups);
    				if (!empty($key))
    				{
    					unset($this->authUserGroups[$key]);
    				}
    				//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
    				$uInfo = array('Group' => array('Group' => $this->authUserGroups), 'User' => array('userid' =>$this->authUser['userid'], 'lastactivity' => time()));
    				$this->User->save($uInfo);
		        }
	        }
	        $this->set('payInfo', $payInfo);
        }

        //$onlineUserSession = session_id();
       	$ips = array();
       	$ips[] = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
       	$ips[] = (!empty($_SERVER['X_FORWARDED_FOR'])) ? $_SERVER['X_FORWARDED_FOR'] : '';
       	$ips[] = (!empty($_SERVER['X_REAL_IP'])) ? $_SERVER['X_REAL_IP'] : '';
       	$onlineUserSession = implode('_', $ips);

        if (!empty($this->authUser['userid']))
        {
        	$onlineUserId = $this->authUser['userid'];
        	$onlineUserName = $this->authUser['username'];
        }
        else
        {
        	$onlineUserId = 0;
        	$onlineUserName = 'guest';
        }

        $onlineUsers = array();
        $curTime = time();
        for ($i = 4; $i > 0; $i--) //аккумулируем кэш за последние 5 минут
        {
        	$min = intval(date('i', $curTime - 60 * $i));
	        $minUsers = Cache::read('Catalog.onlineCnt' . $min, 'counters');
	        if ($minUsers)
	        {
	        	$onlineUsers = array_merge($onlineUsers, $minUsers);
	        }
        }
        $curMin = intval(date('i', $curTime));
        $minUsers = Cache::read('Catalog.onlineCnt' . $curMin, 'counters');
        $minUsers[$onlineUserSession]['name'] = $onlineUserName;// . '_' . $curMin . '_' . $onlineUserSession;
        $minUsers[$onlineUserSession]['id'] = $onlineUserId;

       	$onlineUsers = array_merge($onlineUsers, $minUsers);
        Cache::write('Catalog.onlineCnt' . $curMin, $minUsers, 'counters');

        $this->onlineUsers = $onlineUsers;
//if (@$this->authUser['username'] == 'vanoveb')
        $this->set('onlineUsers', $this->getOnlineUsersCnt());

//pr($this->onlineUsers);

        $this->set('authUser', $this->authUser);

        if ($this->authUser['userid'])
        {
            $this->Bookmark->recursive = -1;
            $this->set('bookmarks', $this->Bookmark->findAllByUserId($this->authUser['userid']));
            //TODO:get model and get data....Это не метод..это лажа. но что же делать прийдется
            $result=$this->User->query('select count(*) as count from pm where messageread=0 and userid='.$this->authUser['userid']);
            $this->set('pms',$result[0][0]['count']);
        }

        $this->_initBlocks();
        $this->set('blockContent', $this->blockContent);
        $this->set('BlockBanner', $this->BlockBanner);

        $this->Film->contain();
        $data = Cache::read('Catalog.filmStats', 'default');
$data=array();
        if (!$data)
        {
            $data['count'] = $this->Film->find('count', array('conditions' => 'Film.active=1'));
/*
//ПОДСЧЕТ РАЗМЕРА ФАЙЛОВ
            $tmp = $this->Film->FilmVariant->FilmFile->find('first',
                                                                 array('fields' => array('SUM(size) AS size')));
            $data['size'] = $tmp[0]['size'];
//*/
//*
//ПОДСЧЕТ ПРОДОЛЖИТЕЛЬНОСТИ ПО ВРЕМЕНИ
			$sql ='SELECT SUM( TIME_TO_SEC( `FilmVariant`.`duration` ) ) AS size
FROM `film_variants` AS `FilmVariant`
LEFT JOIN `films` AS `Film` ON ( `FilmVariant`.`film_id` = `Film`.`id` )
WHERE 1=1
LIMIT 1';
            $tmp = $this->Film->FilmVariant->query($sql);
            $data['size'] = $tmp[0][0]['size'];
//*/

            Cache::write('Catalog.filmStats', $data, 'default');
        }
        $this->set('filmStats', $data);
    }

    /**
     * Check user auth
     *
     * @param string $action действие, для которого надо проверить права,
     *  если не указано - проверяем текущий action
     * @return bool
     */
    function isAuthorized($action = null)
    {
        if (!$action)
            $action = $this->Auth2->action();

        if ($this->Auth2->allowedActions == array('*') || in_array($this->action, $this->Auth2->allowedActions))
        {
            return true;
        }

        Cache::config('default');
        if (!$this->authUser['userid'])
        {
            $permission = Cache::read('Permission_' . Configure::read('App.guestGroup'));
            if (isset($permission[$action]))
                return $permission[$action];
            $valid = $this->Acl->check(array('Group' => array('id' => Configure::read('App.guestGroup'))),
                                       $action);
            $permission[$action] = $valid;
            Cache::write('Permission_' . Configure::read('App.guestGroup'), $permission);
            if (!$valid && $this->Auth2->action() == $action)
                $this->redirect($this->Auth2->loginAction);
            else
                return $valid;
        }

        $recursive = $this->User->recursive;
        $this->User->recursive = 1;
        $user = $this->User->read(null, $this->authUser['userid']);
        $this->User->recursive = $recursive;
        $groups = Set::extract('/Group/id', $user);
        if (!empty($groups))
        {
	        $this->authUserGroups = $groups;
	        $this->set('authUserGroups', $groups); //ПЕРЕДАДИМ СПИСОК ГРУПП В ОТОБРАЖЕНИЕ
	        foreach ($groups as $group)
	        {
	            $permission = Cache::read('Permission_' . $group);
	            if (isset($permission[$action]))
	            {
	                $valid = $permission[$action];
	                if ($valid)
	                    break;
	                continue;
	            }
	            $valid = $this->Acl->check(array('Group' => array('id' => $group)), $action);
	            $permission[$action] = $valid;
	            Cache::write('Permission_' . $group, $permission);
	            if ($valid)
	                break;
	        }
    	}

		if (empty($valid))
            $valid = $this->Acl->check(array('Group' => array('id' => Configure::read('App.guestGroup'))), $action);
        else
			return $valid;
    }

    /**
     * Читаем блоки из базы и инициализируем компоненты
     *
     */
    function _constructBlocks()
    {
        $this->blocksData = Cache::read('Block.blockList_' . $this->params['controller'] . '_' . $this->params['action'], 'block');
        if (!$this->blocksData)
        {
            $model = ClassRegistry::init('Block');

            $this->blocksData = $model->getActiveBlocks($this->params['controller'], $this->params['action']);
            Cache::write('Block.blockList_' . $this->params['controller'] . '_' . $this->params['action'], $this->blocksData, 'block');
        }

        $componentsBack = $this->components;
        $this->components = array();
        foreach ($this->blocksData as $blockElement)
        {
            if (strpos($blockElement['Block']['type'], 'component')  !== false)
            {
                //pr($this->Component);
                //ClassRegistry::init($settings, 'component');
                $this->components[] = $blockElement['Block']['controller'];
            }
        }

        $this->Component->init($this);
        $this->components = am($componentsBack, $this->components);
    }

    /**
     * Читаем данные о блоках и выставляем данные во View
     *
     */
    function _initBlocks()
    {

        $blockContent = array();

        foreach ($this->blocksData as $blockElement)
        {
            if (!isset($blockContent[$blockElement['Block']['position']]))
                $blockContent[$blockElement['Block']['position']] = '';

            $args = eval($blockElement['Block']['arguments']);

            $cache = false;
            if (is_array($args) && isset($args['cache']))
                $cache = $args['cache'];

            switch ($blockElement['Block']['type'])
            {
                case 'text':
                case 'php':
                case 'element':
                    $blockContent[$blockElement['Block']['position']][] = array ('type' => $blockElement['Block']['type'],
                                                                                 'element' => $blockElement['Block']['element'],
                                                                                 'title' => $blockElement['Block']['title'],
                                                                                 'content' => $blockElement['Block']['content'],
                                                                                 'cache' => $cache);
                    break;
                case 'component+element':
                    $content = $this->{$blockElement['Block']['controller']}->{$blockElement['Block']['method']}($args);
                    $this->set($blockElement['Block']['element'], $content);
                    $blockContent[$blockElement['Block']['position']][] = array ('type' => $blockElement['Block']['type'],
                                                                                 'element' => $blockElement['Block']['element'],
                                                                                 'title' => $blockElement['Block']['title'],
                                                                                 'cache' => $cache);
                    break;
                default:
                    $content = $this->{$blockElement['Block']['controller']}->{$blockElement['Block']['method']}($args);
                    $blockContent[$blockElement['Block']['position']][] = array ('type' => $blockElement['Block']['type'],
                                                                                 'content' => $content,
                                                                                 'title' => $blockElement['Block']['title']);
                    break;
            }
        }

        $this->blockContent = $blockContent;
    }

    /**
     * Проверяем наличие куки "запомнить меня"
     *
     * @param bool $redirect редиректить на логин или нет
     */
    function _checkLoginCookie($redirect = false)
    {
        $user = $this->Auth2->user();
        if ($user['User']['userid'] && !empty($_COOKIE['portalxquserid']))
            return;

        $cookie = $this->Cookie->read('Auth.User');
        if (!is_null($cookie))
        {
            $auth = $this->Auth2->login($cookie);
            if ($auth)
            {
                //  Clear auth message, just in case we use it.
                if (!$redirect)
                    $this->Session->del('Message.auth');
                $user = $this->Auth2->user();
                $this->Vb->setLoginCookies($user['User']['userid'], $cookie['vbpassword']);
                if ($redirect)
                    $this->redirect($this->Auth2->redirect());
            }
            else
            { // Delete invalid Cookie
                $this->Vb->clearCookies();
                $this->Cookie->del('Auth.User');
            }
        }
    }

    /**
     * Send an email from site
     *
     * @param unknown_type $from
     * @param unknown_type $to
     * @param unknown_type $subj
     * @param unknown_type $body
     * @return unknown
     */
    function _sendEmail($from, $to, $subj, $body)
    {
/*
        $this->Email->to = $to;

        $this->Email->from = $from;

        $this->Email->subject = $subj;



        $this->Email->smtpOptions['host'] = Configure::read('App.mailSmtp');
//        $this->Email->smtpOptions['username'] = Configure::read('App.mailSmtpUser');
//        $this->Email->smtpOptions['password'] = Configure::read('App.mailSmtpPass');

        //if (Configure::read())
            //$this->Email->delivery = 'debug';
        //else
            //$this->Email->delivery = 'smtp';//отправляет через сокеты (на агаве сокеты запрещены)
            $this->Email->delivery = 'mail';

        $this->Email->lineLength = strlen($body);

        // If want to you templates - dig it yourself
        $result = $this->Email->send($body);

        //TODO: rewrite
        if (Configure::read())
        	ob_start();
        	pr($this->Session->read('Message.email'));
        	$mail=ob_get_clean();
            CakeLog::write('debug_mail',$mail);
//*/

        App::import('Vendor', 'mail');
		$mailObj = new simpleMail();
		$mailObj->addhdfield('X-Mailer', 'videoxq-Robot');
		$mailObj->addhdfield('Precedence', 'bulk');
		$mailObj->setTo($to);
		$mailObj->setFrom($from);
		$mailObj->setSubject($subj);
		$body .= "\n\nPS\nПисьмо отправлено почтовым роботом. Пожалуйста, не отвечайте на него.\n\n";
		$mailObj->setTextBody($body);
		$mailObj->send();
		$mailObj = 0;
		$result = true;

        return $result;
    }

//    function ishavenewmessages()
//    {
//    	return true;
//    }

	public function _admin_before_action()
	{
    	if(!isset($this->useViewPath)||$this->useViewPath==false)$this->viewPath = 'admin/default';
    	$this->pageTitle="admin/".$this->name."/".$this->action;
    	//$name=$this->name;
    	$model=$this->uses[0];
    	$UseTable=$this->$model->useTable;
    	$rows=$this->$model->query("DESCRIBE `".$UseTable."`");
		return array($model,$UseTable,$rows);
	}
	function _admin_after_action($rows)
	{
		$this->adminAtribs=set::merge($this->adminAtribs,$this->_adminAtribs);
		//pr($this->adminAtribs);
    	$model=$this->uses[0];
    	$usedModels=set::merge($this->$model->belongsTo,$this->$model->hasOne);
    	$usedModels=set::combine($usedModels,"{s}.foreignKey","{s}.className");

    	$rows=set::combine($rows,"{n}.COLUMNS.Field","{n}.COLUMNS.Type");
        $this->set('usedModels', $usedModels);
        $this->set('model', $model);
        $this->set('rows', $rows);
        $this->set('actions', $this->adminAtribs['ManageOpions']);
        $this->set('editRowsSettings', $this->adminAtribs['editRowsSettings']);
	}

    function admin_index()
    {
    	list($model,$UseTable,$rows)=$this->_admin_before_action();
    	$this->$model->recursive = 0;
        $this->set('DATA', $this->paginate());
        $this->_admin_after_action($rows);
    }
    function admin_add()
    {
    	list($model,$UseTable,$rows)=$this->_admin_before_action();
    	unset($rows[0]);

        if (! empty($this->data))
        {
            $this->$model->create();
            if ($this->$model->save($this->data))
            {
                $this->Session->setFlash(__("The {$model} has been saved", true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The '.$model.' could not be saved. Please, try again.', true));
            }
        }
    	$this->_admin_after_action($rows);
    }
    function admin_edit($id = null)
    {
    	list($model,$UseTable,$rows)=$this->_admin_before_action();
    	if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid '.$model, true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->$model->save($this->data))
            {
                $this->Session->setFlash(__('The '.$model.' has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The '.$model.' could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
	    	$this->$model->recursive = -1;
            $this->data = $this->$model->read(null, $id);
            $this->set($model,$this->data);
        }
    	$this->_admin_after_action($rows);
    }

	function admin_view($id = null)
	{
    	list($model,$UseTable,$rows)=$this->_admin_before_action();
		if (!$id) {
            $this->Session->setFlash(__('Invalid '.$model.'.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('DATA', $this->$model->read(null, $id));

    	$this->_admin_after_action($rows);
	}

	function admin_delete($id = null) {
    	list($model,$UseTable,$rows)=$this->_admin_before_action();
		if (!$id) {
            $this->Session->setFlash(__('Invalid id for '.$model, true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->$model->del($id)) {
            $this->Session->setFlash(__($model.'deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>