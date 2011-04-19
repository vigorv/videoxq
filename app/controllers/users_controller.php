<?php
class UsersController extends AppController
{

    var $name = 'Users';
    var $helpers = array('Html' , 'Form');
    var $components = array('Captcha' , 'Email', 'ControllerList');
    var $uses = array('User', 'Group', 'UserActivation', 'Useragreement',
//    'DleUser'
    );

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
				        $activation_token = $this->Vb->createActivationId($userInfo['User']['userid'], 3);

						Configure::write('debug', 1);
				        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
	                    /*to  */$userInfo['User']['username'] .
	                    '<' .
	                    $userInfo['User']['email'] .
	                                         '>',
	                    /*subj*/Configure::read('App.siteName') . ' - ' . __('New account', true),
	                    /*body*/__('Your Login', true) . ": username: " . $userInfo['User']['username'] . "\n " . __('Register confirmation link', true) . ":\n" . Configure::read('App.siteUrl') . "users/confirm/" . $activation_token . "\n\n \n" . Configure::read('App.siteName') . " Robot");
	            		$this->redirect('/users/login');
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
	            	$this->redirect('/forum/index.php');
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
        if (empty($this->data) && !$this->authUser['userid'])
        {
            $this->_checkLoginCookie(true);
        }
        elseif (empty($this->data) && $this->authUser['userid'])
            $this->redirect('/media');

        unset($this->data['User']['password']);
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
			        $activation_token = $this->Vb->createActivationId($userInfo['User']['userid'], 3);

					Configure::write('debug', 1);
			        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                    /*to  */$userInfo['User']['username'] .
                    '<' .
                    $userInfo['User']['email'] .
                                         '>',
                    /*subj*/Configure::read('App.siteName') . ' - ' . __('New account', true),
                    /*body*/__('Your Login', true) . ": username: " . $userInfo['User']['username'] . "\n " . __('Register confirmation link', true) . ":\n" . Configure::read('App.siteUrl') . "users/confirm/" . $activation_token . "\n\n \n" . Configure::read('App.siteName') . " Robot");
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
                              $this->data['User']['email'], true, true, 2);//!!! ЮЗЕР СОЗДАЕТСЯ БЕЗ ПОДТВЕРЖДЕНИЯ (ПОСЛЕДНИЙ ПАРАМЕТР = 2 НУЖНО УДАЛИТЬ, ЧТОБЫ ТРЕБОВАЛОСЬ ПОДТВЕРЖДЕНИЕ)

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

        if ($activationToken === '')
            $this->redirect('register');

        if (!($data = $this->UserActivation->findByActivationid($activationToken)))
            $this->redirect('register');

        // Activate user account


        $user = array();
        $user['User']['usergroupid'] = $data['UserActivation']['usergroupid'];
        $user['User']['userid'] = $data['UserActivation']['userid'];

        $this->User->save($user);
        $user = $this->User->read();
        $this->UserActivation->removeActivation($activationToken);

        $this->Session->setFlash(sprintf(__('Account %s confirmed. Please login.', true), $user['User']['username']));
        $this->redirect('login');
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