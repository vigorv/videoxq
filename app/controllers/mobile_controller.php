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
    var $layout = 'mobile';
    var $viewPath = 'mobile';
    var $helpers = array('PageNavigator');
    var $components = array();
    var $uses = array('Film');

    var $langFix='';

    //var $helpers = array('Html', 'Form', 'Rss', 'Text', 'PageNavigator','App');
    //var $components = array('Captcha', 'Cookie', 'RequestHandler'/*,'DebugKit.toolbar'*/);
    /* var $uses = array('Film', 'Basket', 'FilmComment', 'SearchLog', 'Feedback', 'Thread', 'Vbpost', 'Vbgroup',
      'Forum', 'Userban', 'Transtat', 'Genre', 'Bookmark', 'CybChat', 'Smile', 'Migration',
      //'DlePost',
      'SimilarFilm','User', 'Zone', 'Server', 'Page',
      'OzonProduct'
      );
     */

    function Before() {
        parent::Before();
        $lang = Configure::read('Config.language');
        $this->langFix = '';
        if ($lang == _ENG_)
            $this->langFix = '_' . _ENG_;
        $this->set('lang', $lang);
        $this->set('langFix', $this->langFix);
    }

    private function transCyrChars($txt, $reverse = false) {
        $result = '';
        $t = array(//ТРАНСЛИТ
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'z',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shj', 'ъ' => '', 'ы' => 'i', 'ь' => 'j', 'э' => 'e', 'ю' => 'ju',
            'я' => 'ja'
        );
        $t = array(//РАСКЛАДКА
            'а' => 'f', 'б' => ',', 'в' => 'd', 'г' => 'u', 'д' => 'l', 'е' => 't', 'ё' => '`', 'ж' => ';',
            'з' => 'p', 'и' => 'b', 'й' => 'q', 'к' => 'r', 'л' => 'k', 'м' => 'v', 'н' => 'y', 'о' => 'j',
            'п' => 'g', 'р' => 'h', 'с' => 'c', 'т' => 'n', 'у' => 'e', 'ф' => 'a', 'х' => '[', 'ц' => 'w',
            'ч' => 'x', 'ш' => 'i', 'щ' => 'o', 'ъ' => ']', 'ы' => 's', 'ь' => 'm', 'э' => "'", 'ю' => '.',
            'я' => 'z'
        );
        $t = array_flip($t); //ДЛЯ МАССИВА ПО РАСКЛАДКЕ

        if ($reverse) {
            $t = array_flip($t); //ОБРАТНЫЙ ПЕРЕВОД
        }

        $t1 = array(); //массив в верхнем регистре
        foreach ($t as $key => $value)
            $t1[mb_strtoupper($key)] = mb_strtoupper($value);

        $searchCnt = mb_strlen($txt);
        for ($i = 0; $i < $searchCnt; $i++) {
            $c = mb_substr($txt, $i, 1);
            if (isset($t[$c])) {
                $result .= $t[$c];
                continue;
            } elseif (isset($t1[$c])) {
                $result .= $t1[$c];
                continue;
            } else {
                $result .= $c;
            }
        }
        return $result;
    }

    private function GetFilms() {
        $this->Film->recursive = 1;
        if ($this->isWS)
            $order = array('Film.modified' => 'desc');
        else
            $order=array('Film.year' => 'desc');

        if (isset($this->passedArgs["sort"]))
            $order = array($this->passedArgs["sort"] => $this->passedArgs["direction"]);

        $conditions = array();
        $conditions['Film.active'] = 1;
        $postFix = '';

        if (!$this->isWS) {
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
                'limit' => 5));

        if (!empty($this->params['named']['genre'])) {
            $pagination['Film']['contain'][] = 'FilmsGenre';
            $pagination['Film']['group'] = 'Film.id';
            $genres = $this->params['named']['genre'];
            if (strpos($this->params['named']['genre'], ',') !== false)
                $genres = explode(',', $this->params['named']['genre']);
            //$pagination['Film']['conditions'][] = array('FilmsGenre.genre_id' => $genres);
            $pagination['Film']['conditions']['FilmsGenre.genre_id'] = $genres;
            $pagination['Film']['sphinx']['filter'][] = array('genre_id', $genres);
            $this->Film->bindModel(array('hasOne' => array(
                    'FilmsGenre' => array('foreignKey' => 'film_id')
                    )), false);
        }

        if (!empty($this->params['named']['is_license']))
            $pagination['Film']['conditions']['Film.is_license'] = 1;

        if (!empty($this->params['named']['year_start']))
            $pagination['Film']['conditions']['Film.year >='] = $this->params['named']['year_start'];

        if (!empty($this->params['named']['year_end']))
            $pagination['Film']['conditions']['Film.year <='] = $this->params['named']['year_end'];

        if (!empty($this->params['named']['imdb_start']))
            $pagination['Film']['conditions']['Film.imdb_rating >='] = $this->params['named']['imdb_start'];

        if (!empty($this->params['named']['imdb_end']))
            $pagination['Film']['conditions']['Film.imdb_rating <='] = $this->params['named']['imdb_end'];

        if (!empty($this->params['named']['country'])) {
            $pagination['Film']['sphinx']['filter'][] = array('country_id', $this->params['named']['country']);
            $pagination['Film']['contain'][] = 'CountriesFilm';
            $pagination['Film']['conditions']['CountriesFilm.country_id'] = $this->params['named']['country'];
            $this->Film->bindModel(array('hasOne' => array(
                    'CountriesFilm' => array(
                        'foreignKey' => 'film_id'
                    )
                    )), false);
        }
        if (!empty($this->params['named']['type'])) {
            $condition = 'and';
            $type = $this->params['named']['type'];

            if (strpos($this->params['named']['type'], '!') !== false) {
                $condition = 'not';
                $type = str_replace('!', '', $type);
            }
            if (strpos($type, ',') !== false)
                $type = explode(',', $type);

            $pagination['Film']['sphinx']['filter'][] = array('film_type_id', $type, $condition == 'not' ? true : false);
            $find = array($condition => array('FilmType.id' => $type));
            $pagination['Film']['conditions'][] = $find;
        }

        if (!empty($this->params['named']['is_license'])) {
            $condition = 'and';
            $find = array($condition => array('Film.is_license' => 1));
            //$pagination['Film']['conditions'][] = $find;
            $pagination['Film']['conditions']['Film.is_license'] = 1;
        }

        $vtInfo = $this->Film->FilmVariant->VideoType->getVideoTypesWithFilmCount();
        $this->set('vtInfo', $vtInfo);
        if (!empty($this->params['named']['vtype'])) {
            $condition = 'and';
            $vtype = intval($this->params['named']['vtype']);
            $pagination['Film']['sphinx']['filter'][] = array('video_type_id', $vtype, false);
            $find = array($condition => array('FilmVariant.video_type_id' => $vtype));
            $pagination['Film']['conditions'][] = $find;
            $pagination['Film']['group'][] = 'Film.id';
            $this->Film->bindModel(array('hasOne' => array(
                    'FilmVariant' => array(
                        'className' => 'FilmVariant',
                        'foreignKey' => 'film_id',
                    )
                    )), false);
        }

        $sort = ', hits DESC';
        if (!empty($this->params['named']['sort'])) {
            $sort = explode('.', $this->params['named']['sort']);
            $sort = ', ' . $sort[1] . ' DESC';
        }

        if (!empty($this->passedArgs['page'])) {
            $pagination['Film']['page'] = $this->passedArgs['page'];
            $pagination['Film']['limit'] = $pagination['Film']['limit'];
            $pagination['Film']['offset'] = ($pagination['Film']['page'] - 1) * $pagination['Film']['limit'];
        }
        $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
        $pagination['Film']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC' . $sort);
        $pagination['Film']['sphinx']['index'] = array('films'); //ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ



        if (!empty($this->passedArgs['page']))
            $this->pageTitle .= ' ' . __('page', true) . ' ' . $this->passedArgs['page'];
        /*
          echo'<pre>';
          var_dump($pagination);
          echo'</pre>';
          // */
        $out = '';
        $outCount = '';
        $name = $this->passedArgs;
        //$name=array();
        ksort($name);
        foreach ($name as $k => $v) {


            $out.=$k . "_" . $v . "_";
            if ($k <> 'page')
                $outCount.=$k . "_" . $v . "_";
        }
//*

        if (!empty($this->passedArgs['page'])) {
            $pagination['Film']['offset'] = (abs($this->passedArgs['page']) - 1) * $pagination["Film"]['limit'];
        }
//pr($pagination);

        $films = false;
        $posts = array();
        //if (!$isFirstPage)
        $films = Cache::read('Catalog.' . $postFix . 'list_' . $out, 'searchres');
        if (empty($search)) {
            unset($pagination['Film']['sphinx']); //СФИНКС ВСЕ РАВНО НЕ БУДЕТ ИСКАТЬ ПО ПУСТОЙ СТРОКЕ
        }

        if ($films === false) {//ЕСЛИ ЕЩЕ НЕ КЭШИРОВАЛИ
            $films = $this->Film->find('all', $pagination["Film"]);
            //*

            if (empty($films)) {
                if (!empty($translit)) {
                    if (!isset($this->params['named']['istranslit'])) {
                        $this->redirect(array('action' => 'index',
                            'search' => $translit,
                            'istranslit' => 1,
                            'controller' => 'mobile'));
                    } else {
                        if ($isTranslit) {
                            $this->redirect(array('action' => 'index',
                                'search' => $translit,
                                'istranslit' => 0,
                                'controller' => 'mobile'));
                        }
                    }
                    $pagination['Film']['search'] = $translit;
                    $films = $this->Film->find('all', $pagination["Film"]);
                    if ($films) {
                        $this->params['named']['search'] = $translit;
                        $transData = array('Transtat' => array('created' => date('Y-m-d H:i:s'), 'search' => mb_substr($translit, 0, 255)));
                        //$this->Transtat->useDbConfig = 'productionMedia';
                        $this->Transtat->create();
                        $this->Transtat->save($transData);
                    }
                }
            }
            //*/
            //КЭШИРУЕМ ДАЖЕ ЕСЛИ НИЧЕГО НЕ НАЙДЕНО
            //if (((isset($this->passedArgs['page'])) && $films) || isset($this->passedArgs['search']))

            if (!$isFirstPage) {
//echo 'RESULT CACHED';
//exit;
                Cache::write('Catalog.' . $postFix . 'list_' . $out, $films, 'searchres');
            }
        }
        $this->set('pageCount', 10);
        $this->set('films', $films);
    }

    function index() {
        $this->pageTitle = __('Video catalog', true);
        $this->GetFilms();
    }

    function view($id=null) {

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
                    //'FilmComment' => array('order' => 'FilmComment.created ASC',
                    //'conditions' => array('FilmComment.hidden' => 0))
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
        
    }

}

?>
