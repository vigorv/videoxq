<?php

/**
 * Description of mobile_controller
 *
 * @author snowing
 */
class MainaController extends AppController {

    var $name = 'Maina';
    var $layout = 'newstyle';
    var $viewPath = 'maina';
    var $helpers = array('Html', 'javascript');
    var $components = array();
    var $uses = array('Film','Direction', 'News',
        'UserDownloadHistory',
        'UserWishlist','UserFriends'
        );

    function BeforeFilter() {
        parent::beforeFilter();
        if (isset($_REQUEST['ajax'])) {
            $this->layout = 'ajax';
            Configure::write('debug', 0);
        }
        /*
          Configure::write('debug', 1);
          $this->out = '';
          $this->outCount = '';
          $name = $this->passedArgs;
          ksort($name);
          foreach ($name as $k => $v) {
          $this->out.=$k . "_" . $v . "_";
          if ($k <> 'page')
          $this->outCount.=$k . "_" . $v . "_";
          }

          $lang = Configure::read('Config.language');
          $this->langFix = '';
          if ($lang == _ENG_)
          $this->langFix = '_' . _ENG_;

          $this->set('lang', $lang);
          $this->set('langFix', $this->langFix);
          $zone = false;
          $zones = Configure::read('Catalog.allowedIPs');
          $zone = checkAllowedMasks($zones, $_SERVER['REMOTE_ADDR'], 1);
          if ($zone)
          $this->ImgPath = Configure::read('Catalog.imgPath');
          else
          $this->ImgPath = Configure::read('Catalog.imgPathInet');
         */
        View::set('blocks_top', '/maina/btop');
        View::set('blocks_right', '/maina/bright');
        View::set('blocks_m_top', '/maina/bmtop');
    }

    function BeforeRender() {
        //parent::BeforeRender();
        $lang = Configure::read('Config.language');
        $langFix = '';
        if ($lang == _ENG_)
            $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);
        $this->set('authUser', $this->authUser);
    }

    private function CheckForControl($control) {
        /*
        $UserPages = array();
          if (control in $vip) Redirect becomevip

         */
    }

    /* Pages */

    /**
     * Страница "станьте Вип-Пользователем"
     */
    function becomevip() {
        
    }

    /**
     * Страница стартовая
     */
    function index() {
        
    }

    /**
     * действие для вкладки Личного кабинета "Профиль"
     *
     * @param string $subAction	- субдействие
     * @param string $param		- дополнительные параметры
     */
    public function profile($subAction = '', $param = '') {
        
    }

    /**
     * Страница "История скаченного
     */
    public function userhistory() {
       $history = $this->UserDownloadHistory->query('SELECT * FROM userdownloadhistory WHERE user_id ='.$this->authUser['id'].' 
           LIMIT 50');
    }

    public function allfilms() {
        //$films = $this -> Films->find();
    }

    public function tags() {
        if ($_POST){
            
        }
        
        //$tags= $this->UserTags->query('SELECT * FROM `usertags` WHERE user_id='.$this->authUser['id'].' LIMIT 50');
    }

    public function wishlist($wish_id) {
        if ($_POST){         
            
            
        }        
        $wishlist=$this->UserWishlist->query('SELECT * FROM `userwishlist` WHERE user_id='.$this->authUser['id']);
    }

    public function requests() {
        
    }

    public function friendlist($page) {
        if (isset($_POST['friend'])){
            $fname = FILTER_VAR($_POST['friend'],FILTER_SANITIZE_STRING);
        }        
        
        // first find my requests
        $myfriends = $this->UserFriends->query('Select userfriends.friends_id From `userfriends`
            JOIN userfriends as userif ON userif.friend_id=userfriends.user_id  WHERE user_id='.$this->authUser['id']);
        
        $myOutRequests = $this->UserFriends->query('Select userfriends.friends_id From `userfriends`
            LEFT JOIN userfriends as userif ON userif.friend_id=userfriends.user_id  WHERE user_id='.$this->authUser['id']);
        
        $myInRequests = $this->UserFriends->query('Select userif.user_id From `userfriends`
            RIGHT JOIN userfriends as userif ON userif.friend_id=userfriends.user_id  WHERE user_id='.$this->authUser['id']);
                
        $this->set('myfriends',$myfriends);
        $this->set('myOutRequests',$myOutRequests);
        $this->set('myInRequests',$myInRequests);
    }
    
    public function im(){
        
    }
    
    
}