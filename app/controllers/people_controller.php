<?php
class PeopleController extends AppController {

    var $name = 'People';
    var $viewPath = 'media/people';

    function view($id = null)
    {
        //$this->layout = 'ajax';
        if (!$id) {
            $this->Session->setFlash(__('Invalid Page.', true));
            $this->redirect('/people');
        }

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);

		$this->Person->recursive = 1;
	if(!$films=Cache::read('Catalog.person_'.$id.'_film','people'))
        {
        	$sql = '';
        	if (!$this->isWS)
        	{
        		$sql = 'Film.is_license = 1';
        	}
    	    $films = $this->Person->getPersonFilms($id, 'FilmsPerson.profession_id ASC, Film.year ASC', $sql);
    	    Cache::write('Catalog.person_'.$id.'_film',$films,'people');
        }

        $this->Person->contain(array('Profession', 'PersonPicture'));

	if(!$person=Cache::read('Catalog.person_'.$id,'people'))
        {
    	    $person = $this->Person->read(null, $id);
    	    Cache::write('Catalog.person_'.$id.'',$person,'people');
        }
		list($modifyDate, $modifyTime) = explode(' ', $person['Person']['modified']);
		$modifyDate = explode('-', $modifyDate);
		$modifyTime = explode(':', $modifyTime);
		$metaExpires = date('r', mktime($modifyTime[0], $modifyTime[1], $modifyTime[2], $modifyDate[1], $modifyDate[2], $modifyDate[0]));
		$this->set('metaExpires', $metaExpires);

		//$metaRobots = 'INDEX, NOFOLLOW';
		//$this->set('metaRobots', $metaRobots);

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        //$this->pageTitle = __('Video catalog', true) . ' - ' . __('People', true) . ' - ' . $person['Person']['name' . $langFix];
        $this->Metatags->insert(__('People', true) . ' - ' . $person['Person']['name' . $langFix], '', '');

        //----------------------------------------------------------------------
        //добавим в готовый массив поле - сгенерированый slug на основе title фильма
        foreach($films as $key=>$val){
            $films[$key]['Film']['slug'] = $this->_toSlug($val['Film']['title']);
        }
        //----------------------------------------------------------------------
        
        $this->set('person', $person);
        $this->set('films', $films);
        $this->set('alphabet', $this->Person->getAlphabet());
    }


    function index()
    {
        if (!empty($this->data['Person']['search']))
        {
            $this->redirect(array('action' => 'index',
                                  'search' => urlencode($this->data['Person']['search'])));
        }

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);

        if (!empty($this->params['named']['search'])
            && strlen($this->params['named']['search']) > 3)
        {
            $this->set('isSearch', true);

            $this->_setContextUrl($this->params['named']['search']);
            $this->Person->contain();
/*
            $search = '%' . $this->params['named']['search'] . '%';
            $result = $this->Person->find('all', array('conditions' =>
                                           array('or' =>
                                           array('Person.name LIKE' => $search,
                                                 'Person.name_en LIKE' => $search))));
*/
			$pagination = array();
			$pagination['Person']['limit'] = 30;
            $pagination['Person']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Person']['sphinx']['index'] = array('videoxq_persons');//ИЩЕМ ПО ИНДЕКСУ ПЕРСОН
            $pagination['Person']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['Person']['search'] = $this->params['named']['search'];
    		$result = $this->Person->find('all', $pagination["Person"]);

            $people = array();
            foreach ($result as $person)
            {
            	if ($lang == _ENG_)
            	{
            		if (empty($person['Person']['name' . $langFix]))
            			continue;
                	$letter = $person['Person']['name' . $langFix];
            	}
                else
                	$letter = $person['Person']['name'] ? $person['Person']['name'] : $person['Person']['name_en'];
                $letter = mb_substr($letter, 0, 1, 'utf-8');
                $people[$letter][] = $person;
            }
//            pr($people);
            $this->set('people', $people);
            return;
        }

        //$this->pageTitle = __('Video catalog', true) . ' - ' . __('People', true);
        $this->Metatags->insert(__('People', true), '', '');

        if (!($people = Cache::read('Catalog.peopleIndex', 'people')))
        {
            $people = $this->Person->getPeopleIndex();
            Cache::write('Catalog.peopleIndex', $people, 'people');
        }
        $this->set('people', $people);
    }


    function letter($letter = null)
    {
        if (!$letter)
            $this->redirect('/people');

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);

//		$this->pageTitle = __('Video catalog', true) . ' - ' . __('People', true) . ' - ' . $letter;
        $this->Metatags->insert(__('People', true) . ' - ' . $letter, '', '');

        $this->set('alphabet', $this->Person->getAlphabet());
        $this->Person->contain();

        $this->paginate['Person']['conditions'] = array('or' =>
                                           array('Person.name LIKE' => $letter . '%',
                                                 'Person.name_en LIKE' => $letter . '%'));
        $this->paginate['Person']['limit'] = 60;
        $this->paginate['Person']['contain'] = array();

        $this->set('people', $this->paginate());
    }

    /**
     * Показываем ссылку на внешние ресурсы
     *
     * @param string $search
     */
    function _setContextUrl($search)
    {
        $model = ClassRegistry::init('SearchWord');
        $words = $model->getUrl($search);
        $this->set('search_words', $words);
    }

}
?>