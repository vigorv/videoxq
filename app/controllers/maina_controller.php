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
    var $uses = array('Direction', 'News');

    function BeforeFilter() {
        parent::beforeFilter();
        if (isset($_GET['ajax'])) {
            $this->layout = 'ajax';
            Configure::write('debug', 1);
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

    private function CheckForControl($control){
        /*
        if (control in $vip) Redirect becomevip
        
        */
    }
    
    
    function becomevip(){
        
        
        
    }
    
    
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
    
    public function userhistory(){
        
        
    }

}
