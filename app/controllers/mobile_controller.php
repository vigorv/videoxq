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
    var $helpers = array('Paginator', 'Form', 'Html');
    var $components = array('Captcha', 'Email', 'Cookie', 'RequestHandler', 'ControllerList');
    var $uses = array('User', 'Group', 'Film', 'News', 'Media');
    var $langFix = '';
    var $ImgPath;
    var $out, $outCount;
    var $paginate = array(
        'limit' => 10
    );
    var $is_logged = false;


    function BeforeFilter() {
        parent::BeforeFilter();
        if (isset($_GET['ajax'])) $this->layout='ajax';

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
            $this->Film->contain(array('FilmType', 'Genre',
                'Thread',
                'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
                'Country',
                'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
                'MediaRating',
                    )
            );
            $this->Film->recursive = 1;
            $pagination['Film']['limit'] = 20;
            $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Film']['sphinx']['index'] = array('films'); //ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ
            $pagination['Film']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['Film']['search'] = $title;
            $films = $this->Film->find('all', $pagination["Film"]);

            $this->set('imgPath', $this->ImgPath);
            Cache::write('Catalog.film_search_' . $title, 'searchres');
        }
        return $films;
    }

    /*
     * get list of films
     * @param array $cond
     * @return array
     */

    protected function GetFilms($conditions=array()) {

        /*
          if (!$films = Cache::read('Catalog.film_search_' . $title, 'searchres')) {
          $this->Film->contain(array('FilmType', 'Genre',
          'Thread',
          'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
          'Country',
          'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
          'MediaRating',
          )
          );
         */

        if (!$this->isWS) {
            $license = ' and Film.is_license = 1';
        } else
            $license = '';

        $films = $this->Film->query('SELECT * FROM films as Film
                            INNER JOIN film_variants  as FilmVariant ON FilmVariant.film_id = Film.id
                            LEFT JOIN film_pictures as FilmPicture ON FilmPicture.film_id = Film.id
                            LEFT JOIN media_ratings as MediaRating on MediaRating.object_id = Film.id
                           Where Film.active = 1 ' . $license . ' and FilmVariant.video_type_id = 13
                           and FilmPicture.type = "smallposter"
                           and MediaRating.type = "film"
                           ORDER BY Film.year Limit 20');


        foreach ($films as &$film) {
            $gr = $this->Film->query('SELECT genres.title,genres.title_imdb FROM films_genres
                            LEFT JOIN genres on genres.id = films_genres.genre_id
                            WHERE films_genres.film_id =' . $film['Film']['id']);
            foreach ($gr as $genre)
                $film['Genre'][] = $genre['genres'];
        }






        /* $conditions['Film.active'] = 1;
          $postFix = '';
          //
          if ($this->isWS)
          $order = array('Film.modified' => 'desc');
          else {
          $order = array('Film.year' => 'desc');
          $conditions['Film.is_license'] = 1; //ВНЕШНИМ ПОКАЗЫВАЕМ ТОЛЬКО ЛИЦЕНЗИЮ
          $postFix = 'Licensed';
          }
          $joins = array('table' => 'film_variants', 'alias' => 'FilmVariant', 'type' => 'inner', 'foreignKey' => false,
          'conditions' => array('FilmVariant.video_type_id' => 13));

          if (isset($this->passedArgs["sort"]))
          $order = array($this->passedArgs["sort"] => $this->passedArgs["direction"]);

          $films = false;
          //$films = Cache::read('Catalog.' . $postFix . 'list_' . $this->out, 'searchres');
          if ($films === false) {//ЕСЛИ ЕЩЕ НЕ КЭШИРОВАЛ
          $films = $this->Film->find('all', array('conditions' => $conditions, 'joins' => array($joins)));
          Cache::write('Catalog.' . $postFix . 'list_' . $this->out, $films, 'searchres');
          }
         *
         */
        $this->set('films', $films);

        $this->set('imgPath', $this->ImgPath);
    }

    /*
     * Pages
     */

    function index() {

        $this->pageTitle = __('Video catalog', true);
        $this->GetFilms();
    }

    function search() {
        $words = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
        $films = $this->SearchByTitle($words);
        $this->set('films', $films);
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

    function films($id=null) {
        if (!$id) {
            $this->pageTitle = __('Video catalog', true);
            $this->GetFilms();
        } else {
            $lang = Configure::read('Config.language');
            if ($lang == _ENG_) {
                App::import('Vendor', 'IMDB_Parser', array('file' => 'class.imdb_parser.php'));
                App::import('Vendor', 'IMDB_Parser2', array('file' => 'class.imdb_parser2.php'));
                $parser = new IMDB_Parser2();
                $this->set('parser', $parser);
                $this->set('imdb_website', (empty($imdb_website) ? '' : $imdb_website));
            }

            //  if (!$film = Cache::read('Catalog.film_view_mob_' . $id, 'media')) {
          /*  $film = $this->Film->query('SELECT * FROM films as Film
                            LEFT JOIN film_variants  as FilmVariant ON FilmVariant.film_id = Film.id
                            LEFT JOIN media_ratings as MediaRating on MediaRating.object_id = Film.id
                           Where Film.id = ' . $id . '
                           and Film.active = 1  and FilmVariant.video_type_id = 13
                           and MediaRating.type = "film"
                           ORDER BY Film.year Limit 1');
            if (empty($film))
                return null;*/

          //  if (!$film = Cache::read('Catalog.film_view_' . $id, 'media')) {
                $this->Film->recursive = 0;
                $this->Film->contain(array('FilmType',
                    'Genre',
                    'Thread',
                    'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
                    'Country',
                    'FilmVariant'=>
                        array('conditions'=>array('video_type_id'=>13),
                            'FilmLink', 'FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
                    'MediaRating',
                        )
                );

               /* $this->Film->FilmVariant->contain(
                        array('conditions'=>array('video_type_id'=>13),
                            'FilmLink', 'FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')));
                */
                $film = $this->Film->read(null, $id);



                Cache::write('Catalog.film_view_' . $id, $film, 'media');
           // }
            if (!$film['Film']['active']) {
                $this->Session->setFlash(__('Invalid Film', true));
                $this->redirect(array('action' => 'films'));
            }
            $geoInfo = $this->Session->read('geoInfo');
            $geoIsGood = false;
            if (!empty($geoInfo)) {
                if (!empty($geoInfo['Geoip']['region_id']) || !empty($geoInfo['Geoip']['city_id'])) {
                    //ТК база GeoIP СОДЕРЖИТ ТОЛЬКО РОССИЙСКИЕ АДРЕСА
                    //ЛИЦЕНЗИЯ ДЕЙСТВУЕТ НА ВСЮ РОССИЮ
                    $geoIsGood = $film['Film']['is_license'];
                }
            }

            $this->pageTitle = __('Video catalog', true) . ' - ' . $film['Film']['title' . $this->langFix];
            $this->set('files', $this->Media->findfiles($film['Film']['id']));
            $this->set('film', $film);
            $this->set('imgPath', $this->ImgPath);
            $this->set('authUser', $this->authUser);
            $this->render('view');
        }
    }

    function profile() {
        if (!$this->authUser['userid']) {
            $this->render('login');
        } else {
            $this->set('user', $this->authUser);
        }
    }

    function logout() {
        if ($_POST['logout']) {
            $this->Cookie->del('Auth.User');
            Configure::write('App.cookieDomain', '');
            $this->Cookie->del('Auth.User');
            $this->Auth2->logout();
            $this->redirect('/mobile');
        }
    }

}

