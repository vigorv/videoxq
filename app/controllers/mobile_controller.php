<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mobile_controller
 *
 * @author snowing
 * @property FilmFast $FilmFast
 * @property UserFast $UserFast
 * @property Cookie $Cookie
 */
class MobileController extends AppController {

    var $name = 'mobile';
//var $layout = 'mobile';
    var $layout = 'iphone';
    var $viewPath = 'mobile';
    var $helpers = array('Paginator', 'Form', 'Html');
    var $components = array('Captcha', 'Email', 'Cookie', 'RequestHandler', 'ControllerList');
    var $uses = array('User', 'Group', 'FilmFast', 'UserFast', 'News', 'Media');
    var $langFix = '';
    var $lang;
    var $ImgPath;
    var $out, $outCount;
    var $paginate = array(
        'limit' => 10
    );
    var $page;
    var $per_page;
    var $page_count = 0;
    var $page_filter;

    function BeforeFilter() {
        parent::BeforeFilter();

        //echo env('HTTP_USER_AGENT');
        if (preg_match('/android/i', env('HTTP_USER_AGENT'))) {
            $this->set('android_webkit', true);
            View::set('android_webkit', true);
        }
        else
            View::set('android_webkit', false);

        $ajax = 0;
        if (isset($_REQUEST['ajax']) or (isset($_GET['ajax']))) {
            $this->layout = 'ajax';
            $ajax = 1;
            View::set('ajax_draw', 1);
            Configure::write('debug', 0);
        }
        // Configure::write('debug', 10);
        $this->out = '';
        $this->outCount = '';
        $name = $this->passedArgs;
        ksort($name);
        foreach ($name as $k => $v) {
            $this->out.=$k . "_" . $v . "_";
            if ($k <> 'page')
                $this->outCount.=$k . "_" . $v . "_";
        }

        $this->lang = Configure::read('Config.language');
        $this->langFix = '';
        if ($this->lang == _ENG_)
            $this->langFix = '_' . _ENG_;

        $zone = false;
        $zones = Configure::read('Catalog.allowedIPs');
        $zone = checkAllowedMasks($zones, $_SERVER['REMOTE_ADDR'], 1);
        $page = 1;
        $per_page = 10;
        $ajaxmode = 0;
        if (isset($_GET['page'])) {
            $page = filter_var($_GET['page'], FILTER_VALIDATE_INT);
            if ($ajax)
                $ajaxmode = 1;
        }
        if ($page > 0)
            $this->page = $page;
        else
            $this->page = 1;
        if (isset($_GET['per_page']))
            $per_page = filter_var($_GET['per_page'], FILTER_VALIDATE_INT);
        if (($per_page > 0) && ($per_page < 100))
            $this->per_page = $per_page;
        else
            $this->per_page = 10;
        if (isset($_GET['filter']))
            $this->page_filter = filter_var($_GET['filter'], FILTER_SANITIZE_STRING);
        else
            $this->page_filter = '';
        View::set('page_filter', $this->page_filter);
        View::set('page_link', '/' . $this->params['controller'] . '/' . $this->params['action']);
        View::set('first_time',$this->first_time);
        $this->set('ajaxmode', $ajaxmode);
        if ($zone)
            $this->ImgPath = Configure::read('Catalog.imgPath');
        else
            $this->ImgPath = Configure::read('Catalog.imgPathInet');
    }

    function BeforeRender() {
        $this->set('imgPath', $this->ImgPath);
        $this->set('page', $this->page);
        $this->set('lang', $this->lang);
        $this->set('langFix', $this->langFix);
    }

    /*
     * Pages
     */

    function index() {
        $this->pageTitle = __('Video catalog', true);
        View::set('hide_search_bar', true);
        $films = $this->FilmFast->GetFilms(array('lic' => 1, 'variant' => 13, 'order' => 'Year', 'direction' => 'DESC'), 100, $this->page, $this->per_page);
        $count = $this->FilmFast->GetFilmsCount(array('lic' => 1, 'variant' => 13, 'order' => 'Year', 'direction' => 'DESC'));
        //$this->autoRender = false;
        $this->set('films', $films);
        $this->set('count', $count);
        //$this->render('index');
    }

    function search() {
        $words = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
        $films = $this->FilmFast->SearchByTitle($words, 1, $this->page, $this->per_page);
        $count = $this->FilmFast->SearchByTitleCount($words, 1);

        $this->set('films', $films);
        $this->set('count', $count);
        //$this->render('films');
    }

    function news($id=null) {
        if (!$id) {
            $this->set('news', $this->News->GetCatNews(0, $dir, 5));
            $this->set('dir', $dir);
        } else {
            $this->set('news', $this->News->find('first', array('conditions' => array('News.id' => $id))));
            $this->render('news_view');
        }
    }

    function genres($id=null) {
        if (!$id) {
            $genres = $this->FilmFast->GetFullGenresList(1, 13);

            $this->set('genres', $genres);
        } else {
            $this->pageTitle = __('Video catalog', true);
            $genre = $this->FilmFast->getGenreInfo($id);
            $this->set('genre', $genre);
            $cond = array('lic' => 1, 'variant' => 13, 'order' => 'Film.year', 'direction' => "DESC", 'genre_id' => $id, $this->page, $this->per_page);
            $count = $this->FilmFast->GetFilmsCount($cond);
            $films = $this->FilmFast->GetFilms($cond, 1, $this->page, $this->per_page);
            $this->set('films', $films);
            $this->set('count', $count);
            $this->render('films');
        }
    }

    function films($id=null) {
        if (!$id) {
            $this->pageTitle = __('Video catalog', true);
            $films = $this->FilmFast->GetFilms(array('lic' => 1, 'variant' => 13, 'order' => 'Year', 'direction' => 'DESC'), 1000, $this->page, $this->per_page);
            $count = $this->FilmFast->GetFilmsCount(array('lic' => 1, 'variant' => 13, 'order' => 'Year', 'direction' => 'DESC'));
            //    $films = $this->FilmFast->GetFilms(array('lic' => 1, 'variant' => 13, 'order' => 'RAND()'), 10);
            $this->set('films', $films);
            $this->set('count', $count);
            $this->render('films');
        } else {
            $id = (int) $id;
            $film = $this->FilmFast->GetFilm($id);

            if ($film) {
                if ($this->lang == _ENG_) {
                    $imdb_website = Cache::read('Catalog.film_imdbinfo_' . $id, 'searchres');
                    if (empty($imdb_website)) {
                        if (!empty($film['Film']['imdb_id'])) {
                            $fn = 'http://imdb.com/title/' . $film['Film']['imdb_id'];
                            $imdb_website = file_get_contents($fn);
                            Cache::write('Catalog.film_imdbinfo_' . $id, $imdb_website, 'searchres');
                        }
                    }
                    App::import('Vendor', 'IMDB_Parser', array('file' => 'class.imdb_parser.php'));
                    App::import('Vendor', 'IMDB_Parser2', array('file' => 'class.imdb_parser2.php'));
                    $parser = new IMDB_Parser2();
                    $this->set('parser', $parser);
                    $this->set('imdb_website', (empty($imdb_website) ? '' : $imdb_website));
                }
                $this->pageTitle = __('Video catalog', true) . ' - ' . $film['Film']['title' . $this->langFix];
                $this->set('files', $this->Media->findfiles($film['Film']['id']));
                $this->set('film', $film);
                $this->set('authUser', $this->authUser);
                $this->render('view');
            }
        }
    }

    function profile() {
        if (!$this->authUser['userid']) {
            $this->render('login');
        } else {
            $this->set('user', $this->authUser);
            isset($_GET['sub']) ? $sub = $_GET['sub'] : $sub = '';
            switch ($sub) {
                case 'history': {
                        $this->render('history');
                        break;
                    }
                case 'settings': {
                        $this->render('settings');
                        break;
                    }
                default: {
                        
                    }
            }
        }
    }

    function login() {
        if ($_POST) {
            $mail = filter_var($_POST['e-mail'], FILTER_SANITIZE_EMAIL);
            $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            if ($mail <> '') {
                $login = $this->UserFast->Login($mail, $pass);
                if ($login) {
                    $this->Session->write($this->Auth2->sessionKey, $login);
                    $this->_loggedIn = true;
                    $this->redirect('/mobile/profile');
                }
                else
                    $this->set('error', 'Auth Failed');
                //user error count login ++;
            }
        }
    }

    function logout() {
        if ($_POST['logout']) {
            $this->UserFast->logout();
            $this->Vb->clearCookies();
            $this->Cookie->del('Auth.User');

            //УДАЛЯЕМ КУКИ БЕЗ ДОМЕНА
            Configure::write('App.cookieDomain', '');
            $this->Cookie->del('Auth.User');

            $this->redirect($this->Auth2->logout());
            $this->redirect('/mobile');
        }
    }

    function ver() {
        switch ($_GET['id']) {
            case 1:
                $this->Cookie->write('version', 'desk');
                break;
            case 2:
                $this->Cookie->write('version', 'mob');
                break;
        }
        $this->redirect('/');
    }

    function old(){
      $this->layout='old_mobile';     
    }
    
}

