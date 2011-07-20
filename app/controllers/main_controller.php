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
class MainController extends AppController {

    var $name = 'Main';
    //var $layout ='default';
    var $viewPath = 'main';
    var $helpers = array();
    var $components = array();
    var $uses = array('Direction','News');

//  var $helpers = array('Html', 'Form', 'Rss', 'Text', 'PageNavigator','App');
    //var $components = array('Captcha', 'Cookie', 'RequestHandler'/*,'DebugKit.toolbar'*/);
    /* var $uses = array(
      'Direction','News',

      'Film', 'Basket', 'FilmComment', 'SearchLog', 'Feedback', 'Thread', 'Vbpost', 'Vbgroup',
      'Forum', 'Userban', 'Transtat', 'Genre', 'Bookmark', 'CybChat', 'Smile', 'Migration',
      //'DlePost',
      'SimilarFilm','User', 'Zone', 'Server', 'Page',
      'OzonProduct'
      );
     */
    
    private function film_list() {
        Configure::write('debug', 0);
        $films = array();
        $pagination = array('Film' => array('contain' =>
                array('FilmType',
                    'Genre',
                    'FilmVariant' => array('VideoType'),
                    'FilmPicture' => array('conditions' => array('type' => 'smallposter')),
                    'Country',
                    'Person' => array('conditions' => array('FilmsPerson.profession_id' => array(1, 3, 4))),
                    'MediaRating'),
                'order' => 'Film.modified DESC',
                'conditions' => array('Film.active' => 1),
                'group' => 'Film.id',
                'limit' => 30));
        $films = $this->Film->find('all', $pagination["Film"]);

        if (isset($this->params['requested'])) {
            return $films;
        }
    }

    private function GetView($view, $path, $paramname=false, $params=false) {
        $this->autoRender = false;
        //$view = new View($this, false);
        if (is_array($params)) {
            $view->set($paramname, $params);
        }
        $view->layout = false;

        $view->viewPath = 'main';
        return $view->render($path);
    }

    private function GetFilms() {
        $view = new View($this, false);

        if (empty($view->passedArgs['direction']))
            $view->passedArgs['direction'] = 'desc';

        $lang = Configure::read('Config.language');
        $langFix = '';
        if ($lang == _ENG_)
            $langFix = '_' . _ENG_;
        $view->set('lang', $lang);
        $view->set('langFix', $langFix);


        $this->pageTitle = __('Video catalog', true);
        $this->Film->recursive = 1;
        if ($this->isWS)
            $order = array('Film.modified' => 'desc');
        else
            $order=array('Film.year' => 'desc');

        $conditions = array();
        $conditions['Film.active'] = 1;
        $postFix = '';
        if (!$this->isWS && empty($this->params['named']['search'])) {
            $conditions['Film.is_license'] = 1; //ВНЕШНИМ ПОКАЗЫВАЕМ ТОЛЬКО ЛИЦЕНЗИЮ
            $postFix = 'Licensed';
        }
        $pagination = array('Film' => array('contain' =>
                array('FilmType',
                    'Genre',
                    'FilmVariant' => array('VideoType'),
                    'FilmPicture' => array('conditions' => array('type' => 'smallposter')),
                    'Country',
                    'Person' => array('conditions' => array('FilmsPerson.profession_id' => array(1, 3, 4))),
                    'MediaRating'),
                'order' => $order,
                'conditions' => $conditions,
                'group' => 'Film.id',
                'limit' => 3));
        $films = $this->Film->find('all', $pagination["Film"]);

        return $this->GetView($view, 'block/films', 'films', $films);
    }

    function GetCatNews($pos) {
        $lang = $this->Session->read("language");
        $view = new View($this, false);
        $dir=array();
        $lst = $this -> News ->GetCatNews($pos,$dir);
        $view->set('dir',$dir);
        return $this->GetView($view, 'block/catnews', 'news', $lst);
    }

    function index() {
        $this->set('block_films', $this->GetFilms());
        $this->set('block_left_news', $this->GetCatNews(0));
        $this->set('block_left_news_B', $this->GetCatNews(1));
        //$this->set('block_films','test');
        // $this->render(false);
        $this->autoRender = true;
    }

}