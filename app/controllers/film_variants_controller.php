<?php
class FilmVariantsController extends AppController {

	var $name = 'FilmVariants';
//    var $viewPath = 'media/film_variants';
    //var $adminAtribs=array();
/*
    var $adminAtribs=array(
						'ManageOpions'=>array('New %'=>'add'),
    					'editRowsSettings'=>array(
						    		'duration'=>array('options'=>array('type'=>'text')),
						)
	);
*/

	var $uses = array('FilmVariant', 'Language', 'Translation');

	/**
	 * дополнительные настройки для полей форм редактирования
	 *
	 * @return array($model,$UseTable,$rows)
	 */
	public function _admin_before_action()
	{
		$flagCatalogOptions = array('options' => array('checked' => 'checked'));
		if (isset($this->passedArgs))
		{
			if (empty($this->passedArgs[0]))//ПРИ ДОБАВЛЕНИИ НОВОГО ВАРИАНТА - ЧЕКРЫЖИТЬ
				$this->adminAtribs['editRowsSettings']['flag_catalog'] = $flagCatalogOptions;
		}
		else
		{
			$this->adminAtribs['editRowsSettings']['flag_catalog'] = $flagCatalogOptions;
		}
		return parent::_admin_before_action();
	}

	public function admin_view($id = null)
	{
    	$this->viewPath = 'media/film_variants';
		if (!$id) {
            $this->Session->setFlash(__('Invalid '.$model.'.', true));
            $this->redirect(array('action'=>'index'));
        }
        $filmVariant = $this->FilmVariant->read(null, $id);
        $this->set('filmVariant', $filmVariant["FilmVariant"]);
        $this->set('filmFiles', $filmVariant["FilmFile"]);
        $this->set('filmTrack', $filmVariant["Track"]);
	}

	/**
	 * добавление версии к фильму
	 *
	 * @param integer $filmId - идентификатор фильма
	 */
	public function admin_add($filmId = null)
	{
    	$this->viewPath = 'media/film_variants';
    	if (!empty($this->data))
    	{
    		$filmId = $this->data["FilmVariant"]["film_id"];
    	}
		if (!$filmId) {
            $this->Session->setFlash(__('Invalid Film.', true));
            $this->redirect(array('action'=>'index'));
        }

        if (empty($this->data))//ЗНАЧИТ ЭТО ФОРМА ДОБАВЛЕНИЯ
        {
	        $film = $this->FilmVariant->Film->read(null, $filmId);
	        $this->set('film', $film);

	        $videoTypes = $this->FilmVariant->VideoType->find('list');
	        $languages = $this->Language->find('list');
	        $translations = $this->Translation->find('list');
	        $this->set(compact('videoTypes', 'translations', 'languages'));
        }
        else//СОХРАНЯЕМ ДАННЫЕ
        {
        	$filmVariant = $this->FilmVariant->save($this->data);
    		if ($filmVariant)
    		{
		        $film = $this->FilmVariant->Film->read(null, $filmId);
		        $this->set('film', $film);

    			foreach ($this->data["FilmFile"] as $key => $value)
    			{
    				if (empty($this->data["FilmFile"][$key]["size"]))
    				{
    					unset($this->data["FilmFile"][$key]);
    					continue;
    				}
    				$this->data["FilmFile"][$key]["film_variant_id"] = $this->FilmVariant->id;
    			}
    			if (count($this->data["FilmFile"]))
    				$this->FilmVariant->FilmFile->saveAll($this->data["FilmFile"]);

    			$this->data["Track"]["film_variant_id"] = $this->FilmVariant->id;
    			$this->FilmVariant->Track->save($this->data["Track"]);
    		}
    		$this->redirect('/admin/view/' . $filmId);
        }
	}

	/**
	 * сохранение изменений
	 *
	 * @param integer $id - идентификатор FilmVariant
	 */
	public function admin_edit($id = null)
	{
    	$this->viewPath = 'media/film_variants';
    	if (!empty($this->data))
    	{
    		$id = $this->data["FilmVariant"]["id"];
    	}
		if (!$id) {
            $this->Session->setFlash(__('Invalid FilmVariant.', true));
            $this->redirect(array('action'=>'index'));
        }

        if (empty($this->data))//ЗНАЧИТ ЭТО ФОРМА РЕДАКТИРОВАНИЯ
        {
	        $filmVariant = $this->FilmVariant->read(null, $id);
	        $this->set('filmVariant', $filmVariant);

	        $videoTypes = $this->FilmVariant->VideoType->find('list');
	        $languages = $this->Language->find('list');
	        $translations = $this->Translation->find('list');
	        $this->set(compact('videoTypes', 'translations', 'languages'));
        }
        else//СОХРАНЯЕМ ДАННЫЕ
        {
        	$filmVariant = $this->FilmVariant->save($this->data);
    		if ($filmVariant)
    		{
		        $this->set('filmVariant', $filmVariant);

    			$this->FilmVariant->FilmFile->saveAll($this->data["FilmFile"]);
    			$this->FilmVariant->Track->save($this->data["Track"]);
	        	$this->redirect(array('action'=>'edit/' . $filmVariant["FilmVariant"]["id"]));
    		}
    		else
	        	$this->redirect(array('action'=>'index'));
        }
	}
}