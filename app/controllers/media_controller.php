<?php
class MediaController extends AppController {

    var $name = 'Media';
    var $helpers = array('Html', 'Form', 'Rss', 'Text', 'PageNavigator','Javascript','Autocomplete');
    var $components = array('Captcha', 'Cookie', 'RequestHandler'/*,'DebugKit.toolbar'*/);
    var $viewPath = 'media/films';
    var $uses = array('Film', 'Basket', 'FilmComment', 'SearchLog', 'Feedback', 'Thread', 'Vbpost', 'Vbgroup',
    'Forum', 'Userban', 'Transtat', 'Genre', 'Bookmark', 'CybChat', 'Smile', 'Migration',
    //'DlePost',
    'SimilarFilm',
    'OzonProduct'
    );

    /**
     *
     * Модель чата форума
     *
     * @var AppModel
     */
    var $CybChat;

    /**
     *
     * Модель смайликов форума
     *
     * @var AppModel
     */
    var $Smile;

    /**
     *
     * Модель групп форума
     *
     * @var AppModel
     */
    var $Vbgroup;

    /**
     *
     * Модель фавнутых ссылок
     *
     * @var AppModel
     */
    var $Bookmark;

    /**
     * выполнить действие по аякс запросу
     * дополнительные данные отправляются как $_POST
     *
     */
    public function ajax($subAction)
    {
	   	$this->layout = 'ajax';
		switch ($subAction)
		{
			case "switchblock"://ХРАНИМ В СЕССИИ СОСТОЯНИЯ БЛОКОВ
				if (!empty($_POST['blockname']))
				{
					$blockName = $_POST['blockname'];
					$blockStatuses = $this->Session->read('blockStatuses');
					$blockStatuses = unserialize($blockStatuses);

					if (empty($blockStatuses))
					{
						$blockStatuses = array();
					}

					if (empty($blockStatuses[$blockName]))
					{
						$blockStatuses[$blockName] = 1;//РАЗВЕРНУТ
					}
					else
					{
						$blockStatuses[$blockName] = 0;//СВЕРНУТ
					}
					$this->Session->write('blockStatuses', serialize($blockStatuses));
					$blockStatuses = $this->Session->read('blockStatuses');

					$this->set('blockStatuses', $blockStatuses);

				}
			break;
		}
    }

    /**
     * получить список всех фильмов с картинками
     *
     */
    public function cache()
    {
    	$this->layout = 'ajax';
        ini_set('memory_limit','1G');
		$films = Cache::read('Catalog.cache_films', 'searchres');
		if (!$films)
		{
	    	$films = $this->Film->getFilmsWithPictures();
	    	foreach ($films as $key => $film)
	    	{
    			$pic = Configure::read('Catalog.imgPath') . $film['p']['file_name'];
				$films[$key]['p']['file_name'] = $pic;
	    	}
		    Cache::write('Catalog.cache_films', $films, 'searchres');
    	}
		shuffle($films);
		$rfilms = array();
		for ($i = 0; $i < 500; $i++)
			$rfilms[] = $films[$i];
   		$this->set('films', $rfilms);
    }

	public function rss($filmId = 0)
	{
		Configure::write('debug', 0);
		$this->layout = 'rss/default';
		$film = array();
		//if( $this->RequestHandler->isRss() )
		if (!empty($filmId))
		{
			//$film = $this->Film->find(array('Film.id' => $filmId), null, null, 1);
			$film = Cache::read('Catalog.film_view_' . $filmId, 'media');
			if (!$film)
			{
		        $this->Film->recursive = 0;
		        $this->Film->contain(array('FilmType',
		                                     'Genre',
		                                     'Thread',
		                                     'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
		                                     'Country',
		                                     'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
		                                     'MediaRating',
		                                  )
		                             );
		        $film = $this->Film->read(null, $filmId);
			    Cache::write('Catalog.film_view_' . $filmId, $film,'media');
			}
		}
		else
		{
			//$films = $this->Film->findAll(null, null, 'rand()', 1);
//			if (!empty($films))
	//			$film = $films[0];
			$film = $this->Film->getRandomFilm();
		}
		$this->set(compact('film'));
	}

	public function rsslist()
	{
		Configure::write('debug', 0);
		$this->layout = 'rss/default';
		$films = array();
		$conditions = array('Film.active' => 1);
		if (!$this->isWS)
		{
			$conditions['Film.is_license'] = 1;
		}
        $pagination = array('Film' => array('contain' =>
                                       array('FilmType',
                                             'Genre',
                                     		 'FilmVariant' => array('VideoType'),
                                             'FilmPicture' => array('conditions' => array('type' => 'smallposter')),
                                             'Country',
                                              'Person' => array('conditions' => array('FilmsPerson.profession_id' => array(1, 3, 4))),
                                             'MediaRating'),
                                        'order' => 'Film.modified DESC',
                                        'conditions' => $conditions,
                                        'group' => 'Film.id',
                                        'limit' => 30));
		$films = $this->Film->find('all', $pagination["Film"]);
		$this->set('films', $films);
	}

	public function unsetEmpty(&$data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				if (is_array($data[$key]))
				{
					if (empty($data[$key]))
					{
//echo 'unset ('.$key.')';
						unset($data[$key]);
					}
					else
					{
						if (array_key_exists('id', $data[$key]) && empty($data[$key]['id']))
						{
//echo 'unset id ('.$key.')';
							unset($data[$key]);
						}
						else
						{
//echo 'unsetEmpty ('.$key.')';
							$this->unsetEmpty($data[$key]);
						}
					}
				}
			}
		}
	}

	/**
	 * подготовка значений массива для подстановки в sql-запрос
	 * (обрамляет кавычками строковые значения)
	 *
	 * @param array $arr
	 */
	private function prepareValues($arr)
	{
		if (count($arr) > 0)
		{
			foreach ($arr as $key => $value)
			{
				if (is_string($arr[$key]))
				{
					$arr[$key] = "'" . $value . "'";
				}
			}
		}
		return $arr;
	}

	/**
	 * Установка флага лицензии фильмов по списку ссылок
	 *
	 */
	public function admin_licenselist($subaction = '', $id = 0)
	{
		Configure::write('debug', 0);
		$this->set('subaction', $subaction);
		$this->set('id', $id);
		switch ($subaction)
		{
			case "getregions":
				$this->layout='ajax';
				App::import('Model', 'Georegion');
				$Georegion = new Georegion();
				$regions = $Georegion->findAll(array(), array('Georegion.id', 'Georegion.name'), null, null, null, 0);
				$this->set('regions', $regions);
				return;
			break;
			case "getregionfilms":
				$this->layout='ajax';
				App::import('Model', 'Georegion');
				$Georegion = new Georegion();
				$region = $Georegion->find(array('Georegion.id' => $id), array('Georegion.id'), null, 1);
				$this->set('films', $region['Film']);
				return;
			break;
		}

		if (empty($this->data))
		{
			//ВЫВОД ФОРМЫ
		}
		else
		{
			set_time_limit(500000);
			$films = array(); //ВЫБОРКА ФИЛЬМОВ ПО СПИСКУ (СООТВЕТСТВУЕТ МАССИВУ ИДЕНТИФИКАТОРОВ)
			$ids = array(); //МАССИВ ИДЕНТИФИКАТОРОВ ФИЛЬМОВ ИЗ СПИСКА
			if (!empty($this->data['lst']))
			{
		        $this->Film->recursive = 0;
				$lst = preg_split('/[\r\n]+/', trim(str_replace('http://', "\n", strtolower($this->data['lst']))));
				$allCnt = count($lst);
				foreach ($lst as $l)
				{
					if (empty($l)) continue;
					$matches = array();
					if (preg_match('/\/media\/view\/([0-9]+)/', $l, $matches))
					{
						if (isset($matches[1]) && !empty($matches[1]))
						{
							if (in_array($matches[1], $ids))
								continue;
							$ids[] = $matches[1];
					        $film = $this->Film->read(array('Film.id'), $matches[1]);
							$film['Film']['is_license'] = intval(!empty($this->data['is_license']));
							$films[] = $film;

							if (!empty($_POST['georegion_id']))
							{
								if (!empty($this->data['setgeo']))
								{
									$sql = 'insert into films_georegions (film_id, georegion_id) values(' . $matches[1] . ', ' . $_POST['georegion_id'] . ');';
								}
								else
								{
									$sql = 'delete from films_georegions where film_id=' . $matches[1] . ' and georegion_id=' . $_POST['georegion_id'] . ';';
								}
//Configure::write('debug', 2);
								$this->Film->query($sql);
//echo $sql;
//exit;
							}
						}
					}
				}
			}
			$res = $this->Film->saveAll($films, array('validate' => false, 'atomic' => false));

			$this->set('allCnt', count($ids));//ОБЩЕЕ КОЛВО ССЫЛОК
			$this->set('addCnt', count($films));//СКОЛЬКО МОДИФИЦИРОВАНО ФИЛЬМОВ
		}
	}

	/**
	 * Установка флага "общественное достояние" (is_public)
	 *
	 */
	public function admin_publiclist()
	{
		Configure::write('debug', 0);
		if (empty($this->data))
		{
			//ВЫВОД ФОРМЫ
		}
		else
		{
			set_time_limit(500000);
			$films = array(); //ВЫБОРКА ФИЛЬМОВ ПО СПИСКУ (СООТВЕТСТВУЕТ МАССИВУ ИДЕНТИФИКАТОРОВ)
			$ids = array(); //МАССИВ ИДЕНТИФИКАТОРОВ ФИЛЬМОВ ИЗ СПИСКА
			if (!empty($this->data['lst']))
			{
		        $this->Film->recursive = 0;
				$lst = preg_split('/[\r\n]+/', trim(str_replace('http://', "\n", strtolower($this->data['lst']))));
				$allCnt = count($lst);
				foreach ($lst as $l)
				{
					if (empty($l)) continue;
					$matches = array();
					if (preg_match('/\/media\/view\/([0-9]+)/', $l, $matches))
					{
						if (isset($matches[1]) && !empty($matches[1]))
						{
							if (in_array($matches[1], $ids))
								continue;
							$ids[] = $matches[1];
					        $film = $this->Film->read(array('Film.id'), $matches[1]);
							$film['Film']['is_public'] = intval(!empty($this->data['is_public']));
							$films[] = $film;
						}
					}
				}
			}
			$res = $this->Film->saveAll($films, array('validate' => false, 'atomic' => false));

			$this->set('allCnt', count($ids));//ОБЩЕЕ КОЛВО ССЫЛОК
			$this->set('addCnt', count($films));//СКОЛЬКО МОДИФИЦИРОВАНО ФИЛЬМОВ
		}
	}

	/**
	 * Установка флага "разрешен только online-просмотр" (just_online)
	 *
	 */
	public function admin_justonline()
	{
		Configure::write('debug', 0);
		if (empty($this->data))
		{
			//ВЫВОД ФОРМЫ
		}
		else
		{
			set_time_limit(500000);
			$films = array(); //ВЫБОРКА ФИЛЬМОВ ПО СПИСКУ (СООТВЕТСТВУЕТ МАССИВУ ИДЕНТИФИКАТОРОВ)
			$ids = array(); //МАССИВ ИДЕНТИФИКАТОРОВ ФИЛЬМОВ ИЗ СПИСКА
			if (!empty($this->data['lst']))
			{
		        $this->Film->recursive = 0;
				$lst = preg_split('/[\r\n]+/', trim(str_replace('http://', "\n", strtolower($this->data['lst']))));
				$allCnt = count($lst);
				foreach ($lst as $l)
				{
					if (empty($l)) continue;
					$matches = array();
					if (preg_match('/\/media\/view\/([0-9]+)/', $l, $matches))
					{
						if (isset($matches[1]) && !empty($matches[1]))
						{
							if (in_array($matches[1], $ids))
								continue;
							$ids[] = $matches[1];
					        $film = $this->Film->read(array('Film.id'), $matches[1]);
							$film['Film']['just_online'] = intval(!empty($this->data['just_online']));
							$films[] = $film;
						}
					}
				}
			}
			$res = $this->Film->saveAll($films, array('validate' => false, 'atomic' => false));

			$this->set('allCnt', count($ids));//ОБЩЕЕ КОЛВО ССЫЛОК
			$this->set('addCnt', count($films));//СКОЛЬКО МОДИФИЦИРОВАНО ФИЛЬМОВ
		}
	}


	/**
	 * Импорт фильмов по списку ссылок
	 *
	 */
	public function admin_importlist($all = '')
	{
return;//НЕПРАВИЛЬНО РАБОТАЕТ
		//Configure::write('debug', 0);
		$this->data['all'] = $all; //ПОЛНЫЙ ИМПОРТ

		if (empty($this->data))
		{
			//ВЫВОД ФОРМЫ
		}
		else
		{
			set_time_limit(500000);
			$picsCmd = '';
			$filmsCmd = '';
			$allCnt = 0;//ОБЩЕЕ КОЛВО ССЫЛОК
			$updCnt = 0;//СКОЛЬКО ФИЛЬМОВ ДОБАВЛЕНО
			$addCnt = 0;
			$films = array(); //ВЫБОРКА ФИЛЬМОВ ПО СПИСКУ (СООТВЕТСТВУЕТ МАССИВУ ИДЕНТИФИКАТОРОВ)
			$ids = array(); //МАССИВ ИДЕНТИФИКАТОРОВ ФИЛЬМОВ ИЗ СПИСКА

			if (!empty($this->data['lst']))
			{
				$this->Film->useDbRecursive('videoCatalog', $this->Film);//БУДЕМ ВЫБИРАТЬ ИЗ БАЗЫ ВИДЕОКАТАЛОГА
		        $this->Film->recursive = 2;
				$lst = preg_split('/[\r\n]+/', trim(str_replace('http://', "\n", strtolower($this->data['lst']))));
//ПРИНУДИТЕЛЬНО УКАЗЫВАЕМ СХЕМУ МОДЕЛИ ТК FilmType VXQ и NSK54 ОТЛИЧАЮТСЯ, А ПЕРЕКЛЮЧЕНИЕ БАЗЫ НЕ СБРАСЫВАЕТ КЭШ МОДЕЛЕЙ
				$this->Film->FilmType->_schema = Array(
				    'id' => Array(
				            'type' => 'integer',
				            'null' => null,
				            'default' => '',
				            'length' => 11,
				            'key' => 'primary',
				        ),
				    'title' => Array(
				            'type' => 'string',
				            'null' => null,
				            'default' => '',
				            'length' => 255
				        )
				);

				$allCnt = count($lst);
				$ids = array();
				foreach ($lst as $l)
				{
					if (empty($l)) continue;
					$matches = array();
					if (preg_match('/\/media\/view\/([0-9]+)/', $l, $matches))
					{
						if (isset($matches[1]) && !empty($matches[1]))
						{
							if (in_array($matches[1], $ids))
								continue;
							$ids[] = $matches[1];
					        $this->Film->contain(array('FilmType',
					                                     'Genre',
					                                     'FilmPicture',
					                                     'Country',
					                                     'Person' => array('PersonPicture', 'Profession'),
					                                     'Publisher',
					                                     'FilmVariant' => array('FilmFile', 'VideoType', 'Track' => array('Language', 'Translation')),
					                                     'MediaRating',
					                             ));
							$films[$matches[1]] = $this->Film->read(null, $matches[1]);
//							$films[$matches[1]]['Film']['is_license'] = 0;
							$films[$matches[1]]['Film']['thread_id'] = 0;
							if (!empty($this->data['is_license']))
								$films[$matches[1]]['Film']['is_license'] = 1;
						}
					}
				}
				$filmsFrom	= Configure::read('Catalog.filmsSrcPath');
				$filmsTo	= Configure::read('Catalog.filmsDestPath');
				$picsFrom	= Configure::read('Catalog.picsSrcPath');
				$picsTo		= Configure::read('Catalog.picsDestPath');

				if (count($ids) > 0)
				{
			        $this->Film->recursive = 2;
					$this->Film->FilmVariant->recursive = 2;
					$this->Film->FilmVariant->Track->recursive = 2;

					$this->Film->useDbRecursive('defaultMedia', $this->Film);//СОХРАНЯТЬ БУДЕМ В "РОДНУЮ" БАЗУ
					foreach ($ids as $id)
					{
						$addCnt++;
						$filmInfo = array();
				        $filmInfo = $films[$id];

				        if (isset($filmInfo['FilmPicture']))
						{
							foreach ($filmInfo['FilmPicture'] as $fp)
							{
								$pathInfo = pathinfo($picsTo . $fp['file_name']);
								$picsCmd .= "md " . $pathInfo['dirname'] . "\r\n";
								$picsCmd .= "copy "  . $picsFrom . $fp['file_name'] ."\t"  . $picsTo . $fp['file_name'] . "\r\n";
								if (strpos($fp['file_name'], 'rames/'))
									$picsCmd .= "copy "  . $picsFrom . preg_replace('#/f(\d)#i', '/s$1', $fp['file_name']) ."\t"  . $picsTo . preg_replace('#/f(\d)#i', '/s$1', $fp['file_name']) . "\r\n";
							}
						}
						$this->unsetEmpty($filmInfo);

						$filmVariant = array();
						$filmVariant = $filmInfo['FilmVariant'];
						unset($filmInfo['FilmVariant']);//СОХРАНИМ РУКАМИ (МЕШАЮТ ВНЕШНИЕ КЛЮЧИ)

						$res = $this->Film->saveAll($filmInfo, array('validate' => false, 'atomic' => false));
						foreach ($filmVariant as $fv)
						{
							$videoType = array();
							$videoType = array('VideoType' => $fv['VideoType']);
							unset($fv['VideoType']);//СОХРАНИМ РУКАМИ (МЕШАЮТ ВНЕШНИЕ КЛЮЧИ)

							$filmFile = array();
							$filmFile = $fv['FilmFile'];
							unset($fv['FilmFile']);//СОХРАНИМ РУКАМИ (МЕШАЮТ ВНЕШНИЕ КЛЮЧИ)

							$track = array();
							$track = $fv['Track'];
							unset($fv['Track']);//СОХРАНИМ РУКАМИ (МЕШАЮТ ВНЕШНИЕ КЛЮЧИ)

							$res = $this->Film->FilmVariant->saveAll($fv);

							$res = $this->Film->FilmVariant->VideoType->save($videoType);

							if (isset($track))
							{
								if (!empty($track['Language']))
								{
									///*
									//8(((
									//непонятно
									//Cake не меняет запрос с инсерта на апдейт, когда запись в базе уже есть
									//как следствие вылетает sql ошибка 'Duplicate entry'
									//это влечет за собой фатальную ошибку в ядре кейка
									//
									if (!$this->Film->FilmVariant->Track->Language->read('id', $track['Language']['id']))
										$res = $this->Film->FilmVariant->Track->Language->save(array('Language' => $track['Language']));
								}
								if (isset($track['Translation']))
								{
									$res = $this->Film->FilmVariant->Track->Translation->save($track['Translation']);
								}
								unset($track['Translation']);
								unset($track['Language']);
								$res = $this->Film->FilmVariant->Track->saveAll(array(0 => $track));
							}

							if (isset($filmFile))
							{
								foreach ($filmFile as $k => $ff)
								{
									$filmFile[$k]['is_lost'] = 0;
									$pathInfo = pathinfo($filmsTo . $ff['file_name']);
									$filmsCmd .= "md " . $pathInfo['dirname'] . "\r\n";
									$filmsCmd .= "copy " . $filmsFrom . $ff['file_name'] ."\t" . $filmsTo . $ff['file_name'] . "\r\n";
								}
								$res = $this->Film->FilmVariant->FilmFile->saveAll($filmFile);
							}
						}

                		if (isset($filmInfo['Country']))
                		{
                			$sql = 'delete from countries_films where film_id = ' . $id;
                			$this->Film->query($sql);
	                		$res = $this->Film->Country->saveAll($filmInfo['Country'], array('validate' => false, 'atomic' => false));
	                		foreach($filmInfo['Country'] as $fc)
	                		{
	                			foreach ($fc['CountriesFilm'] as $key => $value)
	                			{
	                				if (empty($fc['CountriesFilm'][$key]))
	                					$fc['CountriesFilm'][$key] = 'null';
	                			}
	                			$sql = 'insert into countries_films (' . implode (', ', array_keys($fc['CountriesFilm'])). ') values (' . implode (', ', array_values($this->prepareValues($fc['CountriesFilm']))). ')';
	                			$this->Film->query($sql);
	                		}
						}

                		if (isset($filmInfo['Genre']))
                		{
                			$sql = 'delete from films_genres where film_id = ' . $id;
                			$this->Film->query($sql);
	                		$res = $this->Film->Genre->saveAll($filmInfo['Genre'], array('validate' => false, 'atomic' => false));
	                		foreach($filmInfo['Genre'] as $fg)
	                		{
	                			foreach ($fg['FilmsGenre'] as $key => $value)
	                			{
	                				if (empty($fg['FilmsGenre'][$key]))
	                					$fg['FilmsGenre'][$key] = 'null';
	                			}
	                			$sql = 'insert into films_genres (' . implode (', ', array_keys($fg['FilmsGenre'])). ') values (' . implode (', ', array_values($this->prepareValues($fg['FilmsGenre']))). ')';
	                			$this->Film->query($sql);
	                		}
						}

                		if (isset($filmInfo['Publisher']))
                		{
                			$sql = 'delete from films_publishers where film_id = ' . $id;
                			$this->Film->query($sql);
	                		$res = $this->Film->Publisher->saveAll($filmInfo['Publisher'], array('validate' => false, 'atomic' => false));
	                		foreach($filmInfo['Publisher'] as $fg)
	                		{
	                			foreach ($fg['FilmsPublisher'] as $key => $value)
	                			{
	                				if (empty($fg['FilmsPublisher'][$key]))
	                					$fg['FilmsPublisher'][$key] = 'null';
	                			}
	                			$sql = 'insert into films_publishers (' . implode (', ', array_keys($fg['FilmsPublisher'])). ') values (' . implode (', ', array_values($this->prepareValues($fg['FilmsPublisher']))). ')';
	                			$this->Film->query($sql);
	                		}
						}

                		if (isset($filmInfo['Person']))
                		{
	                		$res = $this->Film->Person->saveAll($filmInfo['Person'], array('validate' => false, 'atomic' => false));
                			$sql = 'delete from films_persons where film_id = ' . $id;
                			$this->Film->query($sql);

	                		foreach($filmInfo['Person'] as $fp)
	                		{
								if (isset($fp['PersonPicture']))
								{
			                		$res = $this->Film->Person->PersonPicture->saveAll($fp['PersonPicture'], array('validate' => false, 'atomic' => false));
									foreach ($fp['PersonPicture'] as $pp)
									{
										$pathInfo = pathinfo($picsTo . $pp['file_name']);
										$picsCmd .= "md " . $pathInfo['dirname'] . "\r\n";
										$picsCmd .= "copy "  . $picsFrom . $pp['file_name'] ."\t"  . $picsTo . $pp['file_name'] . "\r\n";
									}
								}
								if (isset($fp['Profession']))
								{
		                			$sql = 'delete from persons_professions where person_id = ' . $fp['id'];
		                			$this->Film->query($sql);
			                		$res = $this->Film->Person->Profession->saveAll($fp['Profession'], array('validate' => false, 'atomic' => false));
									foreach ($fp['Profession'] as $pp)
									{
										foreach ($pp['PersonsProfession'] as $key => $value)
										{
			                				if (empty($pp['PersonsProfession'][$key]))
			                					$pp['PersonsProfession'][$key] = 'null';
			                			}
			                			$sql = 'insert into persons_professions (' . implode (', ', array_keys($pp['PersonsProfession'])). ') values (' . implode (', ', array_values($this->prepareValues($pp['PersonsProfession']))). ')';
			                			$this->Film->query($sql);
									}
								}
	                			foreach ($fp['FilmsPerson'] as $key => $value)
	                			{
	                				if (empty($fp['FilmsPerson'][$key]))
	                					$fp['FilmsPerson'][$key] = 'null';
	                			}
	                			$sql = 'insert into films_persons (' . implode (', ', array_keys($fp['FilmsPerson'])). ') values (' . implode (', ', array_values($this->prepareValues($fp['FilmsPerson']))). ')';
	                			$this->Film->query($sql);
	                		}
						}
//pr($filmInfo);
//break;
					}
/* ПРОВЕДЕНА В ОСНОВНОМ ЦИКЛЕ
//МИГРАЦИЯ ПЕРСОН

					$this->Film->useDbRecursive('videoCatalog', $this->Film);//БУДЕМ ВЫБИРАТЬ ИЗ БАЗЫ ВИДЕОКАТАЛОГА
			        $this->Film->Person->contain(array('PersonPicture', 'Profession'));
			        $lstPersons = $this->Film->Person->findAll(array('Person.modified >' => $lastNskMigrate1['Migration']['modified'], 'Person.modified <=' => $lastNskMigrate2['Migration']['modified']), null, null, null, 1);
					$this->Film->useDbRecursive('defaultMedia', $this->Film);
			        $lstLocalPersons = $this->Film->Person->findAll(null, array('id', 'modified'), null, null, null, 0);
			        $allPersonsModified = array();//ДАТЫ ИЗМЕНЕНИЯ ПО ВСЕМ ПЕРСОНАМ
			        if (!empty($lstLocalPersons))
			        {
			        	foreach ($lstLocalPersons as $lp)
			        	{
			        		$allPersonsModified[$lp['Person']['id']] = $lp['Person']['modified'];
			        	}
			        }

			        if (empty($picsCmd))
			        {
			        	$picsCmd = '';
			        }

			        foreach ($lstPersons as $fp)
			        {
			        	if (empty($allPersonsModified[$fp['Person']['id']]['modified'])
			        		||
			        		$fp['Person']['modified'] > $allPersonsModified[$fp['Person']['id']]['modified'])//ЕСЛИ ПЕРСОНУ НЕ ОБНОВИЛИ ВО ВРЕМЯ ОБНОВЛЕНИЯ ФИЛЬМОВ
			        	{
							if (isset($fp['PersonPicture']))
							{
		                		$res = $this->Film->Person->PersonPicture->saveAll($fp['PersonPicture'], array('validate' => false, 'atomic' => false));
								foreach ($fp['PersonPicture'] as $pp)
								{
									$pathInfo = pathinfo($picsTo . $pp['file_name']);
									$picsCmd .= "md " . $pathInfo['dirname'] . "\r\n";
									$picsCmd .= "copy "  . $picsFrom . $pp['file_name'] ."\t"  . $picsTo . $pp['file_name'] . "\r\n";
								}
							}
							if (isset($fp['Profession']))
							{
	                			$sql = 'delete from persons_professions where person_id = ' . $fp['Person']['id'];
	                			$this->Film->query($sql);
		                		$res = $this->Film->Person->Profession->saveAll($fp['Profession'], array('validate' => false, 'atomic' => false));
								foreach ($fp['Profession'] as $pp)
								{
									foreach ($pp['PersonsProfession'] as $key => $value)
									{
		                				if (empty($pp['PersonsProfession'][$key]))
		                					$pp['PersonsProfession'][$key] = 'null';
		                			}
		                			$sql = 'insert into persons_professions (' . implode (', ', array_keys($pp['PersonsProfession'])). ') values (' . implode (', ', array_values($this->prepareValues($pp['PersonsProfession']))). ')';
		                			$this->Film->query($sql);
								}
							}

                			unset($fp['PersonPicture']);
                			unset($fp['Profession']);
			        		//$this->Film->Person->create();
			        		$fp['Person']['modified'] = date('Y-m-d H:i:s');
			        		$this->Film->Person->save($fp);
			        	}
//pr($fp);
//break;
			        }
//pr(count($lstPersons));
//exit;
*/
			        //ФИКСИРУЕМ ПРОВЕДЕНИЕ МИГРАЦИИ НА VXQ
					$this->Migration->useDbConfig = 'defaultMedia';
					$data = array('Migration' => array('modified' => date('Y-m-d H:i:s')));
					$this->Migration->create();
					$this->Migration->save($data);
				}
			}
//ЗАКОНЧЕН ИМПОРТ ПО СПИСКУ

			if ($this->data['all'])
			{
				//ИЩЕМ ПОСЛЕДНЮЮ МИГРАЦИЮ VXQ
				$lastVXQMigrate = $this->Migration->find(null, array('modified'), 'Migration.modified DESC', 1);
				if (empty($lastVXQMigrate))
					$lastVXQMigrate = array('Migration' => array('modified' => date('Y-m-d H:i:s')));

				//НА НСК54 ИЩЕМ ДАТУ МИГРАЦИИ, ПОСЛЕ КОТОРОЙ БЫЛА МИГРАЦИЯ НА VXQ
				$this->Migration->useDbConfig = 'videoCatalog';//БУДЕМ ВЫБИРАТЬ ИЗ БАЗЫ ВИДЕОКАТАЛОГА
				$lastNskMigrate1 = $this->Migration->find(
					array('Migration.modified <' => $lastVXQMigrate['Migration']['modified']),
					array('modified'),
					'Migration.modified DESC', 1);
				if (empty($lastNskMigrate1))
					$lastNskMigrate1 = array('Migration' => array('modified' => '0000-00-00 00:00:00'));

				//ВЫБИРАЕМ ПОСЛЕДНЮЮ МИГРАЦИЮ НА НСК54
				$lastNskMigrate2 = $this->Migration->find(
					null, array('modified'),
					'Migration.modified DESC', 1);

				$this->Film->useDbRecursive('videoCatalog', $this->Film);//БУДЕМ ВЫБИРАТЬ ИЗ БАЗЫ ВИДЕОКАТАЛОГА
//$sch = $this->Film->FilmType->schema();
//pr($sch);
//exit;
				if (empty($this->data['lst']))
				{
			        $this->Film->recursive = 0;
			        $this->Film->contain(array());
					//ВЫБИРАЕМ МЕЖДУ ДВУХ МИГРАЦИЙ
			        $lstFilms = $this->Film->findAll(array('Film.modified <' => $lastNskMigrate2['Migration']['modified'], 'Film.modified >=' => $lastNskMigrate1['Migration']['modified']), array('id'));
			        $lst = '';
			        foreach ($lstFilms as $lf)
			        {
			        	$lst .= 'http://nsk54.com/media/view/' . $lf['Film']['id'] . "\n";
//break;
			        }
			        $this->data['lst'] = $lst;//ЭМУЛИРУЕМ СПИСОК В ПОЛЕ ФОРМЫ
				}
//pr($lastVXQMigrate);
//pr($lastNskMigrate1);
//pr($lastNskMigrate2);
			}

//СОХРАНИЛИ КОМАНДНЫЙ ФАЙЛ ДЛЯ КОПИРОВАНИЯ КАРТИНОК
			if (!empty($picsCmd))
			{
				$fn = $_SERVER['DOCUMENT_ROOT'] . Configure::read('Catalog.cmdPath') . 'pics_copy.cmd';
				$this->set("picsFileName", $fn);
				if ($f = fopen($fn, 'w+'))
				{
					fwrite($f, $picsCmd);
					fclose($f);
				}
			}

//СОХРАНИЛИ КОМАНДНЫЙ ФАЙЛ ДЛЯ КОПИРОВАНИЯ ФИЛЬМОВ
			if (!empty($filmsCmd))
			{
				$fn = $_SERVER['DOCUMENT_ROOT'] . Configure::read('Catalog.cmdPath') . 'films_copy.cmd';
				$this->set("filmsFileName", $fn);
				if ($f = fopen($fn, 'w+'))
				{
					fwrite($f, $filmsCmd);
					fclose($f);
				}
			}
			$this->set('allCnt', $allCnt);
			$this->set('addCnt', $addCnt);
		}
	}

	function searchWsmedia()
	{
		return $this->searchSphinxIndex('rumedia_post');
	}

	function searchAnimeBar()
	{
		return $this->searchSphinxIndex('animebar_post');
	}

	function searchSphinxIndex($indexName)
	{
        if (!empty($this->params['named']['search']))
        {
            $search = trim(iconv('utf-8', 'windows-1251', $this->params['named']['search']));
	        App::import('Vendor', 'sphinxapi');
			$sphinx = new SphinxClient();
			$sphinx->SetServer('localhost', 3312);
			$sphinx->setLimits(0, 1000);
			$result = $sphinx->Query($search, $indexName);
			unset($sphinx);

			if (!empty($result["matches"]))
			{
				$resultIds = array_keys($result["matches"]);
    			$this->set($indexName . 'PostCount', count($resultIds));
				return $resultIds;
			}

        }
		return array();
	}

	function meta($id = 0, $vId = 0, $tId = 0)
	{
		if (empty($id))
		{
			$this->redirect('/media');
		}

		if (!$film = Cache::read('Catalog.film_view_' . $id,'media'))
	    {
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
		    Cache::write('Catalog.film_view_' . $id, $film,'media');
	    }

	    if (!$film['Film']['active']) {
	        $this->Session->setFlash(__('Invalid Film', true));
	        $this->redirect('/media');
	    }

		if (empty($vId) || empty($tId))
		{
			if (empty($id))
			{
				$this->redirect('/media');
			}
			else
			{
				$this->redirect('/media/view/' . $id);
			}
		}

		$fLst = array();
		foreach($film['FilmVariant'] as $variant)
		{
			if (($variant['id'] == $vId) && ($variant['video_type_id'] == $tId) && (count($variant['FilmFile'] > 0)))
			{
				$fLst = $variant['FilmFile'];
			}
		}

		if (empty($fLst))
		{
			$this->redirect('/media/view' . $id);
		}
		$this->layout = 'playlist';
		$this->set('id', $id);
		$this->set('vId', $vId);
		$this->set('tId', $tId);
		$this->set('fLst', $fLst);
	}

    function index()
    {
        $this->pageTitle = __('Video catalog', true);
        $this->Film->recursive = 1;

        if (empty($this->passedArgs['direction']))
            $this->passedArgs['direction'] = 'desc';

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
        if ($this->isWS)
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

		$conditions = array();
//*
		if ($this->isWS)
		{

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

		}
//*/
		$conditions['Film.active'] = 1;
		$postFix = '';
		//if (!$this->isWS && empty($this->params['named']['search']))//ВНЕШНИМ ПОКАЗЫВАЕМ ЛИЦЕНЗИЮ, ПРИ ПОИСКЕ ВЫВОДИМ ВСЕ
		if (!$this->isWS)//ВНЕШНИМ ПОКАЗЫВАЕМ ТОЛЬКО ЛИЦЕНЗИЮ
		{
			$conditions['Film.is_license'] = 1;
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
/*
                                        'joins' => array(
                                                        array('table' => 'films_genres', 'alias' => 'fg1', 'type' => 'INNER', 'conditions' => 'fg1.film_id = Film.id'),
                                                        //array('table' => 'genres', 'alias' => 'g1', 'type' => 'INNER', 'conditions' => array ("and"=>array ( 'g1.id' => 'fg1.genre_id'), array('g1.id' => 20))),
                                                        array('table' => 'genres', 'alias' => 'g1', 'type' => 'INNER', 'conditions' => '`g1`.`id`=`fg1`.`genre_id` and `g1`.`id` =20'),

                                                        array('table' => 'films_genres', 'alias' => 'fg2', 'type' => 'INNER', 'conditions' => 'fg2.film_id = Film.id'),
                                                        //array('table' => 'genres', 'alias' => 'g2', 'type' => 'INNER', 'conditions' => array ("and"=>array ( 'g2.id' => 'fg2.genre_id'), array('g2.id' => 23))),
                                                        array('table' => 'genres', 'alias' => 'g2', 'type' => 'INNER', 'conditions' => '`g2`.`id`=`fg2`.`genre_id` and `g2`.`id` =23'),
                                                        ),
*/
                                        'order' => $order,
                                        'conditions' => $conditions,
                                        'group' => 'Film.id',
                                        'limit' => 30));


//        pr ($pagination["Film"]);
        //exit;
//*
	if ($this->isWS)
	{
            if ($isFirstPage)
		{
            $pagination['Film']['conditions'][] = array('Film.id' => $rIds);
//	        $order=array('rand()');
		}
    	}
//*/
        if (!empty($this->params['named']['genre']))
        {
            $pagination['Film']['contain'][] = 'FilmsGenre';
            $pagination['Film']['group'] = 'Film.id';
            $genres = $this->params['named']['genre'];
            if (strpos($this->params['named']['genre'], ',') !== false)
                $genres = explode(',', $this->params['named']['genre']);
//
            $condition = 'and';
            //$pagination['Film']['sphinx']['filter'][] = array('genre_id', $genres, false);
            $pagination['Film']['sphinx']['filter'][] = array('genre_id', $genres);
            $find = array($condition => array('FilmsGenre.genre_id' => $genres));

            $pagination['Film']['conditions'][] = $find;



/*[A1]********************************************************
 * 14-09-2011
 * формирование запроса inner join для выборки фильмов в
 * которых есть все выделеные теги-жанры (AND)
 *
 **********************************************************/
/*
SELECT f.id
from films f
join films_genres fg1 on fg1.film_id = f.id
join genres g1 on g1.id = fg1.genre_id and g1.id = 20
join films_genres fg2 on fg2.film_id = f.id
join genres g2 on g2.id = fg2.genre_id and g2.id = 23
 */
/*
 * пример
                                                        array('table' => 'films_genres', 'alias' => 'fg1', 'type' => 'INNER', 'conditions' => 'fg1.film_id = Film.id'),
                                                        array('table' => 'genres', 'alias' => 'g1', 'type' => 'INNER', 'conditions' => '`g1`.`id`=`fg1`.`genre_id` and `g1`.`id` =20'),

                                                        array('table' => 'films_genres', 'alias' => 'fg2', 'type' => 'INNER', 'conditions' => 'fg2.film_id = Film.id'),
                                                        array('table' => 'genres', 'alias' => 'g2', 'type' => 'INNER', 'conditions' => '`g2`.`id`=`fg2`.`genre_id` and `g2`.`id` =23')
*/
/*
<--------- временно убираем изменния контроллера
            if (is_array($genres)){
                $pagination['Film']['joins'] =  array();
                $n=1;
                foreach ($genres as $k=>$v){
                    $pagination['Film']['joins'][]=array('table' => 'films_genres', 'alias' => 'fg'.$n, 'type' => 'INNER', 'conditions' => 'fg'.$n.'.film_id = Film.id');
                    $pagination['Film']['joins'][]=array('table' => 'genres', 'alias' => 'g'.$n, 'type' => 'INNER', 'conditions' => '`g'.$n.'`.`id`=`fg'.$n.'`.`genre_id` and `g'.$n.'`.`id` = '.$v);
                    $n++;
                }
            }
<--------------------------------------------------------
*/
            //pr ($pagination['Film']);
/*[/A1]***********************************************************/

            $this->Film->bindModel(array('hasOne' => array(
                                          'FilmsGenre' => array(
//                                           'className'	=> 'FilmsGenre',
                                           'foreignKey' => 'film_id'
                                          )
                                        )), false);

//
/*

//            $pagination['Film']['conditions'][] = array('FilmsGenre.genre_id' => $genres);
//#            $pagination['Film']['conditions']['FilmsGenre.genre_id'] = $genres;
//#            $pagination['Film']['conditions']['FilmsGenre.genre_id'] = $genres;
            $pagination['Film']['conditions'][] =  array ( "and" => array ('FilmsGenre.genre_id ' => $genres));

//            pr($pagination['Film']);

//#            $pagination['Film']['sphinx']['filter'][] = array('genre_id', $genres);
            $pagination['Film']['sphinx']['filter'][] = array('genre_id', $genres);
//#            $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_BOOLEAN;
//#            $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $this->Film->bindModel(array('hasOne' => array(
                                          'FilmsGenre' => array(
                                           'foreignKey' => 'film_id'
                                          )
                                        )), false);
*/

        }

//pr ($this->Film->_schema);


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

            function transStarChars($txt)
            {
            	$result = '';
				$t = array('е','и','о','а','д','т','ё','б','п','з','с','ь','ж','ъ','ш','щ','ц');

				$t1 = array();//массив в верхнем регистре
				foreach ($t as $key => $value)
					$t1[mb_strtoupper($key)] = mb_strtoupper($value);

				$searchCnt = mb_strlen($txt);
				for($i = 0; $i < $searchCnt; $i++)
				{
					$c = mb_substr($txt, $i, 1);
					if (in_array($c, $t))
					{
						$result .= '*';
						continue;
					}
					elseif (in_array($c, $t1))
					{
						$result .= '*';
						continue;
					}
					else
					{
						$result .= $c;
					}
				}
				return $result;
            }

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
            $pagination['Film']['sphinx']['index'] = array('videoxq_films');//ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ

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
*/
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
    //pr($name);
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

			//$starSearch = transStarChars($search);
            //$pagination['Film']['search'] = $starSearch;
    		$films = $this->Film->find('all', $pagination["Film"]);


//##                //pr ($pagination["Film"]);
//##                //exit;


    		if (empty($films))
    		{

    			if (!empty($translit))
    			{
    				if (!isset($this->params['named']['istranslit']))
    				{
		                $this->redirect(array('action' => 'index',
    	                                  'search' => $translit,
    	                                  'istranslit' => 1,
        	                              'controller' => 'media'));
    				}
    				else
    				{
    					if ($isTranslit)
    					{
			                $this->redirect(array('action' => 'index',
	    	                                  'search' => $translit,
	    	                                  'istranslit' => 0,
	        	                              'controller' => 'media'));
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

			    		if (!empty($search))
		    				$this->_logSearchRequest($search);//В ЛОГ ПОИСКОВЫХ ЗАПРОСОВ ПИШЕМ НЕТРАНСЛИРОВАННЫЙ ЗАПРОС
		    		}
	    		}
			}
			else
			{
	    		if (!empty($search))
	    			$this->_logSearchRequest($search);//В ЛОГ ПОИСКОВЫХ ЗАПРОСОВ ПИШЕМ ТОЛЬКО ПРИ НАЛИЧИИ РЕЗУЛЬТАТОВ ПОИСКА
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
		if (isset($this->params['named']['search']) && $this->isWS) //ЗАПРОС СЧЕТЧИКА К ДРУГИМ КАТАЛОГАМ
		{
			$wsmediaResult = $this->searchWsmedia();
			$animebarResult = $this->searchAnimeBar();
			$this->set('wsmediaPostCount', count($wsmediaResult));
			$this->set('animebarPostCount', count($animebarResult));
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
        }

       	$filmCount = Cache::read('Catalog.' . $postFix . 'count_'.$outCount, 'searchres');
//pr($countation);
//$countation2 = $pagination;
//unset ($countation2['Film']['limit']);


		if (empty($filmCount))
		{
                    if ((empty($this->passedArgs['type'])) && (empty($this->passedArgs['genre'])) && (empty($this->passedArgs['country'])))
			$this->Film->contain(array());//НА ГЛАВНОЙ КОЛИЧЕСТВО ФИЛЬМОВ БЕЗ ПОДЗАПРОСОВ МОЖНО ПОДСЧИТАТЬ
/*[A2]*********************************************************
 * 15-09-2011
 * модификация массива предыдущего запроса из $pagination['Film'], для
 * вычисления общего количества строк в нем, оставляем параметры поиска.
 * Удаляем limit, page, contain, order - для уменьшения нагрузки
 * добавляем 'count(`Film`.`id`) as countrec' - но оно счиает, почему-то
 * количество genres указаных в параметре поиска через join, поэтому получаем
 * общее количество строк в массив и подсчитываем его, освобождая затем
 * занятые всем этим делом переменные
 **********************************************************/

                    $countation = $pagination;
                    //pr($countation);
                    unset($countation["Film"]['limit']);
                    unset($countation["Film"]['page']);
                    unset($countation["Film"]['contain']);
                    unset($countation["Film"]['order']);

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
                        //$countation['Film']['contain'][] = 'FilmsGenre';
                        //$countation['Film']['contain'][] = 'Genre';
                    }
                    $countation['Film']['fields'] = array();
                    $countation['Film']['fields'][] = 'count(`Film`.`id`) as countrec' ;
                    //pr($countation);
                    $filmCount_arr = $this->Film->find('all', $countation["Film"]);

                    //pr($filmCount_arr[0]['countrec']);

                    $filmCount = count($filmCount_arr);
                    unset ($countation);
                    unset ($filmCount_arr);
                    //pr($filmCount);
                    //exit;
/*[/A2]***********************************************************/

// старый вариант - глючный--------------------------
//                    $filmCount = $this->Film->find('count', $countation2['Film']);
//                    pr($countation["Film"]);
//---------------------------------------------------
    		if ((isset($this->passedArgs['page'])) && $filmCount)
    		{
		    	Cache::write('Catalog.' . $postFix . 'count_'.$outCount, $filmCount, 'searchres');
    		}
		}

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
			$pagination['Person']['limit'] = 30;
            $pagination['Person']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Person']['sphinx']['index'] = array('videoxq_persons');//ИЩЕМ ПО ИНДЕКСУ ПЕРСОН
            $pagination['Person']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['Person']['search'] = $search;
            $result = $this->Film->Person->find('all', $pagination["Person"]);

            if (!empty($result))
                $this->redirect(array('action' => 'index',
                                      'search' => urlencode($this->params['named']['search']),
                                      'controller' => 'people'));

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

    function rocket($pageName = 'favorites', $param = '')
    {
    	$this->layout = 'ajax';
    	$this->set('pageName', $pageName);
        App::import('Vendor', 'Utils'); //ДЛЯ КОНВЕРТИРОВАНИЯ ТЭГОВ UBB

        switch ($pageName)
    	{
    		case "favorites":
    			$bookmarks = array();
    			switch ($param)
    			{
    				case "delete":
		    			if (($_SERVER['REQUEST_METHOD'] == 'POST') && !empty($_POST["id"]))
		    			{
		    				$id = intval($_POST["id"]);
		    				$b = $this->Bookmark->read('user_id', $id);
		    				if ($this->authUser['userid'] == $b['Bookmark']['user_id'])
		    				{
		    					$this->Bookmark->delete($id, false);
		    				}
		    			}
    				break;

    				default:
		    			if (($_SERVER['REQUEST_METHOD'] == 'POST') && !empty($_POST["title"]))//ДОБАВЛЯЕМ/ИЗМЕНЯЕМ ССЫЛКУ
		    			{

		    				$bookmark = array('Bookmark' => array(
		    					'title'		=> trim(strip_tags($_POST['title'])),
		    					'modified'	=> date('Y-m-d H:i:s', time()),
		    				));
		    				if (empty($_POST['id']))
		    				{
			    				$this->Bookmark->create();
		    					$bookmark['Bookmark']['url'] = trim(strip_tags($_POST['url']));
		    					$bookmark['Bookmark']['user_id'] = $this->authUser['userid'];
		    					$bookmark['Bookmark']['created'] = date('Y-m-d H:i:s', time());
		    				}
		    				else
		    				{
		    					$bookmark['Bookmark']['id'] = intval($_POST['id']);
		    				}
			    			if (!empty($this->authUser['userid']))
			    			{
		    					$this->Bookmark->save($bookmark);
			    			}
		    			}
		    			if (empty($this->authUser['userid']))
		    			{
		    				$bookmarks = false;
		    			}
		    			else
		    			{
		    				$bookmarks = $this->Bookmark->findAll(array('Bookmark.user_id' => $this->authUser['userid']), array('id', 'url', 'title'), null, null, 0, 0);
		    			}
		    			if (empty($bookmarks))
		    			{
		    				$bookmarks = array(
		    				array('Bookmark' =>
		    					array('url' => '/media/index/direction:desc/action:index/sort:Film.modified', 'title' => __('Video catalog', true)),
			    				),
		    				array('Bookmark' =>
		    					array('url' => '/media/index/type:15', 'title' => __('Serials', true)),
			    				),
		    				array('Bookmark' =>
		    					array('url' => '/forum', 'title' => __('Forum', true)),
			    				),
		    				);
		    			}
    			}
		    	$this->set('bookmarks', $bookmarks);
    		break;

    		case "chat":
    			$condition = null;
    			if (empty($this->rocketInfo['actualchat']))
    			{
    				$this->rocketInfo['actualchat'] = 0;
    			}

    			$vbGroups = Cache::read('Catalog.vbgroups', 'rocket');
				$vbGroups = array();
    			if (empty($vbGroups))
    			{
    				$vgs = $this->Vbgroup->findAll(null, array('usergroupid', 'title', 'usertitle', 'opentag', 'closetag'));
    				$vbGroups = array();
    				if (!empty($vgs))
    				{
    					foreach ($vgs as $vg)
    					{
    						$vbGroups[$vg['Vbgroup']['usergroupid']] = $vg['Vbgroup'];
    					}
    				}
					Cache::write('Catalog.vbgroups', $vbGroups, 'rocket');
    			}
		    	$this->set('vbGroups', $vbGroups);

    			$allSmiles = Cache::read('Catalog.rocket_smiles', 'rocket');
    			if (empty($allSmiles))
    			{
	    			$smiles = $this->Smile->findAll();
	    			$allSmiles = array();
	    			foreach($smiles as $s)
	    			{
	    				$src = '/forum/' . $s['Smile']['smiliepath'];
	    				$size = getimagesize('http://' . $_SERVER['HTTP_HOST'] . $src);
	    				if (!$size)
	    				{
	    					continue;
	    				}
	    				$allSmiles[$s['Smile']['smilietext']] = '<img ' . $size[3] . ' src="/forum/' . $s['Smile']['smiliepath'] . '" alt="' . htmlspecialchars($s['Smile']['title']) . '" title="' . htmlspecialchars($s['Smile']['title']) . '" />';
	    			}
					Cache::write('Catalog.rocket_smiles', $allSmiles, 'rocket');
    			}
		    	$this->set('allSmiles', $allSmiles);

    			if (!empty($param))
    			{
    				$condition = array('CybChat.dateline > ' => $this->rocketInfo['actualchat']);
    			}
    			else //СМАЙЛИКИ ГРУЗИМ ТОЛЬКО ПРИ ПОЛНОЙ ПЕРЕЗАГРУЗКЕ ЧАТА
    			{
	    			$defSmilies = array(':angel:', ':bigwink:', ':diablo:', ':dribble:', ':jamie:', ':mamba:', ':crazyeyes:', ':dirol:', ':-(', '=)');
	    			$smilies = array();
	    			foreach($allSmiles as $key => $s)
	    			{
	    				if (!in_array($key, $defSmilies))
	    				{
	    					continue;
	    				}
	    				$smilies[] = array(
	    					'code'	=> $key,
	    					'img'	=> $s,
	    				);
	    			}
			    	$this->set('smilies', $smilies);
    			}

    			$chatMessages = $this->CybChat->findAll($condition, array('CybChat.userid', 'CybChat.dateline', 'CybChat.message', 'CybChat.textprop', 'User.username', 'User.userid', 'User.displaygroupid'), 'CybChat.dateline desc', 30, null, 1);
    			if (!empty($chatMessages))
    			{
    				foreach ($chatMessages as $key => $val)
    				{
    					$chatMessages[$key]['CybChat']['message'] = Utils::transUbbTags($val['CybChat']['message']);
    				}
    				$this->rocketInfo['actualchat'] = $chatMessages[0]['CybChat']['dateline'];
					$this->Session->write('rocketInfo', $this->rocketInfo); //И ДЛЯ АВТОРИЗОВАННЫХ И ДЛЯ ГОСТЕЙ РАБОТАЕМ С СЕССИЕЙ
    			}
		    	$this->set('chatMessages', $chatMessages);
		    	$this->set('newMsg', $param);

    		break;

    		case "news":
    		break;

    		case "add":
    			if ($_SERVER['REQUEST_METHOD'] == 'POST')//ДОБАВЛЯЕМ ССЫЛКУ
    			{
    				$this->CybChat->create();
    				$data = array();
    				$data['CybChat']['message'] = trim(strip_tags(
    					//iconv('utf8', 'windows-1251',
    					Utils::stripUbbTags($_POST['message'])
    					//)
    					));
    				$data['CybChat']['userid'] = $this->authUser['userid'];
    				$data['CybChat']['userip'] = $_SERVER['REMOTE_ADDR'];
    				$data['CybChat']['dateline'] = time();
    				$textprop = array(
    					'color' => $_POST['color'],
    					'bold' => ($_POST['bold'] == 'bold') ? 'bold' : '',
    					'underline' => ($_POST['underline'] == 'underline') ? 'underline' : '',
    					'italic' => ($_POST['italic'] == 'italic') ? 'italic' : '',
    				);
    				$data['CybChat']['textprop'] = serialize($textprop);
    				if (!empty($data['CybChat']['message']))
    				{
	    				$this->CybChat->save($data);
    				}
    			}
    		break;

    		case "save":
				$this->rocketInfo = $_POST;
				if (empty($_POST['actualchat']))
				{
					$ri = $this->Session->read('rocketInfo');
					$this->rocketInfo['actualchat'] = $ri['actualchat'];
				}

				$this->Session->write('rocketInfo', $this->rocketInfo); //И ДЛЯ АВТОРИЗОВАННЫХ И ДЛЯ ГОСТЕЙ РАБОТАЕМ С СЕССИЕЙ
    			if (!empty($this->authUser['userid'])) //ДЛЯ АВТОРИЗОВАННЫХ ДУБЛИРУЕМ В КЭШ
    			{
					Cache::write('Catalog.rocket_' . $this->authUser['userid'] , $this->rocketInfo, 'rocket');
    			}
    		break;
    	}
    	$this->set('rocketInfo', $this->rocketInfo);
    	$this->set('authUser', $this->authUser);
    }

    function vasilina()
    {
    	$this->layout = 'ajax';
    }

    /**
     * найти фильмы с похожим названием
     *
     * @param string $title
     * @return array
     */
    function looksLike($title)
    {
    	$films = array(); $searchFor = '';
    	$pos = mb_strpos(mb_strtolower($title), __('season', true));
/*
    	if ($pos)
    	{
    		$searchFor = trim(mb_substr($title, 0, $pos));
    	}
    	else
    	{
    		$matches = array();
    		preg_match('/([^0-9]+)(.*?)[0-9]+/', $title, $matches, PREG_OFFSET_CAPTURE);
    		if (isset($matches[1][0]))
    		{
    			$searchFor = $matches[1][0];
    		}
//pr($matches);
    	}
*/
//ВЫРЕЗАЕМ ДО ЦИФР
		$matches = array();
		preg_match('/([^0-9]+)(.*?)[0-9]+/', $title, $matches, PREG_OFFSET_CAPTURE);
		if (isset($matches[1][0]))
		{
			$searchFor = $matches[1][0];
		}

//pr($searchFor);
    	if (!empty($searchFor) && ($searchFor <> $title))
    	{
			if (!$films = Cache::read('Catalog.film_lookslike_' . $searchFor, 'searchres'))
			{
		        $this->Film->recursive = 0;
	    		$this->Film->contain(array());
				$pagination['Film']['limit'] = 20;
	            $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
	            $pagination['Film']['sphinx']['index'] = array('videoxq_films');//ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ
	            $pagination['Film']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
	            $pagination['Film']['search'] = $searchFor;
	    		$films = $this->Film->find('all', $pagination["Film"]);
			    Cache::write('Catalog.film_lookslike_' . $searchFor, $films, 'searchres');
			}
//pr($films);
    	}
    	return $films;
    }

    function getdivx($filmFileId)
    {
    	$this->layout = 'ajax';
    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
        if (empty($this->authUser['userid']))
    		$allowDownload = false;
        $this->set('allowDownload', $allowDownload);
        if ($allowDownload)
        {
	        $this->Film->FilmVariant->FilmFile->recursive = 0;
	        $this->Film->FilmVariant->FilmFile->contain(array(
	                                     'FilmVariant' => array('Film')
	                                  )
	                             );
	    	$filmFile = $this->Film->FilmVariant->FilmFile->find(array('FilmFile.id' => $filmFileId), array('film_variant_id', 'file_name'), '' , 2);
	    	$this->set('filmFile', $filmFile);
        }
    }

    function lite($id = null, $param = null)
    {
    	$inId = $id;
    	$this->layout = 'lite';
		if (isset($_SESSION["lastFilms"]))
		{
			$lastFilms = $_SESSION["lastFilms"];
			if (!isset($lastFilms[$id]))
			{
				$id = 0;
			}
		}
		else
		{
			$id = 0;
		}

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);

		if (empty($id))
		{
			$url = '/media';
			if (!empty($inId))
				$url .= '/view/' . intval($inId);
			$this->redirect($url);
			return;
		}
		$lastFilms[0] = array('id' => 0, 'title' => '', 'description' => '');

		$film = $lastFilms[$id];

	   	if (isset($_SESSION["lastIds"]))
	           	$lastIds = $_SESSION["lastIds"];
	   	else
	           	$lastIds = array();
	   	$lastIds[$film['id']] = $film['id'];
	  	$_SESSION["lastIds"] = $lastIds;

	  	$playSwitch = '';

		if (!empty($id))
		{
			if (empty($_COOKIE['playSwitch'])) //ИНИЦИАЛИЗИРУЕМ
			{
				setcookie('playSwitch', 'playoff', time() + 60*60*24*30, '/');
				$paramC = 'playon';
			}
			else
			{
				$paramC = $_COOKIE['playSwitch'];
			}

			if (empty($param))
			{
				$param = $paramC;
			}
			setcookie('playSwitch', $param, time() + 60*60*24*30, '/');
		}

		$this->set('metaDescription', $film['title']);
		$this->set('lastFilms', $lastFilms);
		$this->set('film', $film);
		$this->set('lastIds', $lastIds);
		$this->set('param', $param);
		$this->set('playSwitch', $playSwitch);
    	$this->set('id', $id);
    }

    function getbanner($place = '', $dec = 0)
    {
    	$this->layout = 'playlist';
    	$this->set('place', $place);
    	$this->set('dec', $dec);
    	$this->set('hitCnt', '');
    	//$this->set('hitCnt', $this->Session->read('hitCnt') + 2);
    }

    function ozon($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Film', true));
            $this->redirect(array('action'=>'index'));
        }
		if (!$film = Cache::read('Catalog.film_view_' . $id,'media'))
	    {
	        $this->Film->recursive = 0;
	        $this->Film->contain(array('FilmType',
	                                     'Genre',
	                                     'Thread',
	                                     'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
	                                     'Country',
	                                     'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
	                                     'MediaRating',
	                                     //'FilmComment' => array('order' => 'FilmComment.created ASC',
	                                                            //'conditions' => array('FilmComment.hidden' => 0))
	                                  )
	                             );
	        $film = $this->Film->read(null, $id);
		    Cache::write('Catalog.film_view_' . $id, $film, 'media');
	    }
		$ozons = Cache::read('Catalog.ozon_' . $id, 'ozon');
		if (empty($ozons))//ЕСЛИ СПИСОК ТОВАРОВ ПУСТ
		{
	    	$this->redirect('/media/view/' . $id);
		}
		$this->set('film', $film);
		$this->set('ozons', $ozons);
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $this->pageTitle = __('Video catalog', true) . ' - ' . $film['Film']['title' . $langFix] . ' - ' . __('Buy on', true) . ' ozon.ru';
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);
    }

    function findlinks($filmId = 0)
    {
    	$this->layout = 'ajax';
    	$film = array();
		if (!empty($filmId))
		{
			$film = $this->Film->find(array('Film.id' => intval($filmId)));

			Cache::delete('Catalog.film_view_' . $film['Film']['id'], 'media');
/*
			$ch = curl_init();
			$q = urlencode($film['Film']['id'] . ' ' . __('download', true));
			curl_setopt($ch, CURLOPT_URL, Configure::read('App.webShare') . "search/bygroup/$q&rsz=large&hl=ru");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, Configure::read("App.siteUrl"));
			$body = curl_exec($ch);
			curl_close($ch);
*/
		    $geoInfo = $this->Session->read('geoInfo');

		    $geoIsGood = false;
		    if (!empty($geoInfo))
		    {
		    	if (!empty($geoInfo['Geoip']['region_id']) || !empty($geoInfo['Geoip']['city_id']))
		    	{
		    		//ТК база GeoIP СОДЕРЖИТ ТОЛЬКО РОССИЙСКИЕ АДРЕСА
		    		//ЛИЦЕНЗИЯ ДЕЙСТВУЕТ НА ВСЮ РОССИЮ
		    		$geoIsGood = $film['Film']['is_license'];
		    	}
		    }

		    $googleContent = array();
			$ch = curl_init();
			$q = urlencode($film['Film']['title'] . ' ' . __('download', true));
//			if($this->authUser['userid']==2){$q ='maatrix';}

//$q = urlencode("'atrn ,fjxrb");

			curl_setopt($ch, CURLOPT_URL, "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=$q&rsz=large&hl=ru&spell=1");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, Configure::read("App.siteUrl"));
			$body = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($body);
			$googleContent = $json->responseData->results;
//			if($this->authUser['userid']==2){echo "<pre>";print_r($json);die();}
			$variantId = 0;
//*
//ИЩЕМ В СВЯЗЯХ ФИЛЬМА ВАРИАНТ ССЫЛОК
			$webTypeId = Configure::read('App.webTypeId');
			if (!empty($film['FilmVariant']))
			{
				foreach ($film['FilmVariant'] as $variant)
				{
					if ($variant['video_type_id'] == $webTypeId)
					{
						$variantId = $variant['id'];
					}
				}
			}

			if (empty($variantId))
			{
//СОЗДАЕМ, ЕСЛИ НЕ НАШЛИ
				$variant = array('FilmVariant'	=> array(
					'film_id'		=> $film['Film']['id'],
					'video_type_id'	=> $webTypeId,
					'resolution'	=> '',
					'duration'		=> '',
					'active'		=> 1,
					'created'		=> date('Y-m-d H:i:s'),
					'modified'		=> date('Y-m-d H:i:s'),
					'flag_catalog'	=> 1
				));
				$this->Film->FilmVariant->create();
				$this->Film->FilmVariant->save($variant);
				$variantId = $this->Film->FilmVariant->getLastInsertId();
			}

			$this->Film->FilmVariant->FilmLink->deleteAll(array('FilmLink.film_variant_id' => $variantId));
//*/
			$links = array();
//ПОИСК В ОБМЕННИКЕ
			$ch = curl_init();
			$q = urlencode($film['Film']['id']);
			curl_setopt($ch, CURLOPT_URL, Configure::read("App.webShare") . "catalog/searchajax/bygroup/$q");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, Configure::read("App.siteUrl"));
			$body = curl_exec($ch);
			if (empty($body) || curl_errno($ch))
			{
				$body = array();
			}
			else
			{
				$body = unserialize($body);
				if (!$body)
				{
					$body = array();
				}
			}
			curl_close($ch);
			$this->set('shareContent', $body);

			foreach($body as $res)
			{
				$links[] = array('FilmLink' => array(
					//"link"	=> $res->visibleUrl,
					"link"	=> $res['url'],
					"zone"	=> '',
					"film_variant_id"	=> $variantId,
					"title"	=> $res['title'],
					"filename"	=> $res['filename'],
					"descr"	=> $res['content'],
					"dt"	=> date('Y-m-d H:i:s'),
				));
			}

			if (!empty($googleContent))
			{
				foreach($googleContent as $res)
				{
					$links[] = array('FilmLink' => array(
						//"link"	=> $res->visibleUrl,
						"link"	=> $res->url,
						"zone"	=> 'web',//НАШЛИ В ИНТЕРНЕТ
						"film_variant_id"	=> $variantId,
						"title"	=> $res->title,
						"descr"	=> $res->content,
						"filename"	=> '',
						"dt"	=> date('Y-m-d H:i:s'),
					));
				}
			}
			$this->Film->FilmVariant->FilmLink->create();
			$this->Film->FilmVariant->FilmLink->saveAll($links, array('validate' => false, 'atomic' => false));

			$this->set('googleContent', $googleContent);
		}
		$this->set('film', $film);
		$lang = Configure::read('Config.language');
		$langFix = '';
		/*
		if ($lang == _ENG_)
		{
			$langFix = '_' . _ENG_;
	        App::import('Vendor', 'IMDB_Parser', array('file' => 'class.imdb_parser.php'));
	        App::import('Vendor', 'IMDB_Parser2', array('file' => 'class.imdb_parser2.php'));
            $parser = new IMDB_Parser2();
            $this->set('parser', $parser);
            $this->set('imdb_website', (empty($imdb_website) ? '' : $imdb_website));
		}
		*/
		$this->set('lang', $lang);
		$this->set('geoIsGood', $geoIsGood);

    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Film', true));
            $this->redirect(array('action'=>'index'));
        }

		if (!$film = Cache::read('Catalog.film_view_' . $id,'media'))
	    {
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
		    Cache::write('Catalog.film_view_' . $id, $film,'media');
	    }
	    if (!$film['Film']['active']) {
	        $this->Session->setFlash(__('Invalid Film', true));
	        $this->redirect(array('action'=>'index'));
	    }

        App::import('Vendor', 'Utils'); //ДЛЯ КОНВЕРТИРОВАНИЯ ТЭГОВ UBB

	    //for ($i = 0; $i < 100; $i++)
        //	$rFilm = $this->Film->getRandomFilm();

        $cookie = $this->Cookie->read('Film.hits');
        if (!$cookie || (isset($cookie[$id]) && $cookie[$id] < (time() - 24*60*60))
            || !isset($cookie[$id]))
        {
            $cookie[$id] = time();
            $this->Film->updateHits($id);
        }

        $this->Cookie->write('Film.hits', $cookie, true, '+1 day');

        $this->set('votingAllowed', true);
        $cookie = $this->Cookie->read('Voting.film');
        if ($cookie && isset($cookie[$id]) && $cookie[$id] > (time() - 24*60*60))
        {
            $this->set('votingAllowed', false);
        }

        //TODO: remove this, after https://trac.cakephp.org/ticket/5420 fixed
        if (!empty($this->data['FilmComment']))
        {
        if (isset($this->data['FilmComment']['captcha']))
            $this->data['FilmComment']['captcha2'] = $this->Session->read('captcha');

            if ($this->authUser['userid'])
            {
                $this->data['FilmComment']['user_id'] = $this->authUser['userid'];
                $this->data['FilmComment']['username'] = $this->authUser['username'];
                $this->data['FilmComment']['email'] = $this->authUser['email'];
            }
        	if (!$this->User->validates())
        	{
            $this->Session->setFlash(__('Error. Comment not added', true));
            unset($this->data['User']['captcha']);
            unset($this->data['User']['captcha2']);
        	}
            else{
            $this->data['FilmComment']['ip'] = $_SERVER['REMOTE_ADDR'];
            $this->FilmComment->create();
            //if ($this->isAuthorized('FilmComments/add') && $this->FilmComment->save($this->data))
            if (($this->authUser['userid']) && /*$this->isAuthorized('FilmComments/add') &&*/ $this->FilmComment->save($this->data))
            {
                Cache::delete('Catalog.lastComments');
                $this->Session->setFlash(__('Comment added', true));
                unset($this->data['FilmComment']['text']);
                //$this->redirect($this->referer('/media'));
            }

            else
            {
                $this->Session->setFlash(__('Error. Comment not added', true));
                //$this->redirect($this->referer('/media'));
            }}
        }

    $similars = Cache::read('Catalog.film_similar_' . $id, 'media');
	if ($similars === false)
	{
		$similars = $this->SimilarFilm->findAll(array("FIND_IN_SET('$id', SimilarFilm.films)"));
		$ids = array();
		foreach ($similars as $s)
		{
			$ids = array_merge($ids, explode(',', $s['SimilarFilm']['films']));
		}

		if (!empty($ids))
		{
			$ids = array_unique($ids);
			$this->Film->contain(array());
			$similars = $this->Film->findAll(array('Film.id' => $ids), array('Film.id', 'Film.title'));
		}
	    Cache::write('Catalog.film_similar_' . $id, $similars,'media');
	}
	$this->set('similars', $similars);


/*
//TEST
$variant = $this->Film->FilmVariant->find(array('FilmVariant.film_id' => $id, array("OR" => array("FilmVariant.flag_catalog" => 0, "FilmVariant.flag_catalog IS NULL"))));
$this->set("variant", $variant);
$catalogVariants = $this->Film->FilmVariant->findAll(array('film_id' => $id, "flag_catalog" => 1), null, null, null, null, 1);
//ОПРЕДЕЛИМ МАССИВ ИД "каталожных" вариантов
$catVarIds = array();
if (count($catalogVariants) > 0)
{
	foreach ($catalogVariants as $c)
		$catVarIds[] = $c["FilmVariant"]["id"];
}
//ТЕСТИРУЕМ УСЛОВИЕ NOT IN
//$catalogVariants = $this->Film->FilmVariant->findAll(array('FilmVariant.film_id' => $id, "NOT" => array("FilmVariant.id" => $catVarIds)), null, null, null, null, 1);
$this->set("catalogVariants", $catalogVariants);
//END OF TEST
//*/

//ПРОВЕРКА НА РАЗРЕШЕНИЕ ПОКАЗА НА ДАННОЙ ТЕРРИТОРИИ
	    $geoInfo = $this->Session->read('geoInfo');

//* ОТКЛЮЧАЕМ ГЕО ФИЛЬТР ВРЕМЕННО
	    $geoIsGood = false;
	    if (!empty($geoInfo))
	    {
	    	if (!empty($geoInfo['Geoip']['region_id']) || !empty($geoInfo['Geoip']['city_id']))
	    	{
	    		//ТК база GeoIP СОДЕРЖИТ ТОЛЬКО РОССИЙСКИЕ АДРЕСА
	    		//ЛИЦЕНЗИЯ ДЕЙСТВУЕТ НА ВСЮ РОССИЮ
	    		$geoIsGood = $film['Film']['is_license'];
	    	}
	    	if (!empty($geoInfo['Geoip']['region_id']))
	    	{
	    		//ПРОВЕРЯЕМ, ОГРАНИЧЕНА ЛИ ЛИЦЕНЗИЯ РЕГИОНОМ
	    		$sql = 'select georegion_id from films_georegions where film_id = ' . $film['Film']['id'];
	    		$res = $this->Film->query($sql);
	    		if ($res)
	    		{
	    			$geoIsGood = false;
	    			foreach ($res as $r)
	    			{
	    				if ($r['films_georegions']['georegion_id'] == $geoInfo['Geoip']['region_id'])
	    				{
		    				$geoIsGood = true;
		    				break;
	    				}
	    			}
	    		}
	    	}
/*
ОГРАНИЧЕНИЕ ПО ГОРОДАМ ПОКА НЕ РЕАЛИЗОВАНО В ИНТЕРФЕЙСЕ АДМИНИСТРАТОРА
	    	if ((!$geoIsGood) && (!empty($geoInfo['Geoip']['city_id'])))
	    	{
	    		//ПРОВЕРЯЕМ, ОГРАНИЧЕНА ЛИ ЛИЦЕНЗИЯ ГОРОДОМ
	    		$sql = 'select geocity_id from films_geocities where film_id = ' . $film['Film']['id'];
	    		$res = $this->Film->query($sql);
	    		if ($res)
	    		{
	    			$geoIsGood = false;
	    			foreach ($res as $r)
	    			{
	    				if ($r['films_geocities']['geocity_id'] == $geoInfo['Geoip']['city_id'])
	    				{
		    				$geoIsGood = true;
		    				break;
	    				}
	    			}
	    		}
	    	}
//*/
	    }
//*/

	    $this->set('geoIsGood', $geoIsGood);

		list($filmModifyDate, $filmModifyTime) = explode(' ', empty($film['Film']['modified']) ? '000-00-00 00:00:00' : $film['Film']['modified']);
		$filmModifyDate = explode('-', $filmModifyDate);
		$filmModifyTime = explode(':', $filmModifyTime);
		$metaExpires = date('r', mktime($filmModifyTime[0], $filmModifyTime[1], $filmModifyTime[2], $filmModifyDate[1], $filmModifyDate[2], $filmModifyDate[0]));
		$this->set('metaExpires', $metaExpires);

		//$metaRobots = 'INDEX, NOFOLLOW';
		//$this->set('metaRobots', $metaRobots);

   	    $persons = $this->Film->getFilmPersons($id);

        $out = array(); $directors = array();
        foreach ($persons as $person)
        {
            if (!isset($out[$person['Person']['id']]))
            {
                unset($person['FilmsPerson']);
                if ($person['Profession']['id'] == 1)
                {
                	$directors[] = $person['Person']['name'];
                }
                $person['Profession'] = array($person['Profession']['id'] => $person['Profession']['title']);
                $out[$person['Person']['id']] = $person;
            }
            else
            {
                $out[$person['Person']['id']]['Profession'][$person['Profession']['id']] = $person['Profession']['title'];
            }
        }

        if (!isset($directors[0]))
        	$directors[0] = '';
        else
        	$directors[0] = "режиссер " . $directors[0] . ", ";
        $this->set("metaDescription", ". фильм " . htmlspecialchars($film['Film']['title']) . " (" . $directors[0] . $film["Film"]["year"] . ")");

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        //$this->pageTitle = __('Video catalog', true) . ' - ' . $film['Film']['title' . $langFix];
        $this->pageTitle = '-' . $film['Film']['title' . $langFix]."-".__('wordsSEO',true);
        $this->set('film', $film);
        $this->set('lang', $lang);
		$this->set('langFix', $langFix);

        if ($lang == _ENG_)
        {
        	$imdb_website = Cache::read('Catalog.film_imdbinfo_' . $id, 'searchres');
        	if (empty($imdb_website))
        	{
        		if (!empty($film['Film']['imdb_id']))
        		{
	        		$fn = 'http://imdb.com/title/' . $film['Film']['imdb_id'];
            		$imdb_website = file_get_contents($fn);
		    		Cache::write('Catalog.film_imdbinfo_' . $id, $imdb_website, 'searchres');
        		}
        	}
	        App::import('Vendor', 'IMDB_Parser', array('file' => 'class.imdb_parser.php'));
	        App::import('Vendor', 'IMDB_Parser2', array('file' => 'class.imdb_parser2.php'));
            $parser = new IMDB_Parser2();
            $this->set('parser', $parser);
            $this->set('imdb_website', $imdb_website);
        }

        $looksLike = $this->looksLike($film['Film']['title']);
        $this->set('looksLike', $looksLike);

        if(!$film)$this->redirect(array('action'=>'index'));
        $this->set('persons', $out);
        $this->set('allowEdit', $this->isAuthorized('FilmComments/admin_delete'));
        $this->set('allowDownload', checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']));
        $this->set('allowDownload', $film['Film']['is_license'] & !empty($this->authUser['userid']));

		$ozons = Cache::read('Catalog.ozon_' . $id, 'ozon');
		if ($ozons === false)//ЕСЛИ ЕЩЕ НЕ КЭШИРОВАЛИ
		{
			$pagination = array();
	        $pagination = array('OzonProduct' => array(
                                        'order' => '',
                                        'conditions' => array('OzonProduct.avail >' => 0),
                                        'group' => 'OzonProduct.id',
                                        'limit' => 20));
            $pagination['OzonProduct']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['OzonProduct']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['OzonProduct']['sphinx']['index'] = array('videoxq_ozon');//ИЩЕМ ПО ИНДЕКСУ ПРОДУКТОВ ОЗОНА

            if (!empty($film['Film']['title']))
            {
            	$pagination['OzonProduct']['search'] = $film['Film']['title'];
	    		$ozonsByTitle = $this->OzonProduct->find('all', $pagination["OzonProduct"]);
            }
            else
            	$ozonsByTitle = array();

            if (!empty($film['Film']['title_en']))
            {
            	$pagination['OzonProduct']['search'] = $film['Film']['title_en'];
    			$ozonsByOriginal = $this->OzonProduct->find('all', $pagination["OzonProduct"]);
            }
            else
            	$ozonsByOriginal = array();
    		$ozons = array();
    		$ozonIds = array();
    		foreach ($ozonsByOriginal as $key => $val)
    		{
    			$oid = $val['OzonProduct']['id'];
    			if (empty($ozonIds[$oid]))//ЧТОБЫ НЕ БЫЛО ДУБЛЕЙ
    			{
    				$ozons[] = $val;
    				$ozonIds[$oid] = $oid;
    			}
    		}
    		foreach ($ozonsByTitle as $key => $val)
    		{
    			$oid = $val['OzonProduct']['id'];
    			if (empty($ozonIds[$oid]))//ЧТОБЫ НЕ БЫЛО ДУБЛЕЙ
    			{
    				$ozons[] = $val;
    				$ozonIds[$oid] = $oid;
    			}
    		}
			Cache::write('Catalog.ozon_' . $id, $ozons, 'ozon');
		}
        $this->set('ozons', $ozons);

        App::import('Vendor', 'uuconverter');
		$uuConverter = new uuConverter();

		$threadInfo = array();
		$forumInfo = $this->Forum->read(null, Configure::read('forumId'));
		if (!empty($forumInfo))
		{
			//$threadData = $this->Thread->find(array('Thread.film_id' => $film['Film']['id']));
			$threadData = array('Thread' => $film['Thread']);
		}
        if ($this->authUser['userid'] > 0)
        {
        	$ban = $this->Userban->read(null , $this->authUser['userid']);
        	if ($ban)
        	{
        		if ((empty($ban['Userban']['liftdate'])) || ($ban['Userban']['liftdate'] > time()))
        		{
        			$this->data['Vbpost']['pagetext'] = '';
        		}
        		else
        		{
        			$ban = false;
        		}
        	}
        	$this->set('ban', $ban);

        	//$vbulletin = $this->Vb->vbInit();
	        if (!empty($this->data['Vbpost']['pagetext']))
	        {
				Cache::delete('Catalog.film_view_' . $id, 'media');

	        	//БЛАНК СТРУКТУРЫ ПОСТА
	        	$postData = array('Vbpost' => array());
	        	$postData['Vbpost']['threadid'] = 0;
	        	$postData['Vbpost']['parentid'] = 0;
	        	$postData['Vbpost']['username'] = "MediaRobot";
	        	$postData['Vbpost']['userid'] = 64;
	        	$postData['Vbpost']['title'] = '';
	        	$postData['Vbpost']['dateline'] = time();
	        	$postData['Vbpost']['pagetext'] = $uuConverter->utfToUnicode(strip_tags(Utils::stripUbbTags($this->data['Vbpost']['pagetext'])));
	        	$postData['Vbpost']['allowsmilie'] = 0;
	        	$postData['Vbpost']['showsignature'] = 0;
	        	$postData['Vbpost']['ipaddress'] = $_SERVER['REMOTE_ADDR'];
	        	$postData['Vbpost']['iconid'] = 0;
	        	$postData['Vbpost']['visible'] = 1;
	        	$postData['Vbpost']['attach'] = 0;
	        	$postData['Vbpost']['infraction'] = 0;
	        	$postData['Vbpost']['reportthreadid'] = 0;

	        	//СОХРАНЕНИЕ СООБЩЕНИЯ В ФОРУМ ОТ ЗАРЕГИСТРИРОВАННОГО ЮЗЕРА
	        	//if (empty($threadData['Thread']['threadid']))//СОЗДАНИЕ НОВОЙ ВЕТКИ
	        	if (empty($film['Film']['thread_id']) || empty($film['Thread']))//СОЗДАНИЕ НОВОЙ ВЕТКИ
	        	{
	        		$threadData = array('Thread' => array());

	        		$threadTxts = $this->Film->getThreadText($film);
					$threadData['Thread']['title'] = $threadTxts['title'];
					$threadData['Thread']['pagetext'] = $threadTxts['text'];

	        		//СОХРАНЕНИЕ НОВОЙ ВЕТКИ
	        		$threadData['Thread']['prefixid'] = '';
	        		$threadData['Thread']['firstpostid'] = 0;//НУЖНО ОБНОВИТЬ ПОСЛЕ ДОБАВЛНИЯ ПОСТА
	        		$threadData['Thread']['lastpostid'] = 0;//НУЖНО ОБНОВИТЬ ПОСЛЕ ДОБАВЛНИЯ ПОСТА
	        		$threadData['Thread']['lastpost'] = time();
	        		$threadData['Thread']['forumid'] = $forumInfo['Forum']['forumid'];
	        		$threadData['Thread']['pollid'] = 0;
	        		$threadData['Thread']['open'] = 1;
	        		$threadData['Thread']['replycount'] = 1;
	        		$threadData['Thread']['hiddencount'] = 0;
	        		$threadData['Thread']['deletedcount'] = 0;
	        		$threadData['Thread']['postusername'] = "MediaRobot";
	        		$threadData['Thread']['postuserid'] = 64;
	        		$threadData['Thread']['lastposter'] = $uuConverter->utfToUnicode($this->authUser['username']);
	        		$threadData['Thread']['dateline'] = time();
	        		$threadData['Thread']['views'] = 0;
	        		$threadData['Thread']['iconid'] = 0;
	        		$threadData['Thread']['notes'] = '';
	        		$threadData['Thread']['visible'] = 1;
	        		$threadData['Thread']['sticky'] = 0;
	        		$threadData['Thread']['votenum'] = 0;
	        		$threadData['Thread']['votetotal'] = 0;
	        		$threadData['Thread']['attach'] = 0;
	        		$threadData['Thread']['showfirstpost'] = 1;
	        		$threadData['Thread']['similar'] = '';
	        		$threadData['Thread']['taglist'] = '';
	        		//$threadData['Thread']['film_id'] = $film['Film']['id'];

	        		if ($this->Thread->save($threadData))
	        		{
		        		$threadid = $this->Thread->getLastInsertId();
						$film['Film']['thread_id'] = $threadid;
						$saveFilm['Film'] = $film['Film'];
/*
echo'<pre>';
pr($film);
echo'</pre>';
//*/
				        //$this->Film->contain(array());
						//$this->Film->_clearCache(true);
//						$this->Film->create();
		        		$this->Film->save($saveFilm, false);
/*
$films = $this->Film->findAll(array('Film.thread_id' => $threadid), array('Film.id', 'Film.thread_id'));
echo'<pre>';
var_dump($films);
echo'</pre>';
//*/
	        		}
	        	}

        		//ДОБАВЛЕНИЕ СООБЩЕНИЯ (ПОСТА) В ВЕТКУ
        		$postAll = array();
        		if (isset($threadid))//ЗНАЧИТ ЭТО БУДЕТ ПЕРВЫЙ ПОСТ
        		{
		        	$postData['Vbpost']['threadid'] = $threadid;
		        	$postData['Vbpost']['pagetext'] = $threadData['Thread']['pagetext'];
	        		$postAll[] = $postData;
        		}
        		else
        		{
		        	$postData['Vbpost']['threadid'] = $threadData['Thread']['threadid'];
		        	$postData['Vbpost']['parentid'] = $threadData['Thread']['firstpostid'];
        		}

        		$postData['Vbpost']['username'] = $uuConverter->utfToUnicode($this->authUser['username']);
	        	$postData['Vbpost']['userid'] = $this->authUser['userid'];
	        	$postData['Vbpost']['pagetext'] = Utils::stripUbbTags(strip_tags($uuConverter->utfToUnicode(Utils::stripUbbTags($this->data['Vbpost']['pagetext']))));
	        	$postData['Vbpost']['dateline'] = time()+1;
        		$postAll[] = $postData;

        		$copyPost = $this->Vbpost->find(
        			array(
        				'Vbpost.threadid' => $postData['Vbpost']['threadid'],
        				'Vbpost.pagetext' => $postData['Vbpost']['pagetext'],
        			)
        		);

				if (!$copyPost)//ЕСЛИ ДУБЛЕЙ НЕ НАЙДЕНО
				{
	        		$this->Vbpost->saveAll($postAll);
	        		$postid = $this->Vbpost->getLastInsertId();

	        		//ОБНОВЛЕНИЕ ДАННЫХ ВЕТКИ
	        		$threadData['Thread']['lastpostid'] = $postid;
	        		$threadData['Thread']['lastposter'] = $uuConverter->utfToUnicode($this->authUser['username']);
	        		$threadData['Thread']['lastpost'] = time()+1;
	        		if (isset($threadid))
	        		{
	    	    		$firstpostid = $postid - 1;
		        		$threadData['Thread']['firstpostid'] = $firstpostid;
		        		$threadData['Thread']['threadid'] = $threadid;

		        		$forumInfo['Forum']['threadcount']++;

		        		$postData['Vbpost']['parentid'] = $firstpostid;
		        		$postData['Vbpost']['postid'] = $postid;
		        		$this->Vbpost->save($postData);
	        		}
	        		else
	        		{
		        		$threadData['Thread']['replycount']++;
	        		}
	        		$this->Thread->save($threadData);

	        		//ОБНОВЛЕНИЕ ДАННЫХ ФОРУМА
		        	$forumInfo['Forum']['replycount']++;
	        		$forumInfo['Forum']['lastpost'] = time()+1;
	        		$forumInfo['Forum']['lastposter'] = $uuConverter->utfToUnicode($this->authUser['username']);
	        		$forumInfo['Forum']['lastpostid'] = $postid;
	        		$forumInfo['Forum']['lastthread'] = $threadData['Thread']['title'];
	        		$forumInfo['Forum']['lastthreadid'] = $threadData['Thread']['threadid'];
	        		$this->Forum->save($forumInfo);

	        		$this->Session->setFlash(__('Comment added', true));

	        		Cache::delete('Catalog.lastComments', 'default');//СБРАСЫВАЕМ КЭШ БЛОКА ПОСЛЕДНИХ КОМЕНТОВ
	        		Cache::delete('Forum.lastFilmComments', 'media');//СБРАСЫВАЕМ КЭШ ВЫБОРКИ ПОСЛЕДНИХ КОМЕНТОВ
				}
	        }
        }

        if (!empty($forumInfo))
        {
			$threadInfo['enabled'] = $forumInfo['Forum']['options'] & 2 > 0;
			$threadInfo['lst'] = array();
			$threadInfo['stat'] = __('have not yet discussed', true);
			if (!empty($threadData['Thread']['threadid']))
			{
				$threadInfo['enabled'] = $threadInfo['enabled'] & $threadData['Thread']['open'];
				$threadInfo['id'] = $threadData['Thread']['threadid'];
				$lst = $this->Vbpost->findAll(array('Vbpost.threadid' => $threadData['Thread']['threadid'], 'Vbpost.parentid >' => 0, 'Vbpost.visible <= ' => 1), null, 'Vbpost.postid desc', Configure::read('threadLimit'));
				if (!empty($lst))
				{
					foreach ($lst as $key => $val)
					{
						$lst[$key]['Vbpost']['pagetext'] = $uuConverter->unicodeToUtf(Utils::transUbbTags($val['Vbpost']['pagetext']));
						//$lst[$key]['Vbpost']['pagetext'] = iconv('cp1252', 'utf-8', Utils::transUbbTags($val['Vbpost']['pagetext']));
					}
					$threadInfo['stat'] = __('Showing', true) . ' ' . count($lst) . ' ' . __('posts. Total', true) . ' ' . $threadData['Thread']['replycount'];
				}
				$threadInfo['lst'] = $lst;
			}
        }
        $this->set('threadInfo', $threadInfo);

        if ($this->authUser['userid'] == 0)
        {
            $this->set('basket', array());
            return;
        }
        if (!$basket = Cache::read('Catalog.basket_' . $this->authUser['userid'],'basket'))
        {
            $basket = $this->Basket->find('all', array('conditions' => array('Basket.user_id' => $this->authUser['userid']),
                                                       'fields' => array('FilmFile.id', 'FilmFile.film_variant_id')));

            $basket = Set::extract('/FilmFile/id', $basket); //Set::combine($basket, '/FilmFile.film_variant_id', '/FilmFile/id');
        }
        Cache::write('Catalog.basket_' . $this->authUser['userid'], $basket,'basket');
        $this->set('basket', $basket);
        $this->set('players', $this->getPlayerList());
    }

    /**
     * Пишем лог поисковых запросов
     *
     * @param string $keyword
     */
    function _logSearchRequest($keyword)
    {
        if (mb_strlen($keyword) < 3 || (is_numeric($keyword) && mb_strlen($keyword) < 2))
            return;
        $this->SearchLog->create();
        $this->SearchLog->save(array('SearchLog' => array('keyword' => $keyword,'ip'=>$_SERVER['REMOTE_ADDR'],'user_id'=>$this->authUser['userid'])));
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


/*
    function add_comment()
    {
        if (!empty($this->data))
        {
            if ($this->authUser['userid'])
            {
                $this->data['FilmComment']['user_id'] = $this->authUser['userid'];
                $this->data['FilmComment']['username'] = $this->authUser['username'];
                $this->data['FilmComment']['email'] = $this->authUser['email'];
            }

            $this->FilmComment->create();
            if ($this->FilmComment->save($this->data))
            {
                //$this->Session->setFlash(__('The FilmComment has been saved', true));
                $this->redirect($this->referer('/media'));
            }
            else
            {
                //$this->Session->setFlash(__('The FilmComment could not be saved.', true));
                $this->redirect($this->referer('/media'));
            }
        }
        $this->redirect('/media');
    }

*/
	/**
	 * отправка сообщения об ошибке определения географического местоположения пользователя
	 *
	 * @param string $action - тип действия
	 */
	function geoerr($action = '')
	{
		switch ($action)
		{
			case "send": //ДЕЙСТВИЕ ОТПРАВКИ ПИСЬМА АДМИНУ
				Configure::write('debug', 1);
				$geoPlace = '';
				if (!empty($this->geoInfo['Geoip']['region_id']))
				{
					$geoPlace .= implode(' ', array($this->geoInfo['city'], $this->geoInfo['region']));
				}
				else
				{
					$geoPlace .= __('unknown', true);
				}

				$geoPlace = __('user information', true) . ":\nIP - " . $_SERVER['REMOTE_ADDR'] . "\n" .
				__("position", true) . ": " . $geoPlace . "\n\n" . __('User message', true) . ":\n\n";

				$to = 'support@videoxq.com';
				$this->_sendEmail($this->authUser['email'], $to, 'ошибочно определено географическое местоположение пользователя', $geoPlace . strip_tags($this->data['msg']));

			break;
			default:
				//ВЫВОД ФОРМЫ

		}
		$this->set('action', $action);
	}

    function feedback()
    {
        if (!empty($this->data))
        {
            $this->Feedback->create();
            if ($this->Feedback->save($this->data))
            {
                $this->Session->setFlash(__('The Feedback has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Feedback could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_index()
    {
        $this->Film->recursive = -1;
        $this->set('films', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Film.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('film', $this->Film->read(null, $id));
    }

    function admin_sitemap()
    {
    	$this->layout = 'sitemap';
    	$this->Film->recursive = 0;
    	$films = $this->Film->findAll(null, array('id', 'modified'));
    	if ($films)
    		$this->set('films', $films);
    }

    function sitemap()
    {
    	//$this->layout = 'htmsitemap';
		$films = Cache::read('Catalog.map_films', 'searchres');
		$gnr = Cache::read('Catalog.map_genres', 'searchres');
		if (!$films)
		{
	    	$this->Genre->recursive = 0;
	    	$genres = $this->Genre->findAll(null, array('Genre.id', 'Genre.title'));
	    	$nr = array();
	    	foreach ($genres as $genre)
	    	{
	    		$gnr[$genre['Genre']['id']] = $genre['Genre']['title'];
	    	}
	    	$films = $this->Film->getFilmsWithGenres();
		    Cache::write('Catalog.map_films', $films, 'searchres');
		    Cache::write('Catalog.map_genres', $gnr, 'searchres');
    	}
   		$this->set('films', $films);
   		$this->set('genres', $gnr);
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Film->create();
            if ($this->Film->save($this->data))
            {
                $this->Session->setFlash(__('The Film has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Film could not be saved. Please, try again.', true));
            }
        }
        $countries = $this->Film->Country->find('list');
        $genres = $this->Film->Genre->find('list');
        $filmTypes = $this->Film->FilmType->find('list');
        $this->set(compact('countries', 'genres', 'filmTypes'));
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Film', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Film->save($this->data))
            {
            	$filmInfo = $this->Film->find(array('Film.id' => $id));
            	if (!empty($filmInfo['Film']['thread_id']))
            	{
            		$threadTxts = $this->Film->getThreadText($filmInfo);

            		$threadInfo = array('Thread' =>
            			array(
            				'threadid' => $filmInfo['Film']['thread_id'],
							'title' => $threadTxts['title'],
						)
					);
					$this->Thread->save($threadInfo);
            		$post = $this->Thread->Vbpost->find(array('Vbpost.parentid' => 0, 'Vbpost.threadid' => $filmInfo['Film']['thread_id']), array('postid'), 'Vbpost.postid ASC');
            		if ($post)
            		{
/*
echo '<pre>';
print_r($post);
echo '</pre>';
exit;
//*/
	            		$postInfo = array('Vbpost' =>
	            			array(
	            				'postid' => $post['Vbpost']['postid'],
								'pagetext' => $threadTxts['text'],
								'title' => $threadTxts['title'],
							)
						);
						$result = $this->Thread->Vbpost->save($postInfo);//ОБНОВИЛИ ДАННЫЕ В ВЕТКЕ
					}
            	}
            	Cache::delete('Catalog.film_view_' . $id, 'media');

                $this->Session->setFlash(__('The Film has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Film could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Film->read(null, $id);
        }
        $countries = $this->Film->Country->find('list');
        $genres = $this->Film->Genre->find('list');
        $filmTypes = $this->Film->FilmType->find('list');
        $this->set(compact('countries', 'genres', 'filmTypes'));
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Film', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Film->del($id))
        {
           	Cache::delete('Catalog.film_view_' . $id, 'media');
            $this->Session->setFlash(__('Film deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }


    function admin_up($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Film', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Film->contain();
        $film = $this->Film->read(null, $id);
        $film['Film']['modified'] = date('Y-m-d H:i:s');
        if ($this->Film->save($film))
        {
            Cache::delete('Catalog.film_view_' . $id, 'media');

            $this->Session->setFlash(__('Film updated', true));
            $this->redirect(array('action' => 'index'));
        }
    }
    function captcha()
    {
        $this->layout = 'ajax';
        $this->Captcha->render();
        $this->view = null;
    }

    function getPlayerList()
    {
		$players = array(
	    		"alloy"	=> array(
	    			'name'	=> 'alloy',
	    			'title'	=> 'Light Alloy',
	    			'ico'	=> 'alloy',
	    			'mime'	=> 'application/alloy',
	    			'ext'	=> 'lap',
	    		),
	    		"wmp"	=> array(
	    			'name'	=> 'wmp',
	    			'title'	=> 'Windows Media Player',
	    			'ico'	=> 'wmp',
	    			'mime'	=> 'application/x-mplayer2',
	    			'ext'	=> 'asx',
	    		),
	    		"mpc"	=> array(
	    			'name'	=> 'mpc',
	    			'title'	=> 'Media Player Classic',
	    			'ico'	=> 'mpc',
	    			'mime'	=> 'application/mpc',
	    			'ext'	=>	'mpcpl'
	    		),
	    		"bs"	=> array(
	    			'name'	=> 'bs',
	    			'title'	=> 'BSPlayer',
	    			'ico'	=> 'bs',
	    			'mime'	=> 'application/bsplayer',
	    			'ext'	=> 'bsl',
	    		),
	    		"crystal"	=> array(
	    			'name'	=> 'crystal',
	    			'title'	=> 'Crystal Player',
	    			'ico'	=> 'crystal',
	    			'mime'	=> 'application/crystal',
	    			'ext'	=> 'mls',
	    		),
	    		"winamp"	=> array(
	    			'name'	=> 'winamp',
	    			'title'	=> 'Winamp/Mplayer',
	    			'ico'	=> 'winamp',
	    			'mime'	=> 'application/winamp',
	    			'ext'	=> 'pls',
	    		),
	    	);
    	return $players;
    }

    function playlist($filmFileId, $player)
    {
    	$this->layout = 'playlist';
    	$this->set('player', $player);
    	$players = $this->getPlayerList();
    	$this->set('players', $players);
    	$filmFile = $this->Film->FilmVariant->FilmFile->read(null, $filmFileId);
    	$film = $this->Film->read(null, $filmFile['FilmVariant']['film_id']);
    	$this->set('file', $filmFile);
    	$this->set('film', $film);
    }

    function download_list($id = null)
    {
        App::import('Vendor', 'Utils');
//    	$this->layout = 'ajax';
        set_time_limit(50000);
        ini_set('memory_limit','256M');
        $condition=array();
        if($id!=NULL)$condition=array("id>".$id);
        $this->Film->recursive = 0;

        $page = 1;
        $perPage = 100;
        $count=$this->Film->find('all',$condition);
        //echo $count;
//        header("Content-Description: File Transfer");
//        header("Content-Disposition: attachment; filename=" . date('Y-m-d_H_i_s') . '.csv');
//        header("Content-Type: text/plain");
//        header("Content-Transfer-Encoding: binary");
	    echo '"id";"name";"name_eng";"year";"Country"';
	    echo "\n";
        //$counter=ceil($count/$perPage);
        //echo $counter;
        $counter=3;
        for($page=1;$page<$counter;$page++)
        {
        	$this->Film->contain('Country');
        	$data=$this->Film->FindAll($condition,array('id','title','title_en','year'),'',$perPage,$page);
	        foreach ($data as $dat)
	        {
	        	$datt=Utils::iconvRecursive($dat,'utf-8','windows-1251');
	        	$FILM=$dat['Film'];
	        	$Country=$dat['Country'];
	        	$d='"';
	        	$d.=implode('";"',$FILM);
	        	if(count($Country)>0){
		        	$Country=set::extract('n/title',$Country);
		        	$d.='";"';
		        	$d.=implode(',',$Country);
	        	}
	        	$d.="\"\n";
	        	//echo $d;
	        	$d='';
	        	//pr($Country);
	        }
        }
    }

    /**
     * импорт старых комментариев в базу форума
     * добавляет в ветку обсуждения фильма, пропущенные при импорте комментарии из базы видеокаталога
     *
     * @param integer $filmId
     */
    public function filmcommentimport($filmId = 0)
    {
		$this->set('total', 0);
		$this->set('skipped', 0);
		$this->set('added', 0);

		if (empty($filmId))
    		return;

//ИЩЕМ ФИЛЬМ
    	$film = $this->Film->read(null, $filmId);
    	if (empty($film))
    		return;

//ВЫБИРАЕМ СТАРЫЕ КОМЕНТЫ
        $cst = $this->Film->FilmComment->findAll(array('FilmComment.film_id' => $filmId), null, 'FilmComment.id ASC');
        if (empty($cst))
        	return;

//ИЩЕМ ТЕМУ
		$threadData['Thread'] = $film['Thread'];
        if (empty($threadData['Thread']))
        	return;

        App::import('Vendor', 'uuconverter');
		$uuConverter = new uuConverter();
		$threadid = $threadData['Thread']['threadid'];
        $fst = $this->Vbpost->findAll(array('Vbpost.threadid' => $threadid), null, 'Vbpost.threadid ASC');

        $total = count($cst);

		function makeTime($mySqlDate)
		{
			if (empty($mySqlDate))
				return time();
			$parts = explode(' ', $mySqlDate);
			$dmy = explode('-', $parts[0]);
			$his = explode(':', $parts[1]);
			return mktime($his[0], $his[1], $his[2], $dmy[1], $dmy[2], $dmy[0]);
		}

		$skipped = 0;
		$added = 0;

		foreach ($cst as $c)
		{
			//ПОДГОТОВКА ДАННЫХ ПОСТА
			if (empty ($c['FilmComment']['created']))
				continue;

        	$postData = array('Vbpost' => array());
        	$postData['Vbpost']['threadid'] = 0;
        	$postData['Vbpost']['parentid'] = 0;
        	$postData['Vbpost']['username'] = $uuConverter->utfToUnicode($c['User']['username']);
        	$postData['Vbpost']['userid'] = $c['User']['userid'];
        	$postData['Vbpost']['title'] = '';
        	$postData['Vbpost']['dateline'] = makeTime($c['FilmComment']['created']);
        	$postData['Vbpost']['pagetext'] = $uuConverter->utfToUnicode(strip_tags($c['FilmComment']['text']));
        	$postData['Vbpost']['allowsmilie'] = 0;
        	$postData['Vbpost']['showsignature'] = 0;
        	$postData['Vbpost']['ipaddress'] = $c['FilmComment']['ip'];
        	$postData['Vbpost']['iconid'] = 0;
        	$postData['Vbpost']['visible'] = 1;
        	$postData['Vbpost']['attach'] = 0;
        	$postData['Vbpost']['infraction'] = 0;
        	$postData['Vbpost']['reportthreadid'] = 0;
        	$postData['Vbpost']['threadid'] = $threadData['Thread']['threadid'];
        	$postData['Vbpost']['parentid'] = $threadData['Thread']['firstpostid'];

        	//ПОИСК ЭТОГО КОМЕНТА РАНЕЕ ДОБАВЛЕННОГО В ФОРУМ
        	$exists = false;
        	foreach ($fst as $f)
        	{
//echo'<p>' . $f["Vbpost"]['dateline'] . ' == ' . $postData['Vbpost']['dateline'] . ' == ' . $c['FilmComment']['created'] . '</p>';
        		if ($f["Vbpost"]['dateline'] == $postData['Vbpost']['dateline'])
        		{
        			$exists = true;
        			$skipped++;
        			break;
        		}
        	}

        	if ($exists) continue;

			//ДОБАВЛЕНИЕ ПОСТА В ВЕТКУ
    		$this->Vbpost->create($postData);
    		$this->Vbpost->save($postData);
    		$postid = $this->Vbpost->getLastInsertId();
			$added++;
/*
echo'<pre>';
print_r($postData);
echo'</pre>';
return;
*/
    		$threadData['Thread']['threadid'] = $threadid;
    		$threadData['Thread']['lastpostid'] = $postid;
    		$threadData['Thread']['lastposter'] = $uuConverter->utfToUnicode($c['User']['username']);
    		$threadData['Thread']['lastpost'] = makeTime($c['FilmComment']['created'])+1;
        	$threadData['Thread']['replycount']++;
    		$this->Thread->save($threadData);
		}

		$this->set('total', $total);
		$this->set('skipped', $skipped);
		$this->set('added', $added);
    }

    public function commentimport($limit = -1)
    {
    	return;//ТК ОДНОРАЗОВАЯ ОПЕРАЦИЯ
	//Configure::write('debug', 2);
    	set_time_limit(100000000);
        $cst = $this->Film->FilmComment->findAll(null, null, 'FilmComment.id ASC');
    	$films = array();//СПИСОК ФИЛЬМОВ, К КОТОРЫМ СОЗДАНЫ ВЕТКИ ОБСУЖДЕНИЯ
    	$threads = array();//СПИСОК ВЕТОК
    	$forums = array();
		if (!empty($cst))
		{
			function makeTime($mySqlDate)
			{
				if (empty($mySqlDate))
					return time();
				$parts = explode(' ', $mySqlDate);
				$dmy = explode('-', $parts[0]);
				$his = explode(':', $parts[1]);
				return mktime($his[0], $his[1], $his[2], $dmy[1], $dmy[2], $dmy[0]);
			}

			$forumInfo = $this->Forum->read(null, Configure::read('forumId'));
        	$forumInfo['Forum']['replycount'] = 0;
	        App::import('Vendor', 'uuconverter');
			$uuConverter = new uuConverter();

			foreach ($cst as $c)
			{
				if (empty($c['User']))
				{
					$c['User']['username'] = $c['FilmComment']['username'];// ЕСЛИ ДОБАВЛЕНО ГОСТЕМ
					$c['User']['userid'] = 0;
				}
				if (isset($films[$c['FilmComment']['film_id']]))
				{
					//ДОБАВЛЕНИЕ ПОСТА В ВЕТКУ
					$film = $films[$c['FilmComment']['film_id']];
					$threadData = $threads[$film['Film']['id']];
					$threadid = $threadData['Thread']['threadid'];

		        	$postData = array('Vbpost' => array());
		        	$postData['Vbpost']['threadid'] = 0;
		        	$postData['Vbpost']['parentid'] = 0;
		        	$postData['Vbpost']['username'] = $uuConverter->utfToUnicode($c['User']['username']);
		        	$postData['Vbpost']['userid'] = $c['User']['userid'];
		        	$postData['Vbpost']['title'] = '';
		        	$postData['Vbpost']['dateline'] = makeTime($c['FilmComment']['created']);
		        	$postData['Vbpost']['pagetext'] = $uuConverter->utfToUnicode(strip_tags($c['FilmComment']['text']));
		        	$postData['Vbpost']['allowsmilie'] = 0;
		        	$postData['Vbpost']['showsignature'] = 0;
		        	$postData['Vbpost']['ipaddress'] = $c['FilmComment']['ip'];
		        	$postData['Vbpost']['iconid'] = 0;
		        	$postData['Vbpost']['visible'] = 1;
		        	$postData['Vbpost']['attach'] = 0;
		        	$postData['Vbpost']['infraction'] = 0;
		        	$postData['Vbpost']['reportthreadid'] = 0;
		        	$postData['Vbpost']['threadid'] = $threadData['Thread']['threadid'];
		        	$postData['Vbpost']['parentid'] = $threadData['Thread']['firstpostid'];

	        		$this->Vbpost->create($postData);
	        		$this->Vbpost->save($postData);
	        		$postid = $this->Vbpost->getLastInsertId();

	        		$threadData['Thread']['lastpostid'] = $postid;
	        		$threadData['Thread']['lastposter'] = $uuConverter->utfToUnicode($c['User']['username']);
	        		$threadData['Thread']['lastpost'] = makeTime($c['FilmComment']['created'])+1;
		        	$threadData['Thread']['replycount']++;
	        		$this->Thread->save($threadData);
	        		$threads[$film['Film']['id']] = $threadData;

	        		//ОБНОВЛЕНИЕ ДАННЫХ ФОРУМА
		        	$forumInfo['Forum']['replycount']++;
	        		$forumInfo['Forum']['lastpost'] = time()+1;
	        		$forumInfo['Forum']['lastposter'] = $uuConverter->utfToUnicode($c['User']['username']);
	        		$forumInfo['Forum']['lastpostid'] = $postid;
	        		$forumInfo['Forum']['lastthread'] = $threadData['Thread']['title'];
	        		$forumInfo['Forum']['lastthreadid'] = $threadData['Thread']['threadid'];
//break;
				}
				else //СОЗДАНИЕ ВЕТКИ
				{
					if (!empty($c['Film']['thread_id']))
						continue;

if (count($threads) > 150)
break;
					unset($threadid);
					unset($threadData);
					$filmData['Film'] = $c['Film'];
					$film = $filmData;
					$filmInfo = $this->Film->find(array('Film.id' => $c['Film']['id']));
/*
echo '<pre>';
print_r($c['FilmComment']);
echo '</pre>';
exit;
//*/

					$threadInfo = array();

		        	//БЛАНК СТРУКТУРЫ ПОСТА
		        	$postData = array('Vbpost' => array());
		        	$postData['Vbpost']['threadid'] = 0;
		        	$postData['Vbpost']['parentid'] = 0;
		        	$postData['Vbpost']['username'] = $uuConverter->utfToUnicode($c['User']['username']);
		        	$postData['Vbpost']['userid'] = $c['User']['userid'];
		        	$postData['Vbpost']['title'] = '';
		        	$postData['Vbpost']['dateline'] = makeTime($c['FilmComment']['created']);
		        	$postData['Vbpost']['pagetext'] = $uuConverter->utfToUnicode(strip_tags($c['FilmComment']['text']));
		        	$postData['Vbpost']['allowsmilie'] = 0;
		        	$postData['Vbpost']['showsignature'] = 0;
		        	$postData['Vbpost']['ipaddress'] = $c['FilmComment']['ip'];
		        	$postData['Vbpost']['iconid'] = 0;
		        	$postData['Vbpost']['visible'] = 1;
		        	$postData['Vbpost']['attach'] = 0;
		        	$postData['Vbpost']['infraction'] = 0;
		        	$postData['Vbpost']['reportthreadid'] = 0;


	        		$threadData = array('Thread' => array());

					$threadData['Thread']['pagetext'] = $this->Film->getThreadText($filmInfo);

	        		//СОХРАНЕНИЕ НОВОЙ ВЕТКИ
	        		$threadData['Thread']['prefixid'] = '';
	        		$threadData['Thread']['firstpostid'] = 0;//НУЖНО ОБНОВИТЬ ПОСЛЕ ДОБАВЛНИЯ ПОСТА
	        		$threadData['Thread']['lastpostid'] = 0;//НУЖНО ОБНОВИТЬ ПОСЛЕ ДОБАВЛНИЯ ПОСТА
	        		$threadData['Thread']['lastpost'] = time();
	        		$threadData['Thread']['forumid'] = $forumInfo['Forum']['forumid'];
	        		$threadData['Thread']['pollid'] = 0;
	        		$threadData['Thread']['open'] = 1;
	        		$threadData['Thread']['replycount'] = 1;
	        		$threadData['Thread']['hiddencount'] = 0;
	        		$threadData['Thread']['deletedcount'] = 0;
	        		$threadData['Thread']['postusername'] = $uuConverter->utfToUnicode($c['User']['username']);
	        		$threadData['Thread']['postuserid'] = $c['User']['userid'];
	        		$threadData['Thread']['lastposter'] = $uuConverter->utfToUnicode($c['User']['username']);
	        		$threadData['Thread']['dateline'] = makeTime($c['FilmComment']['created']);
	        		$threadData['Thread']['views'] = 0;
	        		$threadData['Thread']['iconid'] = 0;
	        		$threadData['Thread']['notes'] = '';
	        		$threadData['Thread']['visible'] = 1;
	        		$threadData['Thread']['sticky'] = 0;
	        		$threadData['Thread']['votenum'] = 0;
	        		$threadData['Thread']['votetotal'] = 0;
	        		$threadData['Thread']['attach'] = 0;
	        		$threadData['Thread']['similar'] = '';
	        		$threadData['Thread']['taglist'] = '';

		        	$postData['Vbpost']['pagetext'] = $threadData['Thread']['pagetext'];
	        		unset($threadData['Thread']['threadid']);
	        		unset($threadData['Thread']['pagetext']);

	        		$this->Thread->create($threadData);
	        		$this->Thread->save($threadData);

	        		$threadid = $this->Thread->getLastInsertId();
	        		//$max = $this->Thread->find('first', array('threadid'), 'Thread.threadid desc');
	        		//$threadid = $max['Thread']['threadid'];
	        		$filmData['Film']['thread_id'] = $threadid;
/*
echo '<pre>';
print_r($filmData);
echo '</pre>';
*/
	        		$this->Film->save($filmData, false, array('id', 'thread_id'));
	        		$films[$film['Film']['id']] = $filmData;

	        		//ДОБАВЛЕНИЕ СООБЩЕНИЯ (ПОСТА) В ВЕТКУ
	        		$postAll = array();
		        	$postData['Vbpost']['threadid'] = $threadid;
	        		$postAll[] = $postData;

	        		$postData['Vbpost']['pagetext'] = $uuConverter->utfToUnicode($c['FilmComment']['text']);
		        	$postData['Vbpost']['dateline'] = makeTime($c['FilmComment']['created'])+1;
	        		$postAll[] = $postData;

	        		$this->Vbpost->saveAll($postAll);
	        		$postid = $this->Vbpost->getLastInsertId();

	        		//ОБНОВЛЕНИЕ ДАННЫХ ВЕТКИ
	        		$threadData['Thread']['lastpostid'] = $postid;
	        		$threadData['Thread']['lastposter'] = $uuConverter->utfToUnicode($c['User']['username']);
	        		$threadData['Thread']['lastpost'] = makeTime($c['FilmComment']['created'])+1;

        			$firstpostid = $postid - 1;
	        		$threadData['Thread']['firstpostid'] = $firstpostid;
	        		$threadData['Thread']['threadid'] = $threadid;

	        		$forumInfo['Forum']['threadcount']++;

	        		$postData['Vbpost']['parentid'] = $firstpostid;
	        		$postData['Vbpost']['postid'] = $postid;
	        		$this->Vbpost->save($postData);

		        	$this->Thread->save($threadData);

	        		$threads[$film['Film']['id']] = $threadData;
	        		//ОБНОВЛЕНИЕ ДАННЫХ ФОРУМА
		        	$forumInfo['Forum']['replycount']++;
	        		$forumInfo['Forum']['lastpost'] = time()+1;
	        		$forumInfo['Forum']['lastposter'] = $uuConverter->utfToUnicode($c['User']['username']);
	        		$forumInfo['Forum']['lastpostid'] = $postid;
	        		$forumInfo['Forum']['lastthread'] = $threadData['Thread']['title'];
	        		$forumInfo['Forum']['lastthreadid'] = $threadData['Thread']['threadid'];

if (--$limit == 0) {
	break;
}

				}
			}
       		$this->Forum->save($forumInfo);
		}
		$this->redirect('/media');
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


            //если мы вызываем autocomplete из frontend'а, то обрежем
            //нежелдательную инфу, сформировав соотвествующий $condition, в
            //зависимости от модели.
            //юзерам ведь не надо видеть неактивные фильмы и скрытых
            //правообладателей :))))
            if (empty($this->params[Configure::read('Routing.admin')])){
                switch ($model){
                    case 'Film':
                        if (!empty($this->isWS) && !$this->isWS){
                            $frontend_condition = $model.'.is_license = 1 AND';
                        }
                        $frontend_condition = $model.'.active = 1';
                        break;
                    case 'Copyrightholder':
                        $frontend_condition = $model.'.hidden = 0';
                        break;
                }
            }

            //формируем запрос
            //основной фильтр запроса
            $search_condition = $model.'.'.$search_field.' LIKE \'%'.$search_for.'%\'';

            if(!empty($frontend_condition) && $frontend_condition){
            //дополнительный фильтр запроса, если нужен для данной модели
                $conditions = array('AND'=>array($search_condition, $frontend_condition));
            }
            else{
                $conditions = array($search_condition);
            }


            $results = $this->{$model}->find('all',array(
                                        'contain' => array(),
                                        'fields' => $fields_,
                                        'limit' => $limit,
                                        'conditions' => $conditions));

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


}