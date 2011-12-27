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
    
    
    function admin_index() {
        /*
        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
	$this->set('langFix', $langFix);
        $rows = $this->Person->query('DESCRIBE `persons`');
        $this->Person->recursive = 0;
        $this->set('DATA', $this->paginate());
        
        
        $this->adminAtribs = set::merge($this->adminAtribs, $this->_adminAtribs);
        $usedModels = set::merge($this->Person->belongsTo, $this->Person->hasOne);
        $usedModels = set::combine($usedModels, "{s}.foreignKey", "{s}.className");
        $rows = set::combine($rows, "{n}.COLUMNS.Field", "{n}.COLUMNS.Type");
        
        $this->set('usedModels', $usedModels);
        $this->set('model', 'Person');
        $this->set('rows', $rows);
        $this->set('actions', $this->adminAtribs['ManageOpions']);
        $this->set('editRowsSettings', $this->adminAtribs['editRowsSettings']);        
        
        */
        
        $default_rows_per_page = 30;
        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);

        if (!empty($this->data['People']['rows_per_page'])){
            $this->Session->write("PeopleRowsPerPage", $this->data['People']['rows_per_page']);
        }
        if (!$this->Session->read("PeopleRowsPerPage")){
            $this->Session->write("PeopleRowsPerPage", $default_rows_per_page);
        }
        $rows_per_page = $this->Session->read("PeopleRowsPerPage");
        $this->pageTitle = "admin/" . $this->name . "/" . $this->action;
        
        
        
        if (!empty($this->data['PeopleFilter'])){
            $this->Session->write("PeopleFilter", $this->data['PeopleFilter']);
        }
        if (!$this->Session->read("PeopleFilter")){
            $this->Session->write("PeopleFilter", array());
        }
        $this->data['PeopleFilter'] = $this->Session->read("PeopleFilter");
        
        $conditions = array();
        if (!empty($this->data['PeopleFilter'])){
            $this->set('PeopleFilter', $this->data['PeopleFilter']);
            
            if (!empty($this->data['PeopleFilter']['id'])){
                $conditions[] = array('Person.id LIKE' => '%'.$this->data['PeopleFilter']['id'].'%');
            }
            if (!empty($this->data['PeopleFilter']['name'])){
                $conditions[] = array('Person.name LIKE' => '%'.$this->data['PeopleFilter']['name'].'%');
            }
            if (!empty($this->data['PeopleFilter']['name_en'])){
                $conditions[] = array('Person.name_en LIKE' => '%'.$this->data['PeopleFilter']['name_en'].'%');
            }
            if (!empty($this->data['PeopleFilter']['description'])){
                $conditions[] = array('Person.description LIKE' => '%'.$this->data['PeopleFilter']['description'].'%');
            }
        }        
        
        
        

        $this->paginate = array(
//                    'page' => 1,
            'limit' => $rows_per_page,
            'conditions' => $conditions,
            'order' => array(
                'Person.name' => 'asc',
                'Person.name_en' => 'asc'
                )
            );
        $total_rows_count = $this->Person->find('count', array(
                                    'conditions' => $conditions,
                                    'recursive' => 0));
        $data = $this->paginate('Person');

        $this->set('rows_list', $data);
        $this->set('rows_per_page', $rows_per_page);
        $this->set('total_rows_count', $total_rows_count);        
        
        
    }

    function admin_add() {

        $lang = Configure::read('Config.language');
        $langFix = '';
        if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);

        $this->pageTitle = __('Video catalog', true) . ' - ' . __('People', true);
        if (!empty($this->data['People'])){
            $validate = true;
            if(empty($this->data['People']['name']) && empty($this->data['People']['name_en'])){
                $validate = false;
            }
            if ($validate && $this->Person->save($this->data['People'])){
                $this->Session->setFlash('Запись добавлена.');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    function admin_edit($id = null) {
        $lang = Configure::read('Config.language');
        $langFix = '';
        if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);

        $this->pageTitle = __('Video catalog', true) . ' - ' . __('People', true);        
        
        if (!empty($id) && intval($id)) {
            $id = intval($id);
            $conditions =  array('id'=>$id);
            $data = $this->Person->find('first', array(
                                    'conditions' => $conditions,
                                    'recursive' => 0));
            $this->set('People' , $data['Person']);
        }
        else if (!empty($this->data['People'])){
            $validate = true;
            if(empty($this->data['People']['name']) && empty($this->data['People']['name_en']) && empty($this->data['People']['id'])){
                $validate = false;
            }
            if ($validate){
                $conditions = array('id'=>intval($this->data['People']['id']));
                $this->Person->conditions = array('conditions'=>$conditions);
                if ($this->Person->save($this->data['People'])){
                    $this->Session->setFlash('Запись изменена.');
                    $this->redirect(array('action' => 'index'));
                }                    
            }            
        }



    }

    function admin_view($id = null) {
        //list($model, $UseTable, $rows) = $this->_admin_before_action();
        if (!$id) {
            $this->Session->setFlash(__('Invalid ' . $model . '.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('DATA', $this->$model->read(null, $id));

        //$this->_admin_after_action($rows);
    }

    function admin_delete($id = null) {
        if (!empty($id) && intval($id)) {
            $id = intval($id);

            $lang = Configure::read('Config.language');
            $langFix = '';
            if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
            $this->set('lang', $lang);
            $this->set('langFix', $langFix);
            $this->pageTitle = __('Video catalog', true) . ' - ' . __('People', true);

            //$this->_deleteImgByPersonId($id);
            $this->Person->del($id,true);
        }
        $this->Session->setFlash('Запись удалена.');
        $this->redirect(array('action' => 'index'));
    }    

}
?>