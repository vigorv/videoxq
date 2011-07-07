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
class MobileController extends AppController {

    var $name = 'mobile';
    //var $layout = 'mobile';
    var $layout = 'iphone';
    var $viewPath = 'mobile';
    var $helpers = array('Paginator', 'Form');
//    var $components = array('Auth');
    var $uses = array('Film', 'News');
    var $langFix = '';
    var $imgPath;
    var $out, $outCount;
    var $paginate = array(
        'limit' => 10
    );

    function Before() {
        parent::Before();
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
            $this->imgPath = Configure::read('Catalog.imgPath');
        else
            $this->imgPath = Configure::read('Catalog.imgPathInet');

        $this->out = '';
        $this->outCount = '';
        $name = $this->passedArgs;
        ksort($name);
        foreach ($name as $k => $v) {
            $this->out.=$k . "_" . $v . "_";
            if ($k <> 'page')
                $this->outCount.=$k . "_" . $v . "_";
        }
    }

    /**
     * найти фильмы с похожим названием
     *
     * @param string $title
     * @return array
     */
    private function SearchByTitle($title) {
        $films = array();      
       //pr($searchFor);
         if (!$films = Cache::read('Catalog.film_search_' . $title, 'searchres')) {
                $this->Film->recursive = 0;
                $this->Film->contain(array());
                $pagination['Film']['limit'] = 20;
                $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
                $pagination['Film']['sphinx']['index'] = array('films'); //ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ
                $pagination['Film']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
                $pagination['Film']['search'] = $title;
                $films = $this->Film->find('all', $pagination["Film"]);
                Cache::write('Catalog.film_search_' . $title, 'searchres');
            }
           return $films;
    }

    private function GetFilms($conditions=array()) {
        $conditions['Film.active'] = 1;
        $postFix = '';
        if ($this->isWS)
            $order = array('Film.modified' => 'desc');
        else {
            $order = array('Film.year' => 'desc');
            $conditions['Film.is_license'] = 1; //ВНЕШНИМ ПОКАЗЫВАЕМ ТОЛЬКО ЛИЦЕНЗИЮ
            $postFix = 'Licensed';
        }

        if (isset($this->passedArgs["sort"]))
            $order = array($this->passedArgs["sort"] => $this->passedArgs["direction"]);

        $films = false;
        //$films = Cache::read('Catalog.' . $postFix . 'list_' . $this->out, 'searchres');
        if ($films === false) //ЕСЛИ ЕЩЕ НЕ КЭШИРОВАЛ
            $films = $this->paginate('Film', $conditions);
        Cache::write('Catalog.' . $postFix . 'list_' . $this->out, $films, 'searchres');
        $this->set('films', $films);
        $this->set('imgPath', $this->imgPath);
    }

    function index() {
        $this->pageTitle = __('Video catalog', true);
        $this->GetFilms();
    }

    function view($id=null) {
        $lang = Configure::read('Config.language');
        if ($lang == _ENG_) {
            App::import('Vendor', 'IMDB_Parser', array('file' => 'class.imdb_parser.php'));
            App::import('Vendor', 'IMDB_Parser2', array('file' => 'class.imdb_parser2.php'));
            $parser = new IMDB_Parser2();
            $this->set('parser', $parser);
            $this->set('imdb_website', (empty($imdb_website) ? '' : $imdb_website));
        }
        if (!$id) {
            $this->Session->setFlash(__('Invalid Film', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!$film = Cache::read('Catalog.film_view_' . $id, 'media')) {
            $this->Film->recursive = 0;
            $this->Film->contain(array('FilmType',
                'Genre',
                'Thread',
                'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
                'Country',
                'FilmVariant' => array('FilmLink', 'FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
                'MediaRating',
                    )
            );
            $film = $this->Film->read(null, $id);
            Cache::write('Catalog.film_view_' . $id, $film, 'media');
        }
        if (!$film['Film']['active']) {
            $this->Session->setFlash(__('Invalid Film', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->pageTitle = __('Video catalog', true) . ' - ' . $film['Film']['title' . $this->langFix];
        $this->set('film', $film);
    }

    function search() {
        
        $words = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
        echo $words;
        $films = $this->SearchByTitle($words);
        $this->set('films',$films);       
        $this->render('films');
    }

    function news() {
        $this->set('news', $this->News->GetCatNews(-1, $dir, 5));
        $this->set('dir', $dir);
    }

    function films() {
        $this->pageTitle = __('Video catalog', true);
        $this->GetFilms();
    }

    function profile() {
        //getUser
    }

}

