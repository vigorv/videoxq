<?php
// Enter YOUR license number for cookies.
define('VB_LICENSE', 'VBFRRKOU7O');

class VbComponent extends Object
{
    /**
     * @var vB_Registry
     */
    var $vbulletin;

    var $userModel = 'User';

    function initialize(&$controller)
    {
        $this->controller = $controller;
        if (empty($this->User))
            $this->User = $this->getModel();
        if (empty($this->UserActivation))
            $this->UserActivation = $this->getModel('UserActivation');
    }

    function startup(&$controller)
    {

    }

    /**
     * Returns a reference to the model object specified, and attempts
     * to load it if it is not found.
     *
     * @param string $name Model name (defaults to VbComponent::$userModel)
     * @return object A reference to a model object
     * @access public
     */
    function &getModel($name = null)
    {
        $model = null;
        if (!$name)
            $name = $this->userModel;

        if (PHP5)
            $model = ClassRegistry::init($name);
        else
            $model =& ClassRegistry::init($name);

        if (empty($model))
        {
            trigger_error(__('Vb::getModel() - Model is not set or could not be found',
                             true),
                            E_USER_WARNING);
            return null;
        }

        return $model;
    }

    /**
     * Initialize a vBulletin object.
     */
    function vbInit($class = FALSE, $config = TRUE, $options = TRUE)
    {
        if ($class)
        {
            $vbulletin = new vB_Registry();
        }
        if ($config)
        {
            $vbulletin->config = $this->fetchConfig();
        }
        if ($options)
        {
            $vbulletin->options = $this->getOptions();

        }
        //$this->vbulletin = $vbulletin;
        return $vbulletin;
    }

    /**
     * Grab the configuration/options of the vB installation.
     */
    function fetchConfig()
    {
        static $config = array();

        $config_file =  ROOT . Configure::read('App.forumPath') . 'includes/config.php';
        if (empty($config) && file_exists($config_file))
        {
            require_once $config_file;
        }
        return $config;
    }

    /**
     * Parse (if necessary) and display appropriate drupalvb messages.
     */
    function msg($msg, $arg = 'unknown')
    {
        switch ($msg)
        {
            case DVBERROR:
                $this->Session->setFlash('Error: ' . $arg, 'default', array(), 'error');
                break;

            default:
                $this->Session->setFlash($arg);
        }
        return;
    }

    /**
     * Set the necessary cookies for the user to be logged into the forum.
     */
    function setLoginCookies($userid, $password, $hashedPassword = false)
    {
        $vbulletin = $this->vbInit();
        $cookie_prefix = (empty($vbulletin->config['Misc']['cookieprefix']) ? 'portalxq' : $vbulletin->config['Misc']['cookieprefix']);
        $cookie_path = '/';//$vbulletin->options['cookiepath'];
        $cookie_domain = trim($vbulletin->options['cookiedomain']);

        $expire = time() + 60 * 60 * 24 * 365;
        setcookie($cookie_prefix . 'sessionhash',
                                        md5(
                                        'portalxq' .
                                        $userid),
                                        $expire,
                                        $cookie_path,
                                        $cookie_domain);
        setcookie($cookie_prefix . 'lastvisit',
                        time(),
                        $expire,
                        $cookie_path,
                        $cookie_domain);
        setcookie($cookie_prefix . 'lastactivity',
                        '0',
                        $expire,
                        $cookie_path,
                        $cookie_domain);
        setcookie($cookie_prefix . 'userid',
                        $userid,
                        $expire,
                        $cookie_path,
                        $cookie_domain);
        setcookie($cookie_prefix . 'password', $this->cookiePass($password, $hashedPassword), $expire, $cookie_path, $cookie_domain);

        return;
    }


    /**
     * Чистим куки
     *
     */
    function clearCookies()
    {
        $vbulletin = $this->vbInit();

        $cookie_prefix = (empty($vbulletin->config['Misc']['cookieprefix']) ? 'portalxq' : $vbulletin->config['Misc']['cookieprefix']);
        $cookie_path = '/';//$vbulletin->options['cookiepath'];
        $cookie_domain = $vbulletin->options['cookiedomain'];

        // Clear our login cookies beginning.
        setcookie($cookie_prefix . 'sessionhash',
                        '',
                        1,
                        $cookie_path,
                        $cookie_domain);
        setcookie($cookie_prefix . 'lastvisit', '', 1, $cookie_path, $cookie_domain);
        setcookie($cookie_prefix . 'lastactivity', '', 1, $cookie_path, $cookie_domain);
        setcookie($cookie_prefix . 'userid', '', 1, $cookie_path, $cookie_domain);
        setcookie($cookie_prefix . 'password', '', 1, $cookie_path, $cookie_domain);

        return;
    }


    /**
     * Делаем хэш пароля для того, чтобы логиниться в Булку
     *
     * @param unknown_type $password
     * @param unknown_type $hashedPassword
     * @return unknown
     */
    function cookiePass($password, $hashedPassword = false)
    {
        if ($hashedPassword)
            return $password;

        return md5($password . VB_LICENSE);
    }


    /**
     * Регаем пользователя
     *
     * @param unknown_type $username
     * @param unknown_type $password
     * @param unknown_type $email
     * @param unknown_type $hashedPassword
     * @param unknown_type $silent
     * @param unknown_type $usergroupid
     * @return unknown
     */
    function createUser($username, $password, $email, $hashedPassword = false, $silent = FALSE, $usergroupid = '3')
    {
        // Set up the necessary variables.
        $salt = '';
        for ($i = 0; $i < 3; $i++)
        {
            $salt .= chr(rand(32, 126));
        }

        // This hack expects users to not have 32 character length passwords.
// WTF???
//        if (strlen($password) != 32)
//        {
//            $password = md5($password);
//        }
//        if (!$hashedPassword)
//            $passhash = md5(md5($password) . $salt);
//        else
//        {
            $passhash = $password;
            $salt = Configure::read('Security.smallSalt');
//        }
        $passdate = date('Y-m-d H:i:s');
        $joindate = time();


        $resarray = $this->User->query("SELECT title FROM usertitle WHERE minposts = 0");

        if ($resarray)
        {
            $usertitle = $resarray[0]['usertitle']['title'];
        }
        else
        {
            $usertitle = 'Junior Member';
        }

        $options = '11537783';
        $timezone = Configure::read('App.timezone');


        $ip = empty($_SERVER['REMOTE_ADDR']) ? '' : $_SERVER['REMOTE_ADDR'];

        $user = array('User' => array('username' => $username, 'usergroupid' => $usergroupid,
                      'password' => $passhash, 'passworddate' => $passdate, 'usertitle' => $usertitle,
                      'email' => $email, 'salt' => $salt, 'showvbcode' => 2, 'languageid' => 0,
                      'timezoneoffset' => $timezone, 'posts' => 0, 'joindate' => $joindate,
                      'lastvisit' => $joindate, 'lastactivity' => $joindate, 'options' => $options,
                      'reputationlevelid' => 5, 'startofweek' => 2, 'ipaddress' => $ip));
       	$this->User->create();
		$user['User']['password2'] = $user['User']['password'];
        //return user array or false
        $user = $this->User->save($user);
        if (!empty($user))
        {
//        	$user = $this->User->read(null, $this->User->getLastInsertID());
        	$this->User->query("INSERT INTO userfield (userid) VALUES (".$user['User']['userid'].")");
        	$this->User->query("INSERT INTO usertextfield (userid) VALUES (".$user['User']['userid'].")");
        }
        return $user;
    }

    /**
     * Get the vBulletin options.
     */
    function getOptions()
    {
        $result = $this->User->query("SELECT data FROM datastore WHERE title = 'options'");
        if ($data = $result[0]['datastore'])
        {
            $options = unserialize($data['data']);
        }

        return $options;
    }

    /**
     * Prefix database tables in a SQL query.
     */
    function prefix($sql)
    {
        $vbulletin = $this->vbInit(FALSE, TRUE, FALSE);
        return strtr($sql, array('{' => $vbulletin->config['Database']['tableprefix'], '}' => ''));
    }


    /**
    * vBulletin's own random number generator
    *
    * @param    integer Minimum desired value
    * @param    integer Maximum desired value
    * @param    mixed   Seed for the number generator (if not specified, a new seed will be generated)
    */
    function vbrand($min, $max, $seed = -1)
    {
        if (!defined('RAND_SEEDED'))
        {
            if ($seed == -1)
            {
                $seed = (double) microtime() * 1000000;
            }

            mt_srand($seed);
            define('RAND_SEEDED', true);
        }

        return mt_rand($min, $max);
    }

    function createActivationId($userid, $usergroupid)
    {
        return $this->UserActivation->createActivationId($userid, $usergroupid);
    }

}