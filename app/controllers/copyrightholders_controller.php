<?php
//App::import('Vendor','PHPExcel',array('file' => 'excel/PHPExcel.php'));
//App::import('Vendor', 'php-excel-reader/my_excel_reader2');
class CopyrightholdersController extends AppController {

    var $name = 'Copyrightholders';
    var $viewPath = 'media/copyrightholders';
    var $uses = array('Copyrightholder','CopyrightholdersPicture','CopyrightholdersFilm','Film','CopyrightholdersPhonetic');

    var $helpers = array('Html','Form','Javascript','Autocomplete');
    var $components = array('Phonetics', 'ExcelImport' => array(
                                                'param1' => '111111',
                                                'param2' => '222222'
                                             ));
/*
    var $paginate = array(
        'limit' => 5,
        'order' => array(
            'Copyrightholder.name' => 'asc'
        )
    );
*/


//------------------------------------------------------------------------------

    function index()
    {
        if (!empty($this->data['Copyrightholder']['search']))
        {
            $this->redirect(array('action' => 'index',
            'search' => urlencode($this->data['Copyrightholder']['search'])));
        }

        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);


        if (!empty($this->params['named']['search'])
            && strlen($this->params['named']['search']) > 3)
        {
            $this->set('isSearch', true);

            $this->_setContextUrl($this->params['named']['search']);
            $this->Copyrightholder->contain();

            $pagination = array();
            $pagination['Copyrightholder']['limit'] = 30;
            $pagination['Copyrightholder']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Copyrightholder']['sphinx']['index'] = array('videoxq_copyrightholders');//ИЩЕМ ПО ИНДЕКСУ
            $pagination['Copyrightholder']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['Copyrightholder']['search'] = $this->params['named']['search'];
            $pagination['Copyrightholder']['conditions'] =  array('Copyrightholder.hidden' => 0);
            $result = $this->Copyrightholder->find('all', $pagination["Copyrightholder"]);

            $copyrightholders = array();
            foreach ($result as $copyrightholders_items)
            {
            	if ($lang == _ENG_)
            	{
            		if (empty($copyrightholders_items['Copyrightholder']['name' . $langFix]))
            			continue;
                	$letter = $copyrightholders_items['Copyrightholder']['name' . $langFix];
            	}
                else
                    $letter = $copyrightholders_items['Copyrightholder']['name'] ? $copyrightholders_items['Copyrightholder']['name'] : $copyrightholders_items['Copyrightholder']['name_en'];
                $letter = mb_substr($letter, 0, 1, 'utf-8');
                $copyrightholders[$letter][] = $copyrightholders_items;
            }
//            pr($people);
            $this->set('copyrightholders', $copyrightholders);
            return;
        }



        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

/* пока отключим кэш*/
        if (!($copyrightholders = Cache::read('Catalog.copyrightholdersIndex', 'copyrightholders')))
        {
 /**/
            $copyrightholders = $this->Copyrightholder->getCopyrightholdersIndex();
/* пока отключим кэш*/
            Cache::write('Catalog.copyrightholdersIndex', $copyrightholders, 'copyrightholders');
        }

/**/

        $this->set('copyrightholders', $copyrightholders);
    }

//------------------------------------------------------------------------------

    function letter($letter = null)
    {
        if (!$letter)
            $this->redirect('/copyrightholders');

	$lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
	$this->set('langFix', $langFix);

	$this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true) . ' - ' . $letter;


        $this->set('alphabet', $this->Copyrightholder->getAlphabet());

        $this->Copyrightholder->contain();



        $this->paginate['Copyrightholder']['conditions'] =
                array('and' => array('or' => array('Copyrightholder.name LIKE' => $letter . '%',
                                                   'Copyrightholder.name_en LIKE' => $letter . '%')

                               ),
                               array('Copyrightholder.hidden' => 0)
                );
        $this->paginate['Copyrightholder']['limit'] = 60;
        $this->paginate['Copyrightholder']['contain'] = array();

        $this->set('copyrightholders', $this->paginate());
    }

//------------------------------------------------------------------------------
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

//------------------------------------------------------------------------------

    function view($id = null)
    {
        //$this->layout = 'ajax';
        if (!$id) {
            $this->Session->setFlash(__('Invalid Page.', true));
            $this->redirect('/copyrightholders');
        }

	$lang = Configure::read('Config.language');
	$langFix = '';
        if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);
	$this->Copyrightholder->recursive = 1;
/* пока отключим кэш*/
	if(!$films=Cache::read('Catalog.copyrightholder_'.$id.'_film','copyrightholders'))
        {
/**/
            $sql = '';
            if (!$this->isWS)
            {
                $sql = 'Film.is_license = 1';
            }
    	    $films = $this->Copyrightholder->getCopyrightholdersFilms($id, 'CF.copyrightholder_id ASC, Film.year ASC', $sql);
/* пока отключим кэш*/
    	    Cache::write('Catalog.copyrightholder_'.$id.'_film',$films,'copyrightholders');
        }
/**/


/* пока отключим кэш*/
	if(!$person=Cache::read('Catalog.copyrightholder_'.$id,'copyrightholders'))
        {
 /**/
//    	    $copyrightholder = $this->Copyrightholder->read(null, $id);
            $copyrightholder = $this->Copyrightholder->find('first', array(
                                                                    'conditions' =>array(
                                                                        'and'=>array(
                                                                            'Copyrightholder.id'=>$id,
                                                                            'hidden'=>0))));

/* пока отключим кэш*/
    	    Cache::write('Catalog.copyrightholder_'.$id.'',$person,'copyrightholders');
        }
 /**/
        if (!$copyrightholder) $this->redirect('/copyrightholders');
        list($modifyDate, $modifyTime) = explode(' ', $copyrightholder['Copyrightholder']['modified']);
        $modifyDate = explode('-', $modifyDate);
        $modifyTime = explode(':', $modifyTime);
        $metaExpires = date('r', mktime($modifyTime[0], $modifyTime[1], $modifyTime[2], $modifyDate[1], $modifyDate[2], $modifyDate[0]));
        $this->set('metaExpires', $metaExpires);

        //$metaRobots = 'INDEX, NOFOLLOW';
        //$this->set('metaRobots', $metaRobots);

        $lang = Configure::read('Config.language');
        $langFix = '';
        if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true) . ' - ' . $copyrightholder['Copyrightholder']['name' . $langFix];

        $this->set('copyrightholder', $copyrightholder);
        $this->set('films', $films);
        $this->set('alphabet', $this->Copyrightholder->getAlphabet());
    }

//------------------------------------------------------------------------------
// admin'ская часть контроллера :)
//------------------------------------------------------------------------------

    function admin_index()
    {

        $default_rows_per_page = 30;
        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);

        if (!empty($this->data['Copyrightholder']['rows_per_page'])){
            $this->Session->write("CopyrightholderRowsPerPage", $this->data['Copyrightholder']['rows_per_page']);
        }
        if (!$this->Session->read("CopyrightholderRowsPerPage")){
            $this->Session->write("CopyrightholderRowsPerPage", $default_rows_per_page);
        }
        $rows_per_page = $this->Session->read("CopyrightholderRowsPerPage");


        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

/* пока отключим кэш
        if (!($cholders_list = Cache::read('Catalog.choldersList', 'copyrightholders')))
        {
*/

            //$cholders_list = $this->Copyrightholder->getCholdersList();

            $this->paginate = array(
//                    'page' => 1,
                    'limit' => $rows_per_page,
                    'order' => array(
                        'Copyrightholder.name' => 'asc'
                        )
                    );
            $total_rows_count = $this->Copyrightholder->find('count',
                                                            array(
                                                                'conditions' =>array(),
                                                                'recursive' => 0));

            $data = $this->paginate('Copyrightholder');


/* пока отключим кэш
            Cache::write('Catalog.choldersList', $copyrightholders, 'copyrightholders');
        }
*/


        //$this->set('cholders_list', $cholders_list);
            $this->set('cholders_list', $data);
            $this->set('rows_per_page', $rows_per_page);
            $this->set('total_rows_count', $total_rows_count);

    }

//------------------------------------------------------------------------------

    function admin_edit($id = null)
    {
         if (!empty($id) && intval($id)) {
            $id = intval($id);

            $lang = Configure::read('Config.language');
            $langFix = '';
            if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
            $this->set('lang', $lang);
            $this->set('langFix', $langFix);

            $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

            if (empty($this->data)){
                $cholder_data = $this->Copyrightholder->getCholderData($id);
                $this->data = $cholder_data;
            }
            else{
                    //pr($this->data);
                    //pr('<hr>');
                    $k=0;
                    for ($n=0; $n<count($this->data['CopyrightholdersPicture']); $n++){
                        $fileOK = $this->_uploadFiles('img/copyrightholders', $this->data['CopyrightholdersPicture'][$n]);
                        if(array_key_exists('urls', $fileOK)){
                            $this->data['CopyrightholdersPicture'][$n]['copyrightholder_id'] = $id;
                            $this->data['CopyrightholdersPicture'][$n]['file_name'] = ereg_replace('^(img\/)','',$fileOK['urls'][$n]);
                            $this->_deleteImgByCopyrightholderId($id);
                        }
                        else{
                            unset($this->data['CopyrightholdersPicture'][$n]);
                        }

                    }
                    if (!empty($this->data['CopyrightholdersPicture'][0])){
                        $this->data['CopyrightholdersPicture'] = $this->data['CopyrightholdersPicture'][0];
                    }
                    unset ($this->data['Files']);

                    //pr($this->data);

                    if ($this->Copyrightholder->save($this->data['Copyrightholder'])){
                        if (!empty($this->data['CopyrightholdersPicture'])){
                            $this->CopyrightholdersPicture->save($this->data['CopyrightholdersPicture']);
                        }
                        $this->Session->setFlash('Запись отредактирована.');
                        $this->redirect('/admin/copyrightholders');
                    }
            }


        }
        else{
            $this->redirect('/admin/copyrightholders');
        }

    }

//------------------------------------------------------------------------------

    function admin_add()
    {

            $lang = Configure::read('Config.language');
            $langFix = '';
            if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
            $this->set('lang', $lang);
            $this->set('langFix', $langFix);

            $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);
            if (!empty($this->data)){

                    $k=0;
                    for ($n=0; $n<count($this->data['CopyrightholdersPicture']); $n++){
                        $fileOK = $this->_uploadFiles('img/copyrightholders', $this->data['CopyrightholdersPicture'][$n]);
                        if(array_key_exists('urls', $fileOK)){
                            $this->data['CopyrightholdersPicture'][$n]['file_name'] = ereg_replace('^(img\/)','',$fileOK['urls'][$n]);
                        }
                        else{
                            unset($this->data['CopyrightholdersPicture'][$n]);
                        }

                    }
                    if (!empty($this->data['CopyrightholdersPicture'][0])){
                        $this->data['CopyrightholdersPicture'] = $this->data['CopyrightholdersPicture'][0];
                    }
                    unset ($this->data['Files']);



                    if ($this->Copyrightholder->save($this->data['Copyrightholder'])){
                        if (!empty($this->data['CopyrightholdersPicture'])){
                            $id = $this->Copyrightholder->id;
                            if ($id){
                                $this->data['CopyrightholdersPicture']['copyrightholder_id'] = $id;
                                $this->CopyrightholdersPicture->save($this->data['CopyrightholdersPicture']);
                            }
                        }
                        $this->Session->setFlash('Запись добавлена.');
                        $this->redirect('/admin/copyrightholders');
                    }
            }
    }

//------------------------------------------------------------------------------

    function admin_delete($id = null)
    {
        if (!empty($id) && intval($id)) {
            $id = intval($id);

            $lang = Configure::read('Config.language');
            $langFix = '';
            if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
            $this->set('lang', $lang);
            $this->set('langFix', $langFix);

            $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

            $this->_deleteImgByCopyrightholderId($id);
            $this->Copyrightholder->del($id,true);

            //$cholder_data = $this->Copyrightholder->getCholderData();
            //$this->set('cholder_data', $cholder_data);

        }
        $this->Session->setFlash('Запись удалена.');
        $this->redirect('/admin/copyrightholders');

    }

//------------------------------------------------------------------------------

    function _uploadFiles($folder, $formdata, $itemId = null) {
	// setup dir names absolute and relative
	$folder_url = WWW_ROOT.$folder;
	$rel_url = $folder;

	// create the folder if it does not exist
	if(!is_dir($folder_url)) {
		mkdir($folder_url);
	}

	// if itemId is set create an item folder
	if($itemId) {
		// set new absolute folder
		$folder_url = WWW_ROOT.$folder.'/'.$itemId;
		// set new relative folder
		$rel_url = $folder.'/'.$itemId;
		// create directory
		if(!is_dir($folder_url)) {
			mkdir($folder_url);
		}
	}

	// list of permitted file types, this is only images but documents can be added
	$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');

	// loop through and deal with the files
        foreach($formdata as $file){
		// replace spaces with underscores
		$filename = str_replace(' ', '_', $file['name']);
		// assume filetype is false
		$typeOK = false;
		// check filetype is ok
		foreach($permitted as $type) {
			if($type == $file['type']) {
				$typeOK = true;
				break;
			}
		}

		// if file type ok upload the file
		if($typeOK) {
			// switch based on error code
			switch($file['error']) {
				case 0:
					// check filename already exists
					if(!file_exists($folder_url.'/'.$filename)) {
						// create full filename
						$full_url = $folder_url.'/'.$filename;
						$url = $rel_url.'/'.$filename;
						// upload the file
						$success = move_uploaded_file($file['tmp_name'], $url);
					} else {
						// create unique filename and upload file
						ini_set('date.timezone', 'Europe/London');
						$now = date('Y-m-d-His');
						$full_url = $folder_url.'/'.$now.$filename;
						$url = $rel_url.'/'.$now.$filename;
						$success = move_uploaded_file($file['tmp_name'], $url);
					}
					// if upload was successful
					if($success) {
						// save the url of the file
						$result['urls'][] = $url;
					} else {
						$result['errors'][] = "Error uploaded $filename. Please try again.";
					}
					break;
				case 3:
					// an error occured
					$result['errors'][] = "Error uploading $filename. Please try again.";
					break;
				default:
					// an error occured
					$result['errors'][] = "System error uploading $filename. Contact webmaster.";
					break;
			}
		} elseif($file['error'] == 4) {
			// no file was selected for upload
			$result['nofiles'][] = "No file Selected";
		} else {
			// unacceptable file type
			$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
		}
        }
    return $result;
    }

//------------------------------------------------------------------------------

    function _deleteImgByCopyrightholderId($id=null){
        if (!empty($id) && intval($id)){
            $filename = WWW_ROOT.'img/'.$this->Copyrightholder->getImgFilenameByCopyrightholderId($id);
            if (file_exists($filename) && is_file($filename)){
                @unlink($filename);
            }
        }
    }

//------------------------------------------------------------------------------

    function admin_films()
    {

        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);


        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

/* пока отключим кэш
        if (!($cholders_list = Cache::read('Catalog.choldersList', 'copyrightholders')))
        {
*/
/*
        $this->components = array('RequestHandler');
        $this->helpers = array('Html','Form','Javascript');


            $this->paginate = array(
//                    'page' => 1,
                    'limit' => 10,
                    'order' => array(
                        'Copyrightholder.name' => 'asc'
                        )
                    );

            $data = $this->paginate('Copyrightholder');
            $this->set('data', $data);
*/
/* пока отключим кэш
            Cache::write('Catalog.choldersList', $copyrightholders, 'copyrightholders');
        }
*/
/*
            $flist = $this->Copyrightholder->Film->find('list', array(
                                'fields' => array('Film.id', 'Film.title'),
                                'conditions' => array(),
                                'order' => 'Film.title ASC',
                                'recursive' => 0
                                ));
            $this->set('film_list', $flist);

            $clist = $this->Copyrightholder->find('list', array(
                                'fields' => array('Copyrightholder.id', 'Copyrightholder.name'),
                                'conditions' => array(),
                                'order' => 'Copyrightholder.name ASC',
                                'recursive' => 0
                                ));
            $this->set('copyrightholders_list', $clist);

*/

/*

        if ($this->RequestHandler->isAjax()) {
//                $this->layout = 'ajax';
//                $this->viewPath = 'elements/posts';
                $this->render('admin_links_ajax');
        }
        else {
//            $this->layout = 'admin';
            $this->render('admin_links');
        }

*/
        $search_for = '';
         if (!empty($this->data)){
             if (!empty($this->data['Film']['title']) && mb_strlen($this->data['Film']['title'])>=3){
                 $search_for = $this->data['Film']['title'];
             }
         }
/*

                                            'contain' =>
                                       array('FilmType',
                                             'Genre',
                                             'FilmVariant' => array('VideoType'),
                                             'FilmPicture' => array('conditions' => array('type' => 'smallposter')),
                                             'Country',
                                             'Person' => array('conditions' => array('FilmsPerson.profession_id' => array(1, 3, 4))),
                                             'MediaRating'),

*/
        if ($search_for){
            $conditions = array ( "OR" => array (
                                        "Film.title LIKE" => "%".$search_for."%",
                                         "Film.title_en LIKE" => "%".$search_for."%"
                                ));
            $films_list = $this->Film->find('all',array(
                                        'contain' => array('Copyrightholder'),
                                        'fields' => array('Film.id', 'Film.title', 'Film.year'),
                                        'conditions' => $conditions




                                        ));
/*
            $films_list = $this->Copyrightholder->Film->find('list', array(
                                    'fields' => array('Film.id', 'Film.title', 'Film.year', 'CopyrightholdersFilms.film_id' ),
                                    'conditions' => $conditions,
                                    'order' => 'Film.title ASC',
                                    'joins' => array(
                                            array(
                                                'table' => 'copyrightholders_films',
                                                'alias' => 'CopyrightholdersFilms',
                                                'type' => 'Left',
                                                'foreignKey' => 'film_id',
                                                'conditions'=> array('Film.id = CopyrightholdersFilms.film_id')
                                                )),
//                                    'group' => 'Film.id',
                                    'recursive' => 0
                                    ));

 */
//            pr($films_list);

            $this->set('films_list', $films_list);
        }




    }

//------------------------------------------------------------------------------

    function admin_filmedit($id=null, $action=null,$c_id=null)
    {

        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);


        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);


        if (!empty($id) && !empty($action) && !empty($c_id) && $id && $action && $c_id){
            //pr($action);
            switch ($action) {
                case "add":
                    $this->Copyrightholder->addLinkCopyrightholderFilm($id, $c_id);
                    $this->Session->setFlash('Правообладатель добавлен.');
                    $this->redirect('/admin/copyrightholders/filmedit/'.$id);
                    break;
                case "del":
                    $this->Copyrightholder->deleteLinkCopyrightholderFilm($id, $c_id);
                    $this->Session->setFlash('Правообладатель удален.');
                    $this->redirect('/admin/copyrightholders/filmedit/'.$id);
                    break;
                default:
                //$this->Session->setFlash('Неизвестное действие.');
            }


        }




        $film_data = $this->Film->find('all',array(
                        'contain' => array('Copyrightholder'),
                        'fields' => array('Film.id', 'Film.title', 'Film.year'),
                        'conditions' => array ("Film.id" => $id),
                        'limit' => 1
                        ));

        $this->set('film_data', $film_data[0]);
        //pr($film_data[0]);

        $copyrightholders_list = $this->Copyrightholder->find('list', array(
                                'fields' => array('Copyrightholder.id', 'Copyrightholder.name'),
                                'conditions' => array(),
                                'order' => 'Copyrightholder.name ASC',
                                'recursive' => 0
                                ));
        $this->set('copyrightholders_list', $copyrightholders_list);
        //pr($copyrightholders_list);


    }

//------------------------------------------------------------------------------

    function admin_links()
    {

        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);


        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

/* пока отключим кэш
        if (!($cholders_list = Cache::read('Catalog.choldersList', 'copyrightholders')))
        {
*/
        $this->components = array('RequestHandler');
        $this->helpers = array('Html','Form','Javascript');


            $this->paginate = array(
//                    'page' => 1,
                    'limit' => 10,
                    'order' => array(
                        'Copyrightholder.name' => 'asc'
                        )
                    );

            $data = $this->paginate('Copyrightholder');
            $this->set('data', $data);

/* пока отключим кэш
            Cache::write('Catalog.choldersList', $copyrightholders, 'copyrightholders');
        }
*/
            $flist = $this->Copyrightholder->Film->find('list', array(
                                'fields' => array('Film.id', 'Film.title'),
                                'conditions' => array(),
                                'order' => 'Film.title ASC',
                                'recursive' => 0
                                ));
            $this->set('film_list', $flist);

            $clist = $this->Copyrightholder->find('list', array(
                                'fields' => array('Copyrightholder.id', 'Copyrightholder.name'),
                                'conditions' => array(),
                                'order' => 'Copyrightholder.name ASC',
                                'recursive' => 0
                                ));
            $this->set('copyrightholders_list', $clist);





        if ($this->RequestHandler->isAjax()) {
//                $this->layout = 'ajax';
//                $this->viewPath = 'elements/posts';
                $this->render('admin_links_ajax');
        }
        else {
//            $this->layout = 'admin';
            $this->render('admin_links');
        }


    }

//------------------------------------------------------------------------------

    function admin_getfilmlist($id=null)
    {

        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);


        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

/* пока отключим кэш
        if (!($cholders_list = Cache::read('Catalog.choldersList', 'copyrightholders')))
        {
*/
        $this->components = array('RequestHandler');
        $this->helpers = array('Html','Form','Javascript');


            $this->paginate =
                    array(
//                    'page' => 1,
                    'conditions' => array(),
                    'fields' => array('Film.id', 'Film.title'),
                    'recursive' => 0,
                    'limit' => 50,
                    'order' => array(
                        'Film.title' => 'asc'
                        )
                    );
            $data = $this->paginate('Film');
            /*
            $data = $this->Copyrightholder->Film->find('list', array(
                                'fields' => array('Film.id', 'Film.title'),
                                'conditions' => array(),
                                'order' => 'Film.title ASC',
                                'recursive' => 0
                                ));
            */

            $this->set('filmlist', $data);

/* пока отключим кэш
            Cache::write('Catalog.choldersList', $copyrightholders, 'copyrightholders');
        }
*/
/*
            $flist = $this->Copyrightholder->Film->find('list', array(
                                'fields' => array('Film.id', 'Film.title'),
                                'conditions' => array(),
                                'order' => 'Film.title ASC',
                                'recursive' => 0
                                ));
            $this->set('film_list', $flist);

            $clist = $this->Copyrightholder->find('list', array(
                                'fields' => array('Copyrightholder.id', 'Copyrightholder.name'),
                                'conditions' => array(),
                                'order' => 'Copyrightholder.name ASC',
                                'recursive' => 0
                                ));
            $this->set('copyrightholders_list', $clist);


*/


        if ($this->RequestHandler->isAjax()) {
//                $this->layout = 'ajax';
//                $this->viewPath = 'elements/posts';
                $this->render('admin_filmlist_ajax');
        }
        else {
//            $this->layout = 'admin';
            $this->render('admin_filmlist');
        }


    }


//------------------------------------------------------------------------------

    function admin_import($action=null, $file_name = null)
    {

        $lang = Configure::read('Config.language');
	$langFix = '';
	if ($lang == _ENG_) {$langFix = '_' . _ENG_;}
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);
        $this->pageTitle = __('Video catalog', true) . ' - ' . __('copyrightholders', true);

        //--------------------------//
        // инициализация переменных //
        //--------------------------//
/*
        $data = array();
        //число импротированных правообладателей
        //переменная оказалась лишней так как список (массив) все равно
        //формируется по ходу дела
        $count_imported_copyrightholders = 0;
*/
        //удалять ли файл после импорта
        $delete_file_after_import = true;
        //выстота шапки таблицы excel (по умолч. = 1 строка)
        $header_size = 1;
        //номер колонки с имененм правообладателя
        $cname_col = 11;
        //номер колонки с сылкой на фильм (там есть id фильма)
        $film_link_col = 15;
        //событие импорта - пока небыло
        $import_event = false;
        //число проанализированных строк
        $count_analysed_rows = 0;
        //число импортируемых связей
        $count_imported_links = 0;
        //инициализируем список импортированных правообладателей для вывода
        $imported_list = array();
        //параметры импорта строк "все" / "с" / "по"
        $all = false;
        $from = 1;
        $to = 1;
        //---------------------------
        // анализ входных данных (параметров импорта)
        if (!empty($this->data) && $this->data['Copyrightholder']){
            if(!empty($this->data['Copyrightholder']['all']) && $this->data['Copyrightholder']['all']){
                $all = $this->data['Copyrightholder']['all'];
            }
            if(!empty($this->data['Copyrightholder']['from']) && $this->data['Copyrightholder']['from']){
                $from = $this->data['Copyrightholder']['from'];
                if ($from <1) {$from = 1;}
            }
            if(!empty($this->data['Copyrightholder']['to']) && $this->data['Copyrightholder']['to']){
                $to = $this->data['Copyrightholder']['to'];
                if ($to <1) {$to = 1;}
            }

            if (!empty($this->data['Copyrightholder']['file_name']) && $this->data['Copyrightholder']['file_name']){
                $file = $this->data['Copyrightholder']['file_name'];
                if (is_uploaded_file($file['tmp_name'])) {
                    $url = 'spisok.xls';
                    move_uploaded_file($file['tmp_name'], $url);
                    //$file_name = 'spisok.xls';
                    $file_name = $url;
                }
            }

        }
        //---------------------------
        // если файл был загружен то импортируем из него данные
        if (!empty($file_name) && $file_name) {

            $options = array(
                            'all' => $all,
                            'from' => $from,
                            'to' => $to,
                            'file_name' => $file_name,
                            'delete_file_after_import' => $delete_file_after_import,
                            'header_size' => $header_size,
                            'cname_col' => $cname_col,
                            'film_link_col' => $film_link_col
                        );
            //используем метод компонента для импорта
            $result = $this->ExcelImport->importCopyrightholders($options);
            //разбираем результат
            $count_analysed_rows = $result['count_analysed_rows'];
            $count_imported_links = $result['count_imported_links'];
            $imported_list = $result['imported_list'];
            $import_event = $result['import_event'];
        }
        //---------------------
        //сздаем массив данных для отображения
        $data = array();
        $data['count_analysed_rows'] = $count_analysed_rows;
        $data['count_imported_links'] = $count_imported_links;
        $data['imported_list'] = $imported_list;
        $data['import_event'] = $import_event;

        $this->set('data',$data);
    }

//------------------------------------------------------------------------------

    function autocomplete() {
        //был ли ajax-запрос с непустым post?
        if ($this->RequestHandler->isAjax() && $this->RequestHandler->isPost()) {
            //разбор того что пришло
            $model = $this->params['form']['model'];
            $fields = explode(",",$this->params['form']['fields']);
            $search_field = $this->params['form']['search'];
            $search_for = $this->params['form']['query'];
            $limit = $this->params['form']['numresult'];
            $fields_ = $this->params['form']['fields'];
            $rand = $this->params['form']['rand'];
            $search_condition='';
            //если нажали на BS то удалим лишний хлам который прилетел, а
            //прилетает символ BS = 08 и неудаленный последний символ, итого 2
            //символа на удаление
            //блин вот неудаленный символ не удалить так как BS помещается в
            //конец строки, и не понять чего удалять то :((((((
            if (mb_strpos($search_for, chr(8))!== false){
                $search_for = mb_substr($search_for,0, mb_strpos($search_for, chr(8)));
            };

            //флаг эктренного выхода и возврата пустого значения,
            //если что-то не так
            $exit_flag=false;

            //если мы вызываем autocomplete из frontend'а, то обрежем
            //нежелдательную инфу, сформировав соотвествующий $condition, в
            //зависимости от модели.
            //юзерам ведь не надо видеть неактивные фильмы и скрытых
            //правообладателей :))))
            if (empty($this->params[Configure::read('Routing.admin')])){
                switch ($model){
                    case 'Film':
                        if (!$this->isWS){
                            $frontend_condition = $model.'.is_license = 1 AND';
                        }
                        $frontend_condition = $model.'.active = 1';
                        break;
                    case 'Copyrightholder':
                        $frontend_condition = $model.'.hidden = 0';
                        break;
                }
            }
            //если модель - для фонетического поиска то формируем запрос особым
            //способом
            if ($model == 'CopyrightholdersPhonetic'){

                //найдем последнее введенное юзером слово(по умолч. >= 3х букв)
                //длинной, и будем искать по этому слову
                $min_word_length = 4;

                if ( mb_strlen($search_for) >= $min_word_length){
                    $word = $this->_find_last_word_in_string ($search_for, $min_word_length);
                    //узнаем его фонетический код
                    $code_arr = $this->Phonetics->dmstring($word);
                    $code = ''.$code_arr[0];
                    //производим поиск по этому коду, в результате запроса вернется
                    //слово, или несколько слов с похожим началом фонетического кода
                    //из фонетической базы, которые
                    //отправим юзеру
                    //в коде отбросим конечные нули, для поиска нескольких слов,
                    // а не одного точно совпадающего
                    //pr($code_arr);
                    //pr($search_for);
                    //$code = preg_replace('/[0]*$/', '', $code);
                    //$search_for = $code;
                    $search_field2 = 'code';
                    //основной фильтр запроса
                    $search_condition = $model.'.'.$search_field2.' LIKE \''.$code.'%\'';
                }
                else {
                    $exit_flag = true;
                }


            }
            else{
                //для остальных моделей формируем обычный запрос
                //основной фильтр запроса
                //pr($search_for);
                $search_condition = $model.'.'.$search_field.' LIKE \'%'.$search_for.'%\'';
            }

            if(!empty($frontend_condition) && $frontend_condition){
            //дополнительный фильтр запроса, если нужен для данной модели
                $conditions = array('AND'=>array($search_condition, $frontend_condition));
            }
            else{
                $conditions = array($search_condition);
            }

            $results = array();
            //pr($word);
            //pr($conditions);
            if (!$exit_flag){
                $results = $this->{$model}->find('all', array(
                                        'contain' => array(),
                                        'fields' => $fields_,
                                        'limit' => $limit,
                                        'conditions' => $conditions));
            }

            //заполняем переменные предсавления для ответа
            $this->set('results', $results);
            $this->set('fields', $fields);
            $this->set('model', $model);
            $this->set('input_id', $rand);
            $this->set('search', $search_field);
            $this->render('autocomplete','ajax','autocomplete');
        }
    }


//------------------------------------------------------------------------------

    function admin_phoneticsearch() {

    }

//------------------------------------------------------------------------------

    function admin_phoneticbuildbase(){
        //$this->CopyrightholdersPhonetic->cacheQueries = false;
        //получаем список правообладателей
        $copyrightholders_list = $this->Copyrightholder->find('list', array(
            'fields' => array('Copyrightholder.name'),
            'conditions' => array(),
            'order' => 'Copyrightholder.name ASC',
            'recursive' => 0
            ));
        if ($copyrightholders_list){
            $phonetics_list = array();
            //шерстим весь полученый список, разбиваем каждую строку по словам, и
            //формируем новый массив состоящий из полученных слов (не менее 3х
            //символов каждое), и тут же на лету (для экономии ресурсов :) формируем
            //список соотвествия слова и его фонетического кода (6 цифр)
            foreach ($copyrightholders_list as $k=>$cname){
                //разбиваем на слова
                //мин длина слова 3 символа
                $min_word_length = 4;
                /*
                 * в выбраном арсенале имеем 2 способа разбивки
                $text = 'Филиал компании , с ограниченной ответственностью "Юнайтед Интернэшнл Пикчерс ГмбХ" df  df dssd d';
                $result = preg_split('/[^а-яa-z1-9]/siu', $text, -1, PREG_SPLIT_NO_EMPTY);
                pr($result);
                preg_match_all('/([а-яa-z1-9]{5,})/ui',$text,$words);
                pr($words);
                */
                preg_match_all('/([а-яa-z]{'.$min_word_length.',})/ui', $cname, $words_list);
                if (!empty($words_list[0]) && $words_list[0]){
                    //сразу на лету заполняем список
                    foreach ($words_list[0] as $word){
                        if ($word){
                        //$phonetics_list[]=array('word'=>$word,'code'=>$this->Phonetics->dmstring($word));
                        //отказался от предыдущего постоения массива в пользу
                        //следующего, сразу 2ух зайцев за раз! arr(code=>word)
                        //и от дубликатов записей избавился и массив создал! :)
                        $code_arr = $this->Phonetics->dmstring($word);
                        $code = ''.$code_arr[0];
                        $phonetics_list[$code]=$word;
                        }
                    }
                }

            }
            //pr($phonetics_list);
            //чистим после себя :)
            unset ($copyrightholders_list);
            unset ($code_arr);
            unset ($words_list);

            //отправляем весь полученый список на обновление в БД :))))
            //формируем массив дял вставки в БД
            //для экономии ресурсов преобразуем его же в новый формат
            //array(array('code'=>'xxx','word'=>'xxx'))
            //да и число записей все равно то же :)))
            $phonetics_data = array();
            foreach ($phonetics_list as $code => $word){
                $phonetics_data[] = array(
                    'code' => $code,
                    'word' => $word
                    );
            }
            //pr($phonetics_list);
            //pr($phonetics_list);
            unset ($phonetics_list);
            if ($phonetics_data){

                //перед обновлением таблицы со словарем очистим ее полностью, дабы
                //не заморачиваться с поиском и обновлением или удалением старых
                //записей
                $this->CopyrightholdersPhonetic->deleteAll(array('1 = 1'),false);
                $result = $this->CopyrightholdersPhonetic->saveAll($phonetics_data);
                //чистим после себя :)
                unset ($phonetics_data);
                $this->Session->setFlash('Фонетическая база обновлена', true);
            }
        }
        else{
            $this->Session->setFlash('Фонетическая база не обновлена, не могу найти записи :(', true);
        }
        $this->redirect('/admin/copyrightholders/phoneticsearch');
    }

//------------------------------------------------------------------------------

    function _find_words_in_string ($string, $min_word_length = 3){
        $words_list = array();
        //разбиваем на слова
        preg_match_all('/([а-яa-z0-9]{'.$min_word_length.',})/ui', $string, $list);
        if (!empty($list[0]) && $list[0]){
            $words_list = $list[0];
        }
        return $words_list;
    }

//------------------------------------------------------------------------------

    function _find_last_word_in_string ($string, $min_word_length = 3){
        $word = '';
        //разбиваем на слова
        preg_match_all('/([а-яa-z0-9]{'.$min_word_length.',})/ui', $string, $list);
        if (!empty($list[0]) && $list[0]){

            $n= count($list[0]);
            $word = $list[0][$n-1];
        }
        return $word;
    }

//------------------------------------------------------------------------------

}
?>


