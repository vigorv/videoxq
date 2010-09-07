<?php
App::import('Component', 'Auth');
class Auth2Component extends AuthComponent
{
//	function login($data = null) {
//*
//		$result = parent::login($data);
/*
if ($data["User"]["username"] == 'vanoveb')
{
	$user = $this->identify($data);
	echo '<pre>';
	print_r($user);
	echo '</pre>123';
    exit;
}
//*/
//		return $result;
/*
		$this->__setDefaults();
		$this->_loggedIn = false;

		if (empty($data)) {
			$data = $this->data;
		}

		if ($user = $this->identify($data)) {
			$this->Session->write($this->sessionKey, $user);
			$this->_loggedIn = true;
		}
		return $this->_loggedIn;
		*/
//	}

	function initialize(&$controller)
    {
        $this->data = $controller->data;

		if (isset($_POST['redirect']))//ПРИ АВТОРИЗАЦИИ С ФОРУМА ПРОБЛЕМА С КОДИРОВКАМИ
        {
        	$this->data["User"]["username"] = iconv('cp1251', 'utf8', $this->data["User"]["username"]);
        	$this->data["User"]["password"] = iconv('cp1251', 'utf8', $this->data["User"]["password"]);
        	$controller->data = $this->data;

        	    if (strpos($_POST["redirect"], 'login'))
            	{
            	//	pr($_POST["redirect"]);
            		//exit;
	            	$_POST["redirect"] = '/forum/index.php';
            	}
        }
        return parent::initialize($controller);
    }

    //
    // Generates password string
    //
    function createPassword($length = 8, $allowed_symbols = '23456789abcdeghkmnpqsvxyzABCDEGHKMNPQSVXYZ')
    {
        $keystring = '';

        // Next code taken and modified from kcaptcha
        // generating random keystring
        while (true)
        {
            $keystring = '';
            for ($i = 0; $i < $length; $i++)
            {
                $keystring .= $allowed_symbols{mt_rand(0, strlen($allowed_symbols) - 1)};
            }
            if (! preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp/i', $keystring))
                break;
        }

        //
        return $keystring;
    }


    /**
     * Генерим хэш пароля в VB-совместимом виде
     *
     * @param unknown_type $password
     * @return unknown
     */
    function password($password)
    {
        if (empty($password))
            return '';
        $model = & $this->getModel();
        $model->recursive = - 1;

        if (empty($this->data[$this->userModel][$this->fields['username']]))
        {
            $field = 'email';
        }
        else
            $field = $this->fields['username'];

        $data = $model->find(array($field => $this->data[$this->userModel][$field]));

        if (empty($data[$this->userModel]))
        {
            return md5(md5($password) . Configure::read('Security.smallSalt'));
        }

        return md5(md5($password) . $data[$this->userModel]['salt']);
    }

    /**
     * Воркараунд для кейка, чтобы можно было выставить права незареганным
     *
     * @param unknown_type $key
     * @return unknown
     */
    function user($key = null)
    {
        $this->__setDefaults();
        if (! $this->Session->check($this->sessionKey))
        {
            if ($key == null)
                return array('User' => array('userid' => 0, 'usergroupid' => Configure::read('App.guestGroup')));
            elseif ($key == 'userid')
                return 0;
            elseif ($key == 'usergroupid')
                return Configure::read('App.guestGroup');
            else
                return null;
        }

        if ($key == null)
        {
            return array($this->userModel => $this->Session->read($this->sessionKey));
        }
        else
        {
            $user = $this->Session->read($this->sessionKey);
            if (isset($user[$key]))
            {
                return $user[$key];
            }
            else
            {
                return null;
            }
        }
    }

}
?>