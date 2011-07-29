<?php
class UsersController extends AppController
{

    var $name = 'Users';
    var $helpers = array('Html' , 'Form');
    var $components = array('Captcha' , 'Email', 'ControllerList');
    var $uses = array('User', 'Group', 'UserActivation', 'Useragreement', 'Userlottery', 'Pay', 'Usermessage',
    'Vbpost',
//    'DleUser'
    );

	/**
	 * модель пользователей
	 * @var AppModel
	 */
    public $User;

	/**
	 * модель групп
	 * @var AppModel
	 */
    public $Group;

	/**
	 * модель активации пользователей
	 * @var AppModel
	 */
    public $UserActivation;

	/**
	 * модель учета пользовательского соглашения
	 * @var AppModel
	 */
    public $Useragreement;

	/**
	 * модель пользовательские лотереи
	 * @var AppModel
	 */
    public $Userlottery;

	/**
	 * модель платежи
	 * @var AppModel
	 */
    public $Pay;

	/**
	 * модель сообщения
	 * @var AppModel
	 */
    public $Usermessage;

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth2->allowedActions = array('register' , 'restore' , 'captcha' , 'confirm', 'logout');

        //ПЕРЕТАЩИЛ ОПРЕДЕЛЕНИЕ ВАЛИДАЦИИ ИЗ МОДЕЛИ, ИНАЧЕ НЕ ПОДХВАТЫВАЕТ ЛОКАЛЬ (МОДЕЛИ ИНИЦИИРУЮТСЯ, РАНЬШЕ ЧЕМ ПРОИСХОДИТ ВЫБОР ЛОКАЛИ)
        $this->User->validate = array(
          'username' => array(array('rule' => VALID_NOT_EMPTY, 'message' => __('You must specify login', true)),
                              array('rule' => VALID_UNIQUE,    'message' => __('Login exists', true), 'on' => 'create')),
          'password' => array(array('rule' => VALID_NOT_EMPTY, 'message' => __('You must specify password', true)),
                              array('rule' => VALID_HAS_PAIR,  'message' => __('Passwords do not match', true), 'on' => 'create')),
          'email'    => array(array('rule' => VALID_NOT_EMPTY, 'message' => __('You must specify Email', true)),
                              array('rule' => VALID_EMAIL,     'message' => __('Invalid Email', true)),
                              array('rule' => VALID_UNIQUE,    'message' => __('Email exists', true), 'on' => 'create')),
          'captcha'  => array(array('rule' => VALID_HAS_PAIR,  'message' => __('Verification code incorrect', true), 'on' => 'create')),
       );
    }

    /**
     * Основная авторизация происходит в Auth компоненте.
     * Здесь выставляется только куки для логина.
     *
     */
    function login()
    {
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    	if (isset($_POST["vb_login_username"])) //ЗНАЧИТ ЭТО АВТОРИЗАЦИЯ С ФОРУМА
    	{
    		/*
	    		В БД форума VB в таблице template нужно найти шаблон с title = "navbar"
	    		в тексте шаблона изменить action формы на "/users/login"

	    		поля формы переименовать соответственно
	    		data[User][username]
	    		data[User][password]
	    		data[User][remember_me]

	    		добавить в форму скрытое поле
	    			<input type="hidden" name="redirect" value="">
	    		значение этого поля заполнить из обработчика события
	    			onsubmit="this.redirect.value=window.location;"

    		*/

    		//ГОТОВИМ ДАННЫЕ ИЗ ПОСТА ДЛЯ ИСПОЛЬЗОВАНИЯ В ФУНКЦИИ АВТОРИЗАЦИИ

    		$this->data['User'] = array(
    			'username' => $_POST["vb_login_username"],
    			'password' => $_POST["vb_login_password"],
    			'redirect' => $_POST["redirect"],
    		);

    		if (isset($_POST['cookieuser']))
    		{
    			$this->data['User']['remember_me'] = $_POST['cookieuser'];
    		}
        	$user = $this->Auth2->user();
    		$this->authUser = array('userid' => 0, 'usergroupid' => Configure::read('App.guestGroup'));
        	$user['User'] = $this->authUser;
    	}

       	$user = $this->Auth2->user();
/*
if ($this->data["User"]["username"] == 'vanoveb')
{
	echo '<pre>';
	print_r($this->data);
	echo '</pre>';

	echo '<pre>';
	pr($user);
	echo '</pre>';
	exit;
}
//*/

       	$this->authUser = $user['User'];
       		if (isset($this->data['User']['username']))
       		{
	            $userInfo = $this->User->find(array('User.username' => $this->data['User']['username']));
	            if ($userInfo)
	            {
		            if ($userInfo['User']['usergroupid'] == 3)//НЕ ПОДТВЕРЖДЕН
		            {
			            $this->Session->setFlash(__('Login exists Registration not confirmed', true));
	            		if (!empty($this->authUser['userid']))
	            		{
					        if (!empty($this->data['User']["redirect"]))
					        {
					//echo 'REDIRECT to "'. $_POST["redirect"] .'"';
					//exit;
								if (strpos($this->data['User']["redirect"], 'users/login') || strpos($this->data['User']["redirect"], 'users/register'))
									$this->data['User']["redirect"] = '/users/office';
					        }
							else
								$this->data['User']["redirect"] = '/users/office';
	            		}
	            		else
							$this->data['User']["redirect"] = '/users/login';
			        	$this->redirect($this->data['User']["redirect"]);
			            return;
		            }
	            }
       		}

		if ($this->authUser['userid'] && !empty($this->data))
        {
            if (!empty($this->data))
            {
                $this->Session->write('Auth.' . $this->Auth2->userModel . '.vbpassword', $this->Vb->cookiePass($this->data[$this->Auth2->userModel]['password']));
                $this->Vb->setLoginCookies($this->authUser['userid'], $this->data[$this->Auth2->userModel]['password']);
            }
            if (!empty($this->data) && isset($this->data['User']['remember_me']))
            {
                $cookie = array();
                $cookie['username'] = $this->data['User']['username'];
                $cookie['password'] = $this->data['User']['password'];
                $cookie['vbpassword'] = $this->data['User']['password'];

				$cookieDomain = Configure::read('App.cookieDomain');
				//УДАЛЯЕМ КУКИ БЕЗ ДОМЕНА
                Configure::write('App.cookieDomain', '');
   				$this->Cookie->del('Auth.User');

                Configure::write('App.cookieDomain', $cookieDomain);
                $this->Cookie->write('Auth.User',
                                $cookie,
                                true,
                                '+2 weeks');
                unset($this->data['User']['remember_me']);
            }
/*
            	if ($this->data['User']['username'] == 'vanoveb')
            	{
            		echo'<pre>';
            		print_r($_POST);
            		echo'</pre>';
            		exit;
            	}
//*/
            if (!empty($_POST["securitytoken"]))
            {
            	if ($_POST["securitytoken"] != 'guest')
            	{
	            	//$this->redirect('/forum/index.php');
	            	$this->redirect('/users/office');
	            	return;
            	}
            }
            if (!empty($_POST["redirect"]))
            {
//echo 'REDIRECT to "'. $_POST["redirect"] .'"';
//exit;
            	$this->redirect($_POST["redirect"]);
	            return;
            }

            $redirect = $this->Auth2->redirect();
            if (strpos($redirect, 'forum'))
            	$this->redirect($redirect);
            else
            	$this->redirect('/media');
            unset($this->data['User']['password']);
            return;
        }

        if (!empty($this->data['User']["redirect"]))
        {
//echo 'REDIRECT to "'. $_POST["redirect"] .'"';
//exit;
        	$this->redirect($this->data['User']["redirect"]);
            return;
        }

        if (empty($this->data) && !$this->authUser['userid'])
        {
            $this->_checkLoginCookie(true);
        }
        elseif (empty($this->data) && $this->authUser['userid'])
            $this->redirect('/media');

        unset($this->data['User']['password']);
    }

    /**
     * Заявка на удаление аккаунта
     *
	 * @param string $action - тип действия
     */
    public function drop($action = '')
    {
    	if ($this->authUser['userid'])
    	{
			switch ($action)
			{
				case "send": //ДЕЙСТВИЕ ОТПРАВКИ ПИСЬМА АДМИНУ
					Configure::write('debug', 1);
					$geoPlace = '';

					$to = 'support@videoxq.com';
					$this->_sendEmail($this->authUser['email'], $to, 'Заявка на удаление аккаунта', strip_tags($this->data['msg']));

				break;
				default:
					//ВЫВОД ФОРМЫ

			}
    	}
		$this->set('action', $action);
    }

    /**
     * генерировать уникальный код(лот) для участия в лотерее
     *
     * @param mixed $params - параметры, участвующие в генерации кода
     * @return string
     */
    function getLotteryCode($params)
    {
    	return strtoupper(substr(md5(implode('', $params)), 0, 15));
    }

//РОЗЫГРЫШ ПРИЗОВ
	function lottery($id = 0, $action = '')
	{
		$dup = array();
		$winDelay = 3600 * 24 * 5;
//$winDelay = 60;
		if ((!empty($this->authUser['userid'])) && !empty($_POST['lottery_id']))//РЕГИСТРАЦИЯ УЧАСТИЯ В РОЗЫГРЫШЕ
		{
			$ip = $_SERVER['REMOTE_ADDR'] . rand(0, 100);
			$lotteryId = intval($_POST['lottery_id']);
			$dup = $this->Userlottery->find(array('Userlottery.ip' => $ip, 'Userlottery.lottery_id' => $lotteryId));
//$dup = 0;
			if (empty($dup))//ЕСЛИ С ЭТОГО IP НЕ РЕГИСТРИРОВАЛИСЬ
			{
				$action = 'info';
				$code = $this->getLotteryCode(array($this->authUser['userid'], $ip, $lotteryId));
				$bidInfo = array();
				$data = array('Userlottery' =>
					array(
						'id'			=> NULL,
						'user_id'		=> $this->authUser['userid'],
						'lottery_id'	=> $lotteryId,
						'ip'			=> $ip,
						'unique_code'	=> $code,
						'registered'	=> date('Y-m-d H:i:s'),
						'winner'		=> 0,
						'bid_user_id'	=> 0,
						'inv_user_id'	=> 0,
						'fraze'			=> '',
						'lottery_code'	=> '',
				));
				if (!empty($_POST['bid_username']))
				{
					$bidUser = $this->User->find(array('User.email' => $_POST['bid_username']));
					if (!empty($bidUser))
					{
						$bidData = $data;
						$bidData['Userlottery']['inv_user_id'] = $this->authUser['userid'];
						$bidData['Userlottery']['user_id'] = $bidUser['User']['userid'];
						$bidData['Userlottery']['unique_code'] = $this->getLotteryCode(array($bidUser['User']['userid'], $ip, $lotteryId));
						//ДОБАВЛЯЕМ ЕЩЕ ШАНС ПРИГЛАСИВШЕМУ
						$this->Userlottery->create();
						$this->Userlottery->save($bidData);

						$data['Userlottery']['bid_user_id'] = $bidUser['User']['userid'];
					}
				}
				$this->Userlottery->create();
				if ($this->Userlottery->save($data))//РЕГИСТРИРУЕМ УЧАСТИЕ
				{
					$data['Userlottery']['id'] = $this->Userlottery->getInsertID();

//РАЗДАЧА СЛОНОВ
/*
РАЗДАЧУ СЛОНОВ ОТКЛАДЫВАЕМ НА НЕДЕЛЮ ПОСЛЕ РЕГИСТРАЦИИ В РОЗЫГРЫШЕ
					if ($data['Userlottery']['id'] % 13 == 0)
					{
						//КАЖДЫЙ 13й ВЫИГРЫВАЕТ СУВЕНИР
						$winner = 1; - ВЫИГРАД СУВЕНИР
						//winner = 2 - ВЫИГРАЛ СТАТУС ВИП
						//winner = 3 - ВЫИГРАЛ ЕЖЕНЕДЕЛЬНЫЙ ПРИЗ ЗА ПРИГЛАШЕННЫХ
						//winner = 4 - ВЫИГРАЛ ЕЖЕНЕДЕЛЬНЫЙ ПРИЗ ЗА КОМЕНТЫ

						$data['Userlottery']['winner'] = $winner;
						$this->Userlottery->save($data);
					}
					else
					{
						$winner = 2;
						$data['Userlottery']['winner'] = $winner;
						$this->Userlottery->save($data);
						//ВЫДАЕМ ВИПа (холостая оплата + перенос в группу)
						if (isset($this->authUserGroups) && !in_array(Configure::read('VIPgroupId'), $this->authUserGroups))
						{
							$payData = array(
								'Pay' => array(
									'user_id' => $this->authUser['userid'],
									'created' => time(),
									'paydate' => time(),
									'findate' => time() + 3600 * 24 * 30,
									'summ'	  => 0,
									'status'  => _PAY_DONE_,
									'info'	  => 'lottery priz',
								)
							);
							$this->Pay->create();
							$this->Pay->save($payData);
//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
							$this->authUserGroups[] = Configure::read('VIPgroupId');
            				$uInfo = array('Group' => array('Group' => $this->authUserGroups), 'User' => array('userid' => $this->authUser['userid'], 'lastactivity' => time()));
            				$this->User->save($uInfo);
						}
					}
КОНЕЦ РАЗДАЧИ СЛОНОВ
*/
				}
			}
			$this->redirect('/users/lottery');
		}
/*
pr($data);
pr($bidData);
exit;
*/
		$lotteryData = array();
		$inviteUsers = array();
		if (empty($id))
		{
			if (!empty($this->curLottery['Lottery']['id']))
			{
				$id = $this->curLottery['Lottery']['id'];
			}
		}

		$lotteryChances = array();
		if (!empty($id))
		{
			$lotteryData = $this->Lottery->read(null, $id);
			if (!empty($lotteryData['Lottery']['hidden']))
			{
				$lotteryData = array();
			}

			if (!empty($this->authUser['userid']) && !empty($lotteryData))
			{

				if (!empty($_POST['lottery_fraze']))
				{
//СОХРАНЯЕМ КОДОВУЮ ФРАЗУ
					$chanceInfo = $this->Userlottery->find(array('Userlottery.lottery_id' => $id, 'Userlottery.user_id' => $this->authUser['userid'], 'Userlottery.inv_user_id' => 0));
					if (!empty($chanceInfo))
					{
						$chanceInfo['Userlottery']['fraze'] = substr($_POST['lottery_fraze'], 0, 255);
						$this->Userlottery->save($chanceInfo);
					}
				}

				$winnerLot = 0; $winnerRegistered = ''; $winnerInfo = array();
				$lotteryChances = $this->Userlottery->findAll(array('Userlottery.lottery_id' => $id, 'Userlottery.user_id' => $this->authUser['userid']));
				if (!empty($lotteryChances))
				{
					foreach ($lotteryChances as $klc => $lC)
					{
						if (!empty($lC['Userlottery']['inv_user_id']))
						{
							//ЭТО БОНУСНАЯ РЕГИСТРАЦИЯ
							$inviteUsers[] = $lC['Userlottery']['inv_user_id'];
						}

						if (!empty($lC['Userlottery']['winner']))
						{
							$winnerInfo = $lC['Userlottery'];
							$winnerLot = $lC['Userlottery']['unique_code'];
							$winnerRegistered = explode(' ', $lC['Userlottery']['registered']);
							$winnerRegistered = date('Y-m-d', strtotime($winnerRegistered[0]) + $winDelay);
						}
					}
					$inviteUsers = $this->User->findAll(array('User.userid' => $inviteUsers));
				}

				if (!empty($winnerLot) && ($action == 'getprize'))
				{
					$winnerInfo['Userlottery']['winner'] = $winnerInfo['Userlottery']['winner'] * -1; //ЗНАЧИТ УВЕДОМЛЕНИЕ ОТПРАВЛЕНО
					$this->Userlottery->save($winnerInfo);
//ОТПРАВКА ПИСЬМА ПОБЕДИТЕЛЮ
					Configure::write('debug', 1);
			        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                    /*to  */$this->authUser['username'] .
                    '<' .
                    $this->authUser['email'] .
                                         '>',
                    /*subj*/Configure::read('App.siteName') . ' - ' . __('get the prize', true),
                    /*body*/
                    __('Hi', true) . ', ' . $this->authUser['username'] . "!\n\n" .
                    __("Lottery", true) . ' "' . $lotteryData['Lottery']['hd'] . '"' . "\n\n" .
                    __('Congratulations! You Win!', true) . "\n\n" .
                    __('lot of winning', true) . ' - ' . $winnerLot . "!\n\n" .

"Дополнительную информацию с описанием подарка мы направим Вам через неделю!" .

                    "\n\n \n" . Configure::read('App.siteName') . " Robot");
		            $this->Session->setFlash(__('Инструкция по получению приза отправлена вам на электронную почту!', true));
            		$this->redirect('/users/lottery/' . $lotteryData['Lottery']['id']);
				}
			}
			/*
			if (empty($lotteryChances))
			{
				$action = 'rules';
			}
			else
			{
				$action = 'info';//ИНФОРМАЦИИ ДЛЯ УЧАСТНИКА
			}

			switch ($action)
			{
				case "info":

				break;
			}
			*/
			if (!empty($this->curLottery))
			{
		//СТАТИСТИКА ПОСТОВ
				$userPostsCnt = $this->Vbpost->getFilmsCommentsCnt($this->authUser['userid']);
				$this->set('userPostsCnt', $userPostsCnt);

		//СТАТИСТИКА ПРИГЛАШЕННЫХ
				$userInvitesCnt = $this->Userlottery->getInvitesCnt($id, $this->authUser['userid']);
				$this->set('userInvitesCnt', $userInvitesCnt);

		//ПРИСВОЕНИЕ СТАТУСА "ПОБЕДИТЕЛЬ" ПОСЛЕ ОТСРОЧКИ
				$delayLst = $this->Userlottery->getDelayers($this->curLottery['Lottery']['id'], $winDelay);
				if (!empty($delayLst))
				{
					foreach($delayLst as $dL)
					{
						//ПРОВЕРЯЕМ БЫЛ ЛИ ОПЛАЧЕН ПРИЗОВОЙ ВИП
						$vipInfo = $this->Pay->find(array(
							'Pay.summ' => 0,
							'Pay.status' => _PAY_DONE_,
							'Pay.paydate >' => strtotime($this->curLottery['Lottery']['created']),
							'Pay.paydate <' => strtotime($this->curLottery['Lottery']['finished']),
						));
						if (!empty($vipInfo))
						{
							$winner = -2;//ЗНАЧИТ УЖЕ ВЫДАВАЛИ ВИП В КАЧЕСТВЕ ПРИЗА
						}
						else
						{
							$winner = 2;
							$payData = array(
								'Pay' => array(
									'user_id' => $dL['userlotteries']['uid'],
									'created' => time(),
									'paydate' => time(),
									'findate' => time() + 3600 * 24 * 30,
									'summ'	  => 0,
									'status'  => _PAY_DONE_,
									'info'	  => 'lottery priz',
								)
							);
							$this->Pay->create();
							$this->Pay->save($payData);
//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
            				$uInfo =
            					array('Group' => array(Configure::read('VIPgroupId')),
            					'User' => array(
            						'userid' => $dL['userlotteries']['uid'],
            						'lastactivity' => time()
            						)
            					);
            				//$this->User->save($uInfo);
						}

						$ulData = array('Userlottery' => array(
							'id' => $dL['userlotteries']['id'],
							'winner' => $winner,
						));
						$this->Userlottery->save($ulData);
					}
				}

		//РАССЫЛКА ПИСЕМ ПОБЕДИТЕЛЯМ
				$winnersLst = $this->Userlottery->getWinners($this->curLottery['Lottery']['id']);
				if (!empty($winnersLst))
				{
					foreach ($winnersLst as $w)
					{
						if (!empty($w['user']['email']))
						{
							Cache::delete('Office.winners', 'office');
							$prize = "Главный Приз недели";
		                    switch ($w['userlotteries']['winner'])
		                    {
		                    	case 2:
		                    		$prize = "Статус VIP сроком на три месяца";
		                    	break;
		                    }

							Configure::write('debug', 1);
					        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
		                    /*to  */$w['user']['username'] .
		                    '<' .
		                    $w['user']['email'] .
		                                         '>',
		                    /*subj*/Configure::read('App.siteName') . ' - ' . __('get the prize', true),
		                    /*body*/
		                    __('Hi', true) . ', ' . $w['user']['username'] . "!\n\n" .
		                    __("Lottery", true) . ' "' . $this->curLottery['Lottery']['hd'] . '"' . "\n\n" .
		                    __('Congratulations! You Win!', true) . "\n\n" .
		                    //__('lot of winning', true) . ' - ' . $winnerLot . "!\n\n" .
		                    //'Кодовая фраза - "' . $fraze . "\"!\n\n" .

		                    'Ваш приз: ' . $prize . "\n\n" .

		                    Configure::read('App.siteName') . " Robot");

							$ulInfo = array(
								'Userlottery' => array(
									'id' => $w['userlotteries']['id'],
									'winner' => $w['userlotteries']['winner'] * (-1),
								)
							);
							$this->Userlottery->save($ulInfo);
						}
					}

				}
			}
		}
		$this->set('dup', $dup);
		$this->set('inviteUsers', $inviteUsers);
		$this->set('lotteryData', $lotteryData);
		$this->set('curLottery', $this->curLottery);
		$this->set('lotteryChances', $lotteryChances);
		$this->set('inLottery', $this->inLottery);
		$this->set('action', $action);
	}

//ЛИЧНЫЙ КАБИНЕТ
	function office($action = '')
	{
		if (!empty($this->authUser['userid']))
		{
			switch ($action)
			{
				default: //СБОР СТАТИСТИКИ ДЛЯ ЛИЧНОГО КАБИНЕТА
		//ПОДТВЕРЖДЕНИЕ ЭЛЕКТРОННОГО АДРЕСА
				if ($this->authUser['usergroupid'] == 3) //NOT CONFIRMED
				{
					//EMAIL не подвтержден
				}

		//СОГЛАСИЕ С ПОЛЬЗОВАТЕЛЬСКИМ СОГЛАШЕНИЕМ
				if ($this->authUser['agree'])
				{
					//СОГЛАСЕН
				}

		//СВЯЗЬ С ГЕО-РЕГИОНОМ/ГОРОДОМ
				if (!empty($geoInfo))
				{
	        		//$geoInfo['city'] = $cityInfo['Geocity']['name'];
		        	//$geoInfo['region'] = $regionInfo['Georegion']['name'];
				}

		//ПЛАТЕЖИ
				$payList = $this->Pay->findAll(array('Pay.user_id' => $this->authUser['userid'], 'Pay.status' => _PAY_DONE_, 'Pay.summ >' => 0), null, 'Pay.created DESC');
				$this->set('payList', $payList);

		//УЧАСТИЕ В ЛОТЕРЕЯХ
				$userLotteries = $this->Userlottery->findAll(array('Userlottery.user_id' => $this->authUser['userid']));
				$this->set('userLotteries', $userLotteries);
				$lotteryList = $this->Lottery->findAll(array('Lottery.hidden' => 0), null, 'Lottery.created DESC');
				$this->set('lotteryList', $lotteryList);
				//$this->curLottery;//инфо об актуальном розыгрыше

		//СТАТИСТИКА ПОСТОВ
				$userPostsCnt = $this->Vbpost->getFilmsCommentsCnt($this->authUser['userid']);
				$this->set('userPostsCnt', $userPostsCnt);

		//СТАТИСТИКА ПРИГЛАШЕННЫХ
				if (!empty($this->curLottery))
				{
					$userInvitesCnt = $this->Userlottery->getInvitesCnt($this->curLottery['Lottery']['id'], $this->authUser['userid']);
					//$userInvitesCnt = $this->Userlottery->getInvitesCnt(2, $this->authUser['userid']);
					$this->set('userInvitesCnt', $userInvitesCnt);
				}

		//ЛИЧНЫЙ СООБЩЕНИЯ
			//ПОСЛЕДНИЕ
				$userMessages = $this->Usermessage->findAll(array('Usermessage.hidden' => 0, array('OR' => array(array('Usermessage.to_id' => $this->authUser['userid']), array('Usermessage.from_id' => $this->authUser['userid'])))), null, null, 5);
			//СКОЛЬКО НОВЫХ
				$newMessages = $this->Usermessage->findAll(array('Usermessage.is_new' => 1, 'Usermessage.to_id' => $this->authUser['userid']), array('Usermessage.id'));
			//ЧЕРНОВИКИ
				$editMessages = $this->Usermessage->findAll(array('Usermessage.hidden' => 1, 'Usermessage.from_id' => $this->authUser['userid']));
				$this->set('userMessages', $userMessages);
				$this->set('newMessages', $newMessages);
				$this->set('editMessages', $editMessages);

		//ИЗБРАННОЕ (МОЕ ВИДЕО)
				$userVideos = array();
				$this->set('userVideos', $userVideos);
			}
		}
		else
		{
			$this->redirect('/users/login');
		}
	}

    function logout()
    {
        $this->Vb->clearCookies();
        $this->Cookie->del('Auth.User');

        //УДАЛЯЕМ КУКИ БЕЗ ДОМЕНА
        Configure::write('App.cookieDomain', '');
		$this->Cookie->del('Auth.User');

        $this->redirect($this->Auth2->logout());
    }

    public function scode()
    {
        $this->layout = 'ajax';
    	$this->set("captcha", $this->Session->read('captcha'));
    	$this->render();
    }

	function suspend()
	{
	}

    function register()
    {
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_)
		{
			$this->redirect('suspend');
		}

		if (empty($this->data))
            return;

        if (isset($this->data['User']['password2']))
            $this->data['User']['password2'] = $this->Auth2->password($this->data['User']['password2']);

        if (isset($this->data['User']['captcha']))
            $this->data['User']['captcha2'] = $this->Session->read('captcha');
        //$this->data['User']['salt'] = Configure::read('Security.smallSalt');

        $res = $this->User->set($this->data['User']);

	//pr($this->data);

        //if (!$this->User->validates())
		if (!isset($_POST["scode"]))
		{
			$_POST["scode"] = 0;
		}

        if ((md5($this->Session->read('captcha') . '1234567890') != $_POST["scode"]) || !$this->User->validates())
        {
            $this->Session->setFlash(__('Creating user Error', true));

            $userInfo = $this->User->find(
            	array('OR' =>
            		array(
            			array('User.email' => $this->data['User']['email']),
            			array('User.username' => $this->data['User']['username'])
            		)
            	)
            );
            if ($userInfo)
            {
	            if ($userInfo['User']['usergroupid'] == 3)//НЕ ПОДТВЕРЖДЕН
	            {
		            $this->Session->setFlash(__('Login exists Registration not confirmed', true));
	            }
	            else
	            {
		            $this->Session->setFlash(__('User already registered', true));
	            }
            }
            unset($this->data['User']['password2']);
            unset($this->data['User']['password']);
            unset($this->data['User']['captcha']);
            unset($this->data['User']['captcha2']);
            return;
        }
        $user = $this->Vb->createUser($this->data['User']['username'], $this->data['User']['password'],
//                              $this->data['User']['email'], true, true, 2);//!!! ЮЗЕР СОЗДАЕТСЯ БЕЗ ПОДТВЕРЖДЕНИЯ (ПОСЛЕДНИЙ ПАРАМЕТР = 2 НУЖНО УДАЛИТЬ, ЧТОБЫ ТРЕБОВАЛОСЬ ПОДТВЕРЖДЕНИЕ)
                              $this->data['User']['email'], true, true);

        if (empty($user))
        {
            $this->Session->setFlash(__('Creating user Error. Check for valid email and confirmed password', true));
            unset($this->data['User']['password2']);
            unset($this->data['User']['password']);
            return;
        }

        // Send an email with activation link
        $activation_token = $this->Vb->createActivationId($user['User']['userid'], 3);

        $agreementInfo = array('Useragreement' =>
        	array('user_id' => $user['User']['userid'], 'agree' => $this->data['User']['agreement'])
        );
        $this->Useragreement->save($agreementInfo);

        //add user to default portal group
        $user['Group']['Group'][0] = Configure::read('App.defaultUserGroup');
        $this->User->save($user);
        $aro = new Aro();
        $parent = $aro->node(array('model' => 'Group', 'foreign_key' => Configure::read('App.defaultUserGroup')));
        $parent = Set::extract($parent, "0.Aro.id");
        //$user['User']['userid'], Configure::read('App.defaultUserGroup'), 'User.' . $user['User']['userid']

        /* ПРАВА ПО ПОЛЬЗОВАТЕЛЮ ВЫСТАВЛЯТЬ НЕ БУДЕМ
        $aro->create();
        $self = $aro->node(array('model' => 'User', 'foreign_key' => $user['User']['userid']));
        if (!$self)
            $aro->save(array('foreign_key' => $user['User']['userid'], 'parent_id' => $parent,
                             'alias' => 'User.' . $user['User']['userid'], 'model' => 'User'));

        else
        {
            $self[0]['Aro']['alias'] = 'User.' . $user['User']['userid'];
            $self[0]['Aro']['parent_id'] = $parent;
            $aro->save($self[0]);
        }
        ///*/

		Configure::write('debug', 1);
        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
				/*to  */$this->data['User']['username'] .
				'<' .
					$this->data['User']['email'] .
				'>',
				/*subj*/Configure::read('App.siteName') . ' - ' . __('New account', true),
				/*body*/__('Your Login', true) . ": username: " . $this->data['User']['username'] . "\n " . __('Register confirmation link', true) . ":\n" . Configure::read('App.siteUrl') . "users/confirm/" . $activation_token . "\n\n \n" . Configure::read('App.siteName') . " Robot"
		);

        if (!$result)
        {
            $this->Session->setFlash(__('Send email error', true));
            CakeLog::write(LOG_DEBUG, 'Mail error, ' . $this->Session->read('Message.email'));
            CakeLog::write(LOG_ERROR, 'Mail error to: ' . $this->data['User']['email'] . ', error: ' . $this->Email->smtpError);
            $this->redirect('login');
        }
//We could redirect to login.....
//		$this->Session->setFlash("Спасибо за регистрацию! Ссылка для активации вашего аккаунта выслана на ваш email.");
        $this->Session->setFlash(__("Thanks for register Login please", true));

        // .. or login and redirect to proper page
        //$this->Auth2->login($this->data);

        $this->redirect('login');
    }
    //
    // Restore user password
    //
    function restore()
    {

        if (empty($this->data))

            return;

        // Check email is correct
        $data = $this->User->findByEmail(
                        $this->data['User']['email'],
                        array(
                        'userid' ,
                        'username' ,
                        'email'));

        if (!$data)
        {
            $this->User->invalidate('email', __('No such email registered', true));
            $this->Session->setFlash($this->data['User']['email'] . ' ' . __('No such email registered', true));
	        return;
        }

        $notConfirmed = false;
    	$userInfo = $this->User->find(array('User.email' => $this->data['User']['email']));
        if ($userInfo)
        {
            if ($userInfo['User']['usergroupid'] == 3)//НЕ ПОДТВЕРЖДЕН
            {
            	$notConfirmed = true;
	            $this->Session->setFlash(__('Login exists Registration not confirmed', true));
		        $activation_token = $this->Vb->createActivationId($userInfo['User']['userid'], 3);

				Configure::write('debug', 1);
		        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                /*to  */$userInfo['User']['username'] .
                '<' .
                $userInfo['User']['email'] .
                                     '>',
                /*subj*/Configure::read('App.siteName') . ' - ' . __('New account', true),
                /*body*/__('Your Login', true) . ": username: " . $userInfo['User']['username'] . "\n " . __('Register confirmation link', true) . ":\n" . Configure::read('App.siteUrl') . "users/confirm/" . $activation_token . "\n\n\n" . Configure::read('App.siteName') . " Robot"
                );
            }
        }

        //ассоциированные модели нам не нужны
		//к тому же при сохранении ломалась связь HABTM
		unset($data["Group"]);
		unset($data["GroupUser"]);

        $this->data = $data;
//var_dump($data);
        // Generate new password
        $password = $this->Auth2->createPassword();

        $data['User']['password'] = $data['User']['password2'] = $this->Auth2->password($password);

        $this->User->begin();

        if (!$this->User->save($data))
        {
            $this->User->rollback();
            return;
        }

        // Send email
		Configure::write('debug', 1);
        if (!$this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
			/*to  */$data['User']['username'] .
            '<' .
				$data['User']['email'] .
			'>',
			/*subj*/'New password',
			/*body*/__("Dear", true) . ' ' . $data['User']['username'] .
				"!\n\n" . __("Restore Password Request", true) . " " . Configure::read('App.siteUrl') .
				"\n\n" . __("Your Login", true) . ": " . $data['User']['username'] .
				"\n" . __("Your new Password", true) . ": " . $password . "\n\n\n" . Configure::read('App.siteName') . " Robot"
			)
		)
		{
            CakeLog::write(LOG_ERROR, 'Mail error to: ' . $data['User']['email'] . ', error: ' . $this->Email->smtpError);
            $this->User->rollback();

            $this->flash('Internal server error during sending mail', 'restore', 10);
        }
        else
        {
            $this->User->commit();
            if (!$notConfirmed)
            	$this->Session->setFlash(__('Sent new Password', true));
            //$this->flash(sprintf(__('New password sent to %s. Please login', true), $data['User']['email']), '/', 10);
        }
        $this->redirect('/users/login');
    }

    //
    // Confirm user email by token
    //
    function confirm($activationToken = '')
    {
/*
		if (empty($this->authUser['userid']))
		{
			$this->redirect('/users/login');
			return;
		}
*/
		$data = false;
		if ($activationToken)
		{
    		$data = $this->UserActivation->findByActivationid($activationToken);
		}
        if (($activationToken === '') || (!$data))
        {
        	if (!empty($this->authUser['userid']))
        	{
		        $this->UserActivation->removeActivationByUid($this->authUser['userid']);
		        $this->authUser['usergroupid'] = 2;
       	        $this->User->save($this->authUser);
		       	$user = $this->User->read($this->authUser['userid']);

//                $this->Session->write('Auth.' . $this->Auth2->userModel . '.vbpassword', $this->Vb->cookiePass($user['User']['password']));
//                $this->Vb->setLoginCookies($this->authUser['userid'], $user['User']['password']);
				$this->Session->setFlash(__('Account %s confirmed. Please login.', true));
       	        $this->redirect('/users/logout');
        	}


	        $activation_token = $this->Vb->createActivationId($this->authUser['userid'], 3);

			Configure::write('debug', 1);
	        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
            /*to  */$this->authUser['username'] .
            '<' .
            $this->authUser['email'] .
                                 '>',
            /*subj*/Configure::read('App.siteName') . ' - ' . __('Confirm account', true),
            /*body*/__('Your Login', true) . ": username: " . $this->authUser['username'] . "\n " . __('Register confirmation link', true) . ":\n" . Configure::read('App.siteUrl') . "users/confirm/" . $activation_token . "\n\n \n" . Configure::read('App.siteName') . " Robot");

        }
        else
        {
        // Activate user account

	        $user = array();
	        $user['User']['usergroupid'] = $data['UserActivation']['usergroupid'];
	        $user['User']['userid'] = $data['UserActivation']['userid'];

	        $this->User->save($user);
	        $user = $this->User->read();
	        $this->UserActivation->removeActivation($activationToken);

	        $this->Session->setFlash(sprintf(__('Account %s confirmed. Please login.', true), $user['User']['username']));
	        $this->redirect('logout');
        }
    }
    //
    // Generates captcha image
    //
    function captcha()
    {
        $this->layout = 'ajax';
        $this->Captcha->render();
        $this->view = null;
    }

    function admin_home()
    {
    }

    function admin_index()
    {
        $this->User->recursive = 0;
        if (!empty($this->passedArgs['search']))
        {
        	$this->paginate['User']['conditions'][] = array('or' => array('User.email ' => $this->passedArgs['search'], 'User.username like ' => '%' . $this->passedArgs['search'] . '%'));
        }
        $this->set('users', $this->paginate());
        $this->set('args', $this->passedArgs);
/*
	Configure::write('debug', 1);
        $result = $this->_sendEmail(Configure::read('App.mailFrom'),
                          'vanogml@gmail.com' ,
                          Configure::read('App.siteName') . ' - ' . __('New account', true),
                          sprintf(__("тестовое письмо", true)));

        $page = 0;
        $srt = '';
        $dir = '';
        if (isset($this->passedArgs['page']))
        {
        	$page = intval($this->passedArgs['page']);
        }
        if (isset($this->passedArgs['direction']))
        {
        	$dir = $this->passedArgs['direction'];
        }
        if (isset($this->passedArgs['srt']))
        {
        	$srt = $this->passedArgs['srt'];
        }
        if (!empty($srt))
        {
        	if ($this->User->getColumnType($srt))//КОСВЕННО ПРОВЕРЯЕМ НАЛИЧИЕ ПОЛЯ В ТАБЛИЦЕ
        	{
        		if ($dir <> 'desc')
        		{
        			$dir = 'asc';
        		}
        		$srt .= ' ' . $dir;
        	}
        	else
        		$srt = '';
        }
        $users = $this->User->findAll(null, null, $srt, 30, $page, 0);
        $this->set('users', $users);
//*/
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash('Invalid User.');
            $this->redirect(array('action' => 'index'), null, true);
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function admin_add()
    {
        if (!empty($this->data))
        {
//            debug($this->data);
//            die();
			if (empty($this->data['Group']['Group']))
			{
                $this->Session->setFlash(
                                'The User could not be saved. Group not selected.');
                return;
			}

   	        $this->data['User']['password'] = $_POST['data']['User']['password'];
            $res = $this->Vb->createUser($this->data['User']['username'],
                                  $this->Auth2->password($this->data['User']['password']),
                                  $this->data['User']['email'],
                                  true, false, $this->data['User']['usergroupid']);
//            $this->User->create();
//            if ($this->User->save($this->data))
            if (!empty($res['User']))
            {
                $this->data['User'] = $res['User'];
                $this->User->save($this->data);
                $this->Session->setFlash(
                                'The User has been saved');
                $this->redirect(
                                array(
                                'action' => 'index'),
                                null,
                                true);
            }
            else
            {
                $this->Session->setFlash(
                                'The User could not be saved! Please, try again.');
            }
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    function admin_edit($id = null)
    {
        if (!$id && empty($this->data))
        {
            $this->Session->setFlash('Invalid User');
            $this->redirect(array('action' => 'index'), null, true);
        }
        if (!empty($this->data))
        {
			Configure::write('debug', 2);
	        ini_set('memory_limit', '1G');
            if (empty($this->data['User']['password']))
                unset($this->data['User']['password']);
			$this->User->create();
            //$this->User->set($this->data);
            $user = $this->User->read(null, $this->data['User']['userid']);
            unset($user['Pay']);
            unset($user['Useragreement']);
            $user['User'] = am($user['User'], $this->data['User']);
            $user['User'] = $this->data['User'];
            $user['Group'] = $this->data['Group'];
            //$this->User->set($user);
            if ($this->User->save($user, false))
            {
                $this->Session->setFlash('The User saved');
                $this->redirect(array('action' => 'index'), null, true);
            } else
            {
                $this->Session->setFlash('The User could not be saved. Please, try again.');
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->User->read(null, $id);
            unset($this->data['User']['password']);
        }

        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    /**
     * различные сервисные операции с пользователями
     *
     * @param string $action
     */
    function admin_service($action = '')
    {
		$sql = 'SELECT user . * , groups_users.group_id AS gid FROM `user`
			LEFT JOIN groups_users ON ( user.userid = groups_users.user_id )
			WHERE isnull( groups_users.group_id )
		';
    	switch ($action)
    	{
    		//восстановление принадлежности пользователей к группе "пользователи"
    		case "restoregroups":
    			$users = $this->User->query($sql);
    			if (count($users) > 0)
    			{
    				$data = array();
    				foreach ($users as $user)
    				{
    					if (!empty($user['user']['userid']))
    					{
    						$sql = 'insert into groups_users (group_id, user_id) values (9, ' . $user['user']['userid'] . ')';
    						$this->User->query($sql);
    					}
    				}
    			}
		    	$this->set('users', $users);
    		break;

    		default:
    			$users = $this->User->query($sql);
		    	$this->set('users', $users);
    	}
    	$this->set('action', $action);
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash('Invalid id for User');
            $this->redirect(array('action' => 'index'), null, true);
        }
        if ($this->User->del($id))
        {
            $this->Session->setFlash('User #' . $id . ' deleted');
            $this->redirect(array('action' => 'index'), null, true);
        }
    }

    function admin_whereis()
    {
    	$zone = false;
    	if (!empty($this->data))
    	{
    		$zones = Configure::read('Catalog.allowedIPs');

		    $zone = checkAllowedMasks($zones, $this->data['User']['ip'], 1);
    	}
    	$this->set("data", $this->data);
    	$this->set("zone", $zone);
    }

    /**
     * Добавляет АЦЛ и дефолтную группу всем юзерам.
     * Может быть полезна, для добавления портальной группы для форумных юзеров.
     *
     */
    function admin_setacl()
    {
        $limit = ' LIMIT %s, %s';
        $page = 1;
        $perPage = 100;

        $sql = 'SELECT * FROM user ';
        $query = $sql . sprintf($limit, $page - 1, $perPage);

        while ($objects = $this->User->query($query))
        {
            foreach ($objects as $object)
            {
                $user = $this->User->read(null, $object['user']['userid']);
                $groups = Set::extract('/Group/id', $user);

                unset($user['Group']);
                if (array_search(Configure::read('App.defaultUserGroup'), $groups) === false)
                {
                    array_push($groups, Configure::read('App.defaultUserGroup'));
                    $user['Group']['Group'] = $groups;
                    $this->User->create();
                    $this->User->set($user);
                    $this->User->save($user);
                }

                /*
                $aro = new Aro();
                $parent = $aro->node(array('model' => 'Group', 'foreign_key' => Configure::read('App.defaultUserGroup')));
                $parent = Set::extract($parent, "0.Aro.id");
                //$user['User']['userid'], Configure::read('App.defaultUserGroup'), 'User.' . $user['User']['userid']
                $aro->create();
                $self = $aro->node(array('model' => 'User', 'foreign_key' => $user['User']['userid']));
                if (!$self)
                    $aro->save(array('foreign_key' => $user['User']['userid'], 'parent_id' => $parent,
                                     'alias' => 'User.' . $user['User']['userid'], 'model' => 'User'));

                else
                {
                    $self[0]['Aro']['alias'] = 'User.' . $user['User']['userid'];
                    $self[0]['Aro']['parent_id'] = $parent;
                    $aro->save($self[0]);
                }
                */
            }

            $page++;
            $query = $sql . sprintf($limit, ($page - 1) * $perPage, $perPage);
        }
    }
}
?>