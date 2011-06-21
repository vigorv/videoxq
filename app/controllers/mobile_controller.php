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

   var $name='mobile';
   var $layout='mobile';
   var $viewPath = 'mobile';
   var $helpers = array('Html', 'Form', 'Rss', 'Text', 'PageNavigator','App');
   var $components = array('Captcha', 'Cookie', 'RequestHandler'/*,'DebugKit.toolbar'*/);
   var $uses = array('Film', 'Basket', 'FilmComment', 'SearchLog', 'Feedback', 'Thread', 'Vbpost', 'Vbgroup',
    'Forum', 'Userban', 'Transtat', 'Genre', 'Bookmark', 'CybChat', 'Smile', 'Migration',
    //'DlePost',
    'SimilarFilm','User', 'Zone', 'Server', 'Page',
    'OzonProduct'
    );

     function index(){
        $this->pageTitle = __('Video catalog', true);
        $this->Film->recursive = 1;
        if (empty($this->passedArgs['direction'])) $this->passedArgs['direction'] = 'desc';

        if (!empty($this->data['Film']['searchsimple']))
        {
            extract($this->data, EXTR_PREFIX_SAME, 'post');
            $this->redirect(array('action' => 'index',
                                  'search' => urlencode($Film['searchsimple'])));
        }

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);

        if (!empty($this->data['Film']))
        {
            extract($this->data, EXTR_PREFIX_SAME, 'post');

            $redirect = $this->data['Film'];
            $redirect['direction'] = $Film['direction'] ? 'desc' : 'asc';
            $redirect['action'] = 'index';
            $redirect['search'] = urlencode($Film['search']);
            $redirect['ex'] = 'yes';

            $this->redirect($redirect);
        }

        $isFirstPage = false;//ДЛЯ СЛУЧАЙНОЙ ВЫБОРКИ ПЕРВОЙ СТРАНИЦЫ ОСОБЫЕ ДЕЙСТВИЯ
//        if ($this->isWS)
        if (false)
        {
        	$order=array('Film.modified' => 'desc');
        }
        else
        {
        	$order=array('Film.year' => 'desc');
        }

        if (isset($this->passedArgs["sort"]))
        {
	        $order=array($this->passedArgs["sort"] => $this->passedArgs["direction"]);
        }
/*
        if (isset($this->passedArgs["sort"]))
        {
	        $order=array($this->passedArgs["sort"] => $this->passedArgs["direction"]);
        }
        else
        {
        	$order=array('Film.modified' => 'desc');
	        if ((empty($this->passedArgs['page']) || $this->passedArgs['page'] == 1) && empty($this->passedArgs["search"]) && empty($this->passedArgs["ex"]) && empty($this->passedArgs["type"]) && empty($this->passedArgs["is_license"]))
	        {
	        	$isFirstPage = true;
	        	$rIds = array();
	        	$maxFilmId = $this->Film->getMaxFilmId();
	        	for ($i = 0; $i < 40; $i++) //ВЫБИРАЕМ СЛУЧАЙНО С ЗАПАСОМ
	        	{
	        		$rIds[] = mt_rand(1, $maxFilmId);
	        	}
	        	//$rIds = $this->Film->getRandomIds();
	        }
        }
*/
		$conditions = array();
		$conditions['Film.active'] = 1;
		$postFix = '';
		//if (!$this->isWS && empty($this->params['named']['search']))
                if (empty($this->params['named']['search']))
		{
			$conditions['Film.is_license'] = 1;//ВНЕШНИМ ПОКАЗЫВАЕМ ТОЛЬКО ЛИЦЕНЗИЮ
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
/*
		if ($isFirstPage)
		{
            $pagination['Film']['conditions'][] = array('Film.id' => $rIds);
//	        $order=array('rand()');
		}
*/
        if (!empty($this->params['named']['genre']))
        {
            $pagination['Film']['contain'][] = 'FilmsGenre';
            $pagination['Film']['group'] = 'Film.id';
            $genres = $this->params['named']['genre'];
            if (strpos($this->params['named']['genre'], ',') !== false)
                $genres = explode(',', $this->params['named']['genre']);
            //$pagination['Film']['conditions'][] = array('FilmsGenre.genre_id' => $genres);
            $pagination['Film']['conditions']['FilmsGenre.genre_id'] = $genres;
            $pagination['Film']['sphinx']['filter'][] = array('genre_id', $genres);
            $this->Film->bindModel(array('hasOne' => array(
                                          'FilmsGenre' => array(
                                           'foreignKey' => 'film_id'
                                          )
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

        if (!empty($this->params['named']['country']))
        {
            $pagination['Film']['sphinx']['filter'][] = array('country_id', $this->params['named']['country']);
            $pagination['Film']['contain'][] = 'CountriesFilm';
            $pagination['Film']['conditions']['CountriesFilm.country_id'] = $this->params['named']['country'];
            $this->Film->bindModel(array('hasOne' => array(
                                          'CountriesFilm' => array(
                                           'foreignKey' => 'film_id'
                                          )
                                        )), false);

        }
        if (!empty($this->params['named']['type']))
        {
            $condition = 'and';
            $type = $this->params['named']['type'];

            if (strpos($this->params['named']['type'], '!') !== false)
            {
                $condition = 'not';
                $type = str_replace('!', '', $type);
            }
            if (strpos($type, ',') !== false)
                $type = explode(',', $type);

            $pagination['Film']['sphinx']['filter'][] = array('film_type_id', $type, $condition == 'not' ? true : false);
            $find = array($condition => array('FilmType.id' => $type));
            $pagination['Film']['conditions'][] = $find;
        }

        if (!empty($this->params['named']['is_license']))
        {
            $condition = 'and';
            $find = array($condition => array('Film.is_license' => 1));
            //$pagination['Film']['conditions'][] = $find;
            $pagination['Film']['conditions']['Film.is_license'] = 1;
        }

        $vtInfo = $this->Film->FilmVariant->VideoType->getVideoTypesWithFilmCount();
        $this->set('vtInfo', $vtInfo);
        if (!empty($this->params['named']['vtype']))
        {
            $condition = 'and';
            $vtype = intval($this->params['named']['vtype']);
            $pagination['Film']['sphinx']['filter'][] = array('video_type_id', $vtype, false);
            $find = array($condition => array('FilmVariant.video_type_id' => $vtype));
            $pagination['Film']['conditions'][] = $find;
            $pagination['Film']['group'][] = 'Film.id';
            $this->Film->bindModel(array('hasOne' => array(
                                          'FilmVariant' => array(
                                           'className'	=> 'FilmVariant',
                                           'foreignKey' => 'film_id',
                                          )
                                        )), false);
        }

        if (!empty($this->params['named']['search']))
        {
            //$pagination['Film']['group'] = 'Film.id';
            $search = (!empty($this->params['named']['search'])) ? trim($this->params['named']['search']) : '';

			function transCyrChars($txt, $reverse = false)
			{
				$result = '';
				$t = array(//ТРАНСЛИТ
					'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'jo','ж'=>'zh',
					'з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o',
					'п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'z',
					'ч'=>'ch','ш'=>'sh','щ'=>'shj','ъ'=>'','ы'=>'i','ь'=>'j','э'=>'e','ю'=>'ju',
					'я'=>'ja'
				 );
				$t = array(//РАСКЛАДКА
					'а'=>'f','б'=>',','в'=>'d','г'=>'u','д'=>'l','е'=>'t','ё'=>'`','ж'=>';',
					'з'=>'p','и'=>'b','й'=>'q','к'=>'r','л'=>'k','м'=>'v','н'=>'y','о'=>'j',
					'п'=>'g','р'=>'h','с'=>'c','т'=>'n','у'=>'e','ф'=>'a','х'=>'[','ц'=>'w',
					'ч'=>'x','ш'=>'i','щ'=>'o','ъ'=>']','ы'=>'s','ь'=>'m','э'=>"'",'ю'=>'.',
					'я'=>'z'
				 );
				$t = array_flip($t);//ДЛЯ МАССИВА ПО РАСКЛАДКЕ

				if ($reverse)
				{
					$t = array_flip($t);//ОБРАТНЫЙ ПЕРЕВОД
				}

				$t1 = array();//массив в верхнем регистре
				foreach ($t as $key => $value)
					$t1[mb_strtoupper($key)] = mb_strtoupper($value);

				$searchCnt = mb_strlen($txt);
				for($i = 0; $i < $searchCnt; $i++)
				{
					$c = mb_substr($txt, $i, 1);
					if (isset($t[$c]))
					{
						$result .= $t[$c];
						continue;
					}
					elseif (isset($t1[$c]))
					{
						$result .= $t1[$c];
						continue;
					}
					else
					{
						$result .= $c;
					}
				}
				return $result;
			}

			if (empty($this->params['named']['istranslit']))
			{
	            $translit = transCyrChars($search);
				$isTranslit = 0;
    	    }
			else
			{
	            $translit = transCyrChars($search, true);
				$isTranslit = 1;
			}

            if ($translit == $search)
            	$translit = '';

            $this->_logSearchRequest($search);

            $sort = ', hits DESC';
            if (!empty($this->params['named']['sort']))
            {
                $sort = explode('.', $this->params['named']['sort']);
                $sort = ', ' . $sort[1] . ' DESC';
            }

            if (!empty($this->passedArgs['page']))
            {
            	$pagination['Film']['page'] = $this->passedArgs['page'];
            	$pagination['Film']['limit'] = $pagination['Film']['limit'];
            	$pagination['Film']['offset'] = ($pagination['Film']['page'] - 1) * $pagination['Film']['limit'];
            }
            $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Film']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC' . $sort);
            $pagination['Film']['sphinx']['index'] = array('films');//ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ

            $pagination['Film']['search'] = $search;
            $parts = explode(' ', $search);
            $condition = array('or' => array());

            $this->pageTitle .= ' - ' . __('Search', true) . ': ';

            $this->_setContextUrl($search);

            foreach ($parts as $part)
            {
                if (!is_numeric($part) && mb_strlen($part) < 3)
                    continue;

                $this->pageTitle .= $part . ' ';
            }
        }

        if (!empty($this->passedArgs['page']))
            $this->pageTitle .= ' ' . __('page', true) . ' ' . $this->passedArgs['page'];
/*
echo'<pre>';
var_dump($pagination);
echo'</pre>';
//*/
    $out='';
    $outCount='';
    $name=$this->passedArgs;
    //$name=array();
    ksort($name);
    foreach ($name as $k =>$v)
    {
    	//if ($k == 'search') continue; //РЕЗУЛЬТАТЫ ПОИСКА НЕКЭШИРУЕМ

        $out.=$k."_".$v."_";
        if ($k <> 'page')
        	$outCount.=$k."_".$v."_";
    }
//*

		if (!empty($this->passedArgs['page']) && empty($this->params['named']['search']))
		{
			$pagination['Film']['offset'] = (abs($this->passedArgs['page'])-1) * $pagination["Film"]['limit'];
		}
//pr($pagination);

		$films = false;
		$posts = array();
		if (!$isFirstPage)
			$films = Cache::read('Catalog.' . $postFix . 'list_'.$out, 'searchres');
		if (empty($search))
		{
			unset($pagination['Film']['sphinx']);//СФИНКС ВСЕ РАВНО НЕ БУДЕТ ИСКАТЬ ПО ПУСТОЙ СТРОКЕ
		}

		if ($films === false)//ЕСЛИ ЕЩЕ НЕ КЭШИРОВАЛИ
		{
    		$films = $this->Film->find('all', $pagination["Film"]);
    		//*

    		if (empty($films))
    		{
    			if (!empty($translit))
    			{
    				if (!isset($this->params['named']['istranslit']))
    				{
		                $this->redirect(array('action' => 'index',
    	                                  'search' => $translit,
    	                                  'istranslit' => 1,
        	                              'controller' => 'mobile'));
    				}
    				else
    				{
    					if ($isTranslit)
    					{
			                $this->redirect(array('action' => 'index',
	    	                                  'search' => $translit,
	    	                                  'istranslit' => 0,
	        	                              'controller' => 'mobile'));
    					}
    				}
	    			$pagination['Film']['search'] = $translit;
		    		$films = $this->Film->find('all', $pagination["Film"]);
		    		if ($films)
		    		{
		    			$this->params['named']['search'] = $translit;
			    		$transData=array('Transtat' => array('created' => date('Y-m-d H:i:s'), 'search' => mb_substr($translit, 0, 255)));
			    		//$this->Transtat->useDbConfig = 'productionMedia';
			    		$this->Transtat->create();
			    		$this->Transtat->save($transData);
		    		}
	    		}
			}
    		//*/

			//КЭШИРУЕМ ДАЖЕ ЕСЛИ НИЧЕГО НЕ НАЙДЕНО
    		//if (((isset($this->passedArgs['page'])) && $films) || isset($this->passedArgs['search']))

    		if (!$isFirstPage)
    		{
//echo 'RESULT CACHED';
//exit;
	    		Cache::write('Catalog.' . $postFix . 'list_'.$out, $films, 'searchres');
    		}
		}

		$wsmediaResult = 0;
		$animebarResult = 0;
		if (isset($this->params['named']['search'])) //ЗАПРОС СЧЕТЧИКА К ДРУГИМ КАТАЛОГАМ
		{
			$wsmediaResult = $this->searchWsmedia();
			$animebarResult = $this->searchAnimeBar();
		}

		$countation = $pagination;
    	unset($countation["Film"]['limit']);
    	unset($countation["Film"]['page']);
    	unset($countation["Film"]['contain']);
    	unset($countation["Film"]['order']);
    	unset($countation["Film"]['group']);
    	unset($countation["Film"]['fields']);

    	if ($isFirstPage)
    	{
	    	unset($countation["Film"]['conditions']);
	    	$countation["Film"]['conditions'][] = array('Film.active' => 1);
    	}

        if (!empty($this->params['named']['country']))
        {
            $countation['Film']['contain'][] = 'CountriesFilm';
            $countation['Film']['contain'][] = 'Country';
        }

        if (!empty($this->params['named']['type']))
        {
            $countation['Film']['contain'][] = 'FilmType';
        }

        if (!empty($this->params['named']['genre']))
        {
            $countation['Film']['contain'][] = 'FilmsGenre';
            $countation['Film']['contain'][] = 'Genre';
//pr($countation);
        }

       	$filmCount = Cache::read('Catalog.' . $postFix . 'count_'.$outCount, 'searchres');
//pr($countation);

		if (empty($filmCount))
		{
			if ((empty($this->passedArgs['type'])) && (empty($this->passedArgs['genre'])) && (empty($this->passedArgs['country'])))
				$this->Film->contain(array());//НА ГЛАВНОЙ КОЛИЧЕСТВО ФИЛЬМОВ БЕЗ ПОДЗАПРОСОВ МОЖНО ПОДСЧИТАТЬ
    		$filmCount = $this->Film->find('count', $countation["Film"]);
    		//if ((isset($this->passedArgs['page'])) && $filmCount)
    		{
		    	Cache::write('Catalog.' . $postFix . 'count_'.$outCount, $filmCount, 'searchres');
    		}
		}
//pr($filmCount);
    	$pageCount = intval($filmCount / $pagination['Film']['limit'] + 1);
    	$this->set('filmCount', $filmCount);
    	$this->set('pageCount', $pageCount);

//*/
	//}

        if (empty($films) && !empty($search) && empty($wsmediaResult) && empty($animebarResult))
        {
            $this->Film->Person->contain();
            $search = '%' . $this->params['named']['search'] . '%';
			$pagination = array();
			$pagination['Person']['limit'] = 1;
            $pagination['Person']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Person']['sphinx']['index'] = array('persons');//ИЩЕМ ПО ИНДЕКСУ ПЕРСОН
            $pagination['Person']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['Person']['search'] = $search;
    		$result = $this->Film->Person->find('all', $pagination["Person"]);

            if (!empty($result))
                $this->redirect(array('action' => 'people',
                                      'search' => urlencode($this->params['named']['search']),
                                      'controller' => 'mobile'));

            //show feedback form
	    	$this->set('user', $this->authUser);
            $this->data['Feedback']['film'] = $this->params['named']['search'];
            $this->render('feedback');
            return;
        }

        $this->set('films', $films);
        if (isset($search))
        {
        	$this->set('search', $search);
        }

    }

    function view(){

        echo "test";
    }
    
}

?>
