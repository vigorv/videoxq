<?php
App::import('Model', 'MediaModel');
class Film extends MediaModel {

    var $name = 'Film';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'FilmComment' => array('className' => 'FilmComment',
                                'foreignKey' => 'film_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
            'FilmPicture' => array('className' => 'FilmPicture',
                                'foreignKey' => 'film_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
            'FilmVariant' => array('className' => 'FilmVariant',
                                'foreignKey' => 'film_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
            'FilmClick' => array('className' => 'FilmClick',
                                'foreignKey' => 'film_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
            'SourceLink' => array('className' => 'SourceLink',
                                'foreignKey' => 'film_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            )
    );

    var $hasAndBelongsToMany = array(
            'Country' => array('className' => 'Country',
                        'joinTable' => 'countries_films',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'country_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'Emotion' => array('className' => 'Emotion',
                        'joinTable' => 'emotions_films',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'emotion_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'Genre' => array('className' => 'Genre',
                        'joinTable' => 'films_genres',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'genre_id',
                        'unique' => true,
                        'conditions' => array('Genre.is_delete' => 0),
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'Person' => array('className' => 'Person',
                        'joinTable' => 'films_persons',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'person_id',
                        'unique' => false,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => '',
                        'with' => 'FilmsPerson'
            ),
            'Publisher' => array('className' => 'Publisher',
                        'joinTable' => 'films_publishers',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'publisher_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'Theme' => array('className' => 'Theme',
                        'joinTable' => 'films_themes',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'theme_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
/*
            'Geoip' => array('className' => 'Geoip',
                        'joinTable' => 'films_geoips',
                        'foreignKey' => 'film_id',
                        'associationForeignKey' => 'geoip_id',
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            )
*/
    );

    var $belongsTo = array(
            'FilmType' => array('className' => 'FilmType',
                                'foreignKey' => 'film_type_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
    //СВЯЗЬ С ВЕТКОЙ ОБСУЖДЕН�?Й Ф�?ЛЬМА В ФОРУМЕ VB
			'Thread' =>
                        array('className'    => 'Thread',
                              'foreignKey'   => 'thread_id'
                        )
    );
    var $hasOne = array(
            'MediaRating' => array('className' => 'MediaRating',
                                'foreignKey' => 'object_id',
                                'conditions' => 'MediaRating.type="film"',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $actsAs = array('Containable', 'Sphinx');


    var $picturesFrom = 'cp -R -v "c:\\server\\vhosts\\media\\www\\';
    var $picturesTo = ' "c:\\asdasd\\';


    /**
     * Получает список акторов и т.п. для фильма
     *
     * @param int $id Film.id
     * @param string $order
     * @return unknown
     */
    function getFilmPersons($id, $order = 'FilmsPerson.profession_id ASC')
    {
		if (!$persons = Cache::read('Catalog.film_view_' . $id.'_persons','media'))
        {
	        $db =& ConnectionManager::getDataSource($this->useDbConfig);
	        $sql = '
	        SELECT PersonPicture.*,
	              `FilmsPerson`.`person_id`, `FilmsPerson`.`role`, `FilmsPerson`.`profession_id`,
	              `Person`.`id`, `Person`.`name`, `Person`.`name_en`, `Person`.`url`,
	              `Profession`.`id`, `Profession`.`title`
	        FROM `films_persons` AS `FilmsPerson`
	        LEFT JOIN `persons` AS `Person` ON (`FilmsPerson`.`person_id` = `Person`.`id`)
	        LEFT JOIN `professions` AS `Profession` ON (`FilmsPerson`.`profession_id` = `Profession`.`id`)
	        LEFT JOIN person_pictures AS PersonPicture ON ( `Person`.`id` = PersonPicture.person_id )
	        WHERE `FilmsPerson`.`film_id` = ' . $db->value($id, 'integer')
	        . ' ORDER BY ' . $order;
	        $persons = $this->query($sql);
		    Cache::write('Catalog.film_view_' . $id.'_persons', $persons,'media');
        }
        return $persons;
    }

    function updateHits($id, $increment = 1)
    {
        $db =& ConnectionManager::getDataSource($this->useDbConfig);
        $sql = 'UPDATE films SET hits=hits+' .$increment
               . ' WHERE id = ' . $db->value($id);
        $this->query($sql);
    }


    /**
     * Список фильмов с последними комментами
     *
     * @return unknown
     */
    function getActivity()
    {
        $sql = 'SELECT `Film`.`title`, `Film`.`id`, MAX(`Comment`.`created`) as created
                FROM `film_comments` AS `Comment`
                JOIN `films` AS `Film` ON (`Comment`.`film_id` = `Film`.`id`)
                GROUP BY `Film`.`title` ORDER BY created DESC LIMIT 10';

        //ЗАПРОС К БАЗЕ ФОРУМА
        $sql = 'SELECT `Comment`.`dateline` AS created, `Comment`.`threadid`
                FROM `post` AS `Comment` INNER JOIN `thread` ON (`thread`.`threadid` = `Comment`.threadid AND `thread`.`forumid` = ' . Configure::read('forumId') . ') GROUP BY `Comment`.`threadid` ORDER BY created DESC LIMIT 10
        ';

        $pst = $this->Thread->Vbpost->query($sql);
//echo '<pre>';
//print_r($pst);
//echo '</pre>';
        $ids = array();
        $result = array();
        if (!empty($pst))
        {
        	//ТАБЛ�?ЦЫ ФОРУМА �? В�?ДЕОКАТАЛОГА В РАЗНЫХ БАЗАХ, ПОСЕМУ �?МЕЕМ СЛЕДУЮЩ�?Й БАЯН
        	foreach ($pst as $p)
        	{
        		$ids[] = $p['Comment']['threadid'];
        	}
        	$result = $this->findAll(array('Film.thread_id' => $ids));

        	if (!empty($result))
        	{
        		foreach ($result as $key => $value)
        		{
        			foreach ($pst as $p)
        			{
        				if ($value['Film']['thread_id'] == $p['Comment']['threadid'])
        				{
//echo '<p>' . date('Y-m-d H:i:s', $p['Comment']['created']);

        					$result[$key][0]['created'] = date('Y-m-d H:i:s', $p['Comment']['created']);
        					$result[$key][0]['createdstamp'] = $p['Comment']['created'];
        				}
        			}
        		}
        		function byCreated($a, $b)
        		{
        			if ($a[0]['createdstamp'] < $b[0]['createdstamp'])
        				return 1; else return 0;
        		}
        		usort($result, "byCreated");
        	}
        }

        //return $this->query($sql);
        return $result;
    }

    /**
     * получить текст (данные фильма) первого сообщения в ветке обсуждения фильма
     *
     * @param array $filmInfo - данные фильма со всеми ассоциациями
     * @return array('title','text') заголовок(title) и текст (text) для первого сообщения в ветке обсуждения
     */
    public function getThreadText($filmInfo)
    {
	    App::import('Vendor', 'uuconverter');
		$uuConverter = new uuConverter();

    	extract($filmInfo);
    	$countries = array();
    	if (count($Country))
    		foreach($Country as $c) $countries[] = $c["title"];
    	$countries = implode(', ', $countries);

	    $persons = $this->getFilmPersons($filmInfo['Film']['id']);

        $out = array();

        foreach ($persons as $person)
        {
            if (!isset($out[$person['Person']['id']]))
            {
                unset($person['FilmsPerson']);
                $person['Profession'] = array($person['Profession']['id'] => $person['Profession']['title']);
                $out[$person['Person']['id']] = $person;
            }
            else
            {
                $out[$person['Person']['id']]['Profession'][$person['Profession']['id']] = $person['Profession']['title'];
            }
        }
        $directors	= array();
        $dirNames	= array();
        $story		= array();
        $actors		= array();
        foreach ($out as $personRow)
        {
            extract($personRow);

            if (!empty($Person['name']))
                $name = $Person['name'];
            else
                $name = $Person['name_en'];

            $link = '[URL="' . Configure::read('App.siteUrl') . 'people/view/' . $Person['id'] . '"]' . $name . '[/URL]';
            if (isset($Profession[1]))
            {
                $directors[] = $link;
                $dirNames[] = $name;
            }
            if (isset($Profession[2])
                || isset($Profession[22]))
                $story[] = $link;
            if (isset($Profession[3])
                || isset($Profession[4]))
            {
            	if (count($actors) < 10)
            		$actors[] = $link;
            }
        }

        $year = '';
        if (!empty($filmInfo['Film']['year']))
        	$year = ', ' . $filmInfo['Film']['year'];
		$threadTitle = $uuConverter->utfToUnicode($filmInfo['Film']['title'] . $year . strip_tags(((!empty($directors) ? ' (' . implode(', ', $dirNames) . ')' : ''))));

		$posters = Set::extract('/FilmPicture[type=poster]/.', $filmInfo);
		$filmUrl = Configure::read('App.siteUrl') . 'media/view/' . $filmInfo['Film']['id'];
		$imdbUrl = 'http://imdb.com/title/' . $filmInfo['Film']['imdb_id'];
		if (!empty($posters))
		{
			//$imgUrl = '<img align="right" src="' . Configure::read('Catalog.imgPath') . $posters[array_rand($posters)]['file_name'] . '" />';
			$imgUrl = '[RIGHT][URL="' . $filmUrl . '"][IMG]' . Configure::read('Catalog.imgPath') . $posters[array_rand($posters)]['file_name'] . '[/IMG][/URL][/RIGHT]';
		}
		else
			$imgUrl = '';
		$threadText = $imgUrl;
		$threadText .= '[URL="' . $filmUrl . '"][SIZE="4"]«' . $uuConverter->utfToUnicode($filmInfo['Film']['title']) . '»[/SIZE][/URL]' . "\r\n";
		$threadText .= '[URL="' . $imdbUrl . '"][SIZE="3"]' . $filmInfo['Film']['title_en'] . '[/SIZE][/URL]' . "\r\n";
		$threadText .= $uuConverter->utfToUnicode(implode(', ', array($countries, $filmInfo['Film']['year'], $FilmType["title"]))) . "\r\n";
	    if (!empty($directors))
    		$threadText .= '[B][SIZE="3"]Режиссёр:[/SIZE][/B]' . " " . implode(', ', $directors) . "\r\n";
	    if (!empty($story))
    		$threadText .= '[B][SIZE="3"]Сценарий:[/SIZE][/B]' . " " . implode(', ', $story) . "\r\n";
	    if (!empty($actors))
    		$threadText .= '[B][SIZE="3"]В ролях:[/SIZE][/B]' . " " . implode(', ', $actors) . "\r\n";
	    if (!empty($filmInfo['Genre']))
	    {
	    	$genres = array();
	    	foreach ($filmInfo['Genre'] as $genre)
	    	{
	    		$genres[] = $genre['title'];
	    	}
	    	$genres = implode(', ', $genres);
    		$threadText .= '[B][SIZE="3"]Жанр:[/SIZE][/B]' . " " . $genres . "\r\n";
	    }

		$threadText .= strip_tags(str_replace('&nbsp;', ' ', $filmInfo['Film']['description']));
		$threadText .= "\r\n" . '[URL="' . $filmUrl . '"]Скачать[/URL]';
		$threadText = $uuConverter->utfToUnicode($threadText);

    	return array('title' => $threadTitle, 'text' => $threadText);
    }

    function migrate($date)
    {
        App::import('Vendor', 'Utils');

        //Utils::getMemoryReport();
        $this->useDbConfig = 'migration';
        $this->setDataSource($this->useDbConfig);

        $limit = ' LIMIT %s, %s';
        $page = 1;
        $perPage = 100;
        $sql = 'SELECT * FROM films ' . $date;
        $query = $sql . sprintf($limit, $page - 1, $perPage);

        $picturesFrom = $this->picturesFrom;
        $picturesTo = $this->picturesTo;

        $picturesCmd = '';
        $picturesCmd .= "@echo off\r\n";
        $picturesCmd .= 'md '.$picturesTo."posters\"\r\n";
        $picturesCmd .= 'md '.$picturesTo."bigposters\"\r\n";
        $picturesCmd .= 'md '.$picturesTo."smallposters\"\r\n";
        $picturesCmd .= 'md '.$picturesTo."frames\"\r\n";

        unlink(APP . 'migration_film_pics.cmd');

        //получаем фильмы пачками по 100 штук, чтобы не было проблем
        //при большом кол-ве фильмов
        while ($objects = $this->query($query))
        {
            //Utils::getMemoryReport();
            /*
            $this->useDbConfig = $this->defaultConfig;
	        $this->setDataSource($this->useDbConfig);
            $ds = $this->getDataSource();
            $ds->commit();
            */

            foreach ($objects as $object)
            {
                //Utils::getMemoryReport();

                $object = Utils::iconvRecursive($object);

//                $this->useDbConfig = $this->defaultConfig;
                extract($object['films']);
                $ImdbRating = (float)$ImdbRating / 10;
                $film = array('title' => $Name, 'id' => $ID, 'title_en' => $OriginalName,
                              'description' => $Description, 'year' => $Year, 'active' => (!$Hide),
                              'imdb_id' => $imdbID, 'imdb_rating' => $ImdbRating, 'created' => $timestamp, 'modified' => $timestamp);

                $this->useDbConfig = 'migration';
		        $this->setDataSource($this->useDbConfig);

                //Utils::getMemoryReport();

                $country = $this->query('select * from filmcountries where FilmID = ' . $ID);
                $publisher = $this->query('select * from filmcompanies where FilmID = ' . $ID);
                $genre = $this->query('select * from filmgenres where FilmID = ' . $ID);
                $people = $this->query('select * from filmpersones LEFT JOIN roles ON (filmpersones.RoleID=roles.ID) where FilmID = ' . $ID);
                $filmFiles = $this->query('select * from files where FilmID = ' . $ID);

                //Utils::getMemoryReport();

                $film['dir'] = basename(dirname($filmFiles[0]['files']['Path']));

                $this->useDbConfig = $this->defaultConfig;
		        $this->setDataSource($this->useDbConfig);

                $country = array('Country' =>
                           array('Country' => $this->getHabtm($country, 'filmcountries', array('country_id' => 'CountryID'))));
                $publisher = array('Publisher' =>
                             array('Publisher' => $this->getHabtm($publisher, 'filmcompanies', array('publisher_id' => 'CompanyID'))));
                $genre = array('Genre' =>
                         array('Genre' => $this->getHabtm($genre, 'filmgenres', array('genre_id' => 'GenreID'))));
                $people = Utils::iconvRecursive($people);

                $people = array('Person' =>
                         array('Person' => $this->getHabtm($people, array ('filmpersones', 'roles'),
                                           array('person_id' => 'PersonID', 'role' => 'RoleExt', 'profession_id' => 'RoleID'))));


                $filmType = $this->FilmType->findByTitle($TypeOfMovie);
                $film['film_type_id'] = $filmType['FilmType']['id'];

                $this->FilmsPerson->deleteAll(array('film_id' => $ID));
                $this->CountriesFilm->deleteAll(array('film_id' => $ID));
                $this->FilmsPublisher->deleteAll(array('film_id' => $ID));
                $this->FilmsGenre->deleteAll(array('film_id' => $ID));

                $oldInfo = $this->read(array('Film.is_license'), $ID);
                if (!empty($oldInfo))
                {
                	$film['is_license'] = $oldInfo['Film']['is_license'];//ИНАЧЕ ТЕРЯЕТСЯ ПРИ ОБНОВЛЕНИИ
                }
                else
                {
                	$film['active'] = 2;//НОВЫЕ СКРЫВАЕМ (ДЛЯ ПОСТЕПЕННОЙ ПУБЛИКАЦИИ В ПОСЛЕДУЮЩЕМ)
                }
                $save = am(array($this->name => $film), $country, $publisher, $genre, $people);

                $this->create();
                $this->save($save);
//*

//ПР�? МНОЖЕСТВЕННЫХ ОШ�?БКАХ ВО ВРЕМЯ М�?ГРАЦ�?�? БЛОК ЗАКОММЕНТ�?РОВАТЬ

//ОБНОВЛЕН�?Е ДАННЫХ В СВЯЗАННОЙ ВЕТКЕ ФОРУМА
            	if (!empty($save['Film']['thread_id']))
            	{
            		$save['FilmPicture'] = $this->FilmPicture->findAll(array('FilmPicture.film_id' => $save['Film']['id']), null, null, null, null, 0);
            		$threadTxts = $this->getThreadText($save);

            		$threadInfo = array('Thread' =>
            			array(
            				'threadid' => $save['Film']['thread_id'],
							'title' => $threadTxts['title'],
						)
					);
					$this->Thread->save($threadInfo);
            		$post = $this->Thread->Vbpost->find(array('Vbpost.parentid' => 0, 'Vbpost.threadid' => $save['Film']['thread_id']), array('postid'), 'Vbpost.postid ASC');
            		if ($post)
            		{
	            		$postInfo = array('Vbpost' =>
	            			array(
	            				'postid' => $post['Vbpost']['postid'],
								'pagetext' => $threadTxts['text'],
								'title' => $threadTxts['title'],
							)
						);
						$result = $this->Thread->Vbpost->save($postInfo);//ОБНОВ�?Л�? ДАННЫЕ В ВЕТКЕ
					}
            	}
//*/
            	Cache::delete('Catalog.film_view_' . $ID, 'media');

                $Poster = explode("\n", $Poster);
                $SmallPoster = explode("\n", $SmallPoster);
                $BigPosters = explode("\n", $BigPosters);
                $Frames = explode("\n", $Frames);


                $this->FilmPicture->deleteAll(array('film_id' => $ID));

                $picturesCmd .= $this->savePics($Poster, $film, 'poster');
                $picturesCmd .= $this->savePics($SmallPoster, $film, 'smallposter');
                $picturesCmd .= $this->savePics($BigPosters, $film, 'bigposter');
                $picturesCmd .= $this->savePics($Frames, $film, 'frame');

/*
//BLOCK 1
                $variant = $this->FilmVariant->findByFilmId($ID);
//УДАЛЕН�?Е БЕЗ УЧЕТА ВЕРС�?Й Ф�?ЛЬМОВ, ВВЕДЕННЫХ ЧЕРЕЗ КАТАЛОГ
                $this->FilmVariant->deleteAll(array('film_id' => $ID));
                $this->FilmVariant->FilmFile->deleteAll(array('film_variant_id' => $variant['FilmVariant']['id']));
                $this->FilmVariant->Track->deleteAll(array('film_variant_id' => $variant['FilmVariant']['id']));
//END OF BLOCK 1
//*/

/*
	!!! ДОЛЖЕН БЫТЬ ЗАКОММЕНТ�?РОВАН Л�?БО БЛОК 1, Л�?БО БЛОК 2 !!!
*/

//*
//BLOCK 2
//ВЫБОРКА ВЕРС�?�? Ф�?ЛЬМА, ВНЕСЕННОЙ ЧЕРЕЗ М�?ГРАЦ�?Ю
				$variant = $this->FilmVariant->find(array('FilmVariant.film_id' => $ID, array("OR" => array("FilmVariant.flag_catalog" => 0, "FilmVariant.flag_catalog IS NULL"))));
//ВЫБОРКА ВЕРС�?Й Ф�?ЛЬМОВ, ВВЕДЕННЫХ ЧЕРЕЗ КАТАЛОГ
				$catalogVariants = $this->FilmVariant->findAll(array('FilmVariant.film_id' => $ID, "FilmVariant.flag_catalog" => 1));
				$catVarIds = array();
				if (count($catalogVariants) > 0)
				{
//ОПРЕДЕЛ�?М МАСС�?В �?Д "каталожных" вариантов
					foreach ($catalogVariants as $c)
						$catVarIds[] = $c["FilmVariant"]["id"];
				}
				//УСЛОВ�?Е NOT IN ВЫГЛЯД�?Т ТАК
				//"NOT" => array("FilmVariant.id" => $catVarIds)
				$delFilmFileCondition = array('film_variant_id' => $variant['FilmVariant']['id']);
                $delTrackCondition = array('film_variant_id' => $variant['FilmVariant']['id']);
                $delFilmVariantCondition = array('film_id' => $ID);
                if (!empty($catVarIds))
                {
					$delFilmFileCondition["NOT"] = array("FilmFile.film_variant_id" => $catVarIds);
	                $delTrackCondition["NOT"] = array("Track.film_variant_id" => $catVarIds);
	                $delFilmVariantCondition["NOT"] = array("FilmVariant.id" => $catVarIds);
                }

                $this->FilmVariant->FilmFile->deleteAll(
                	$delFilmFileCondition
                );
                $this->FilmVariant->Track->deleteAll(
					$delTrackCondition
                );
                $this->FilmVariant->deleteAll(
                	$delFilmVariantCondition
                );
//END OF BLOCK 2
//*/
                $Runtime = implode(':', Utils::secs2hms($Runtime));
                $this->FilmVariant->VideoType->recursive = -1;
                $videoType = $this->FilmVariant->VideoType->findByTitle($Quality);
                //pr($videoType);
                $save = array('FilmVariant' => array('film_id' => $ID, 'video_type_id' => $videoType['VideoType']['id'],
                                                     'resolution' => $Resolution, 'duration' => $Runtime, 'active' => (!$Hide)));
                $this->FilmVariant->create();
                $this->FilmVariant->save($save);
                $this->FilmVariant->recursive = -1;
                $filmVariant = $this->FilmVariant->read();

                $translation = $this->FilmVariant->Track->Translation->findByTitle($Translation);

                $this->FilmVariant->Track->Language->recursive = -1;
                if ($translation['Translation']['title'] != 'На языке оригинала')
                    $language = $this->FilmVariant->Track->Language->findByTitle($this->FilmVariant->Track->Language->languages[0]);
                else
                    $language = $this->FilmVariant->Track->Language->findByTitle($this->FilmVariant->Track->Language->languages[1]);

                $save = array('Track' => array('film_variant_id' => $filmVariant['FilmVariant']['id'],
                                               'translation_id' => $translation['Translation']['id'],
                                               'language_id' => $language['Language']['id'],
                                               'audio_info' => $AudioInfo));

                $this->FilmVariant->Track->create();
                $this->FilmVariant->Track->save($save);


                $filmDir = '';
                foreach ($filmFiles as $filmFile)
                {
                    extract($filmFile['files'], EXTR_PREFIX_ALL, 'file');
                    $save = array('FilmFile' => array('film_variant_id' => $filmVariant['FilmVariant']['id'],
                                                      'file_name' => basename($file_Path), 'md5' => $file_MD5,
                                                      'size' => $file_Size, 'dcpp_link' => $file_dcppLink,
                                                      'ed2k_link' => $file_ed2kLink, 'server_id' => 0));

                    $this->FilmVariant->FilmFile->create();
                    $this->FilmVariant->FilmFile->save($save);
                }

                //Utils::getMemoryReport();
                $this->useDbConfig = 'migration';
		        $this->setDataSource($this->useDbConfig);
            }
            //Utils::getMemoryReport();

            $page++;
            $query = $sql . sprintf($limit, ($page - 1) * $perPage, $perPage);
            //die();
	        //file_put_contents(APP . 'migration_film_pics.cmd', $picturesCmd);
	        $picsFile = fopen(APP . 'migration_film_pics.cmd', 'a+');
	        if ($picsFile)
	        {
	        	fwrite($picsFile, $picturesCmd);
	        	fclose($picsFile);
	        }
	        $picturesCmd = '';
			//if ($page > 10) break; //ОСТАЛЬНОЕ ЧЕРЕЗ УСТАНОВКУ НОВОЙ ТОЧКИ (использовать, http://92.63.196.3/_hawk)
			// И ОБНОВЛЕНИЯ ИНФЫ О ФИЛЬМАХ (использовать, http://92.63.196.3/_hawk/finddiff.php)
        }

        $this->useDbConfig = $this->defaultConfig;
        $this->setDataSource($this->useDbConfig);
    }

    public function getMaxFilmId()
    {
    	if (!$max= Cache::read('Catalog.film_max_id', 'searchres'))
    	{
			$max = $this->query('select id from films order by id desc limit 1');
			Cache::write('Catalog.film_max_id', $max, 'searchres');
    	}
		$max = $max[0]['films']['id'];
		if (empty($max))
			$max = 20000;
		return $max;
    }

    public function getRandomIds()
    {
    	if (!$rIds = Cache::read('Catalog.random_ids', 'searchres'))
    	{
			$ids = $this->query('select id from films');
			foreach ($ids as $i)
			{
				$rIds[] = $i['films']['id'];
			}
			Cache::write('Catalog.random_ids', $rIds, 'searchres');
    	}
    	srand((float)microtime() * 1000000);
    	shuffle($rIds);
    	$rIds = array_slice($rIds, 0, 30);
    	return $rIds;
    }

    public function getRandomFilm()
    {
    	srand((float) microtime() * 10000000);
		static $cnt = 1;
		$max = $this->getMaxFilmId();
		$limit = 10;
		//$id = rand(1, $max);
		//$film = Cache::read('Catalog.film_view_' . $id, 'media');
		$film = null;
        $this->recursive = 0;
        $this->contain(array('FilmType',
                                     'Genre',
                                     'Thread',
                                     'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
                                     'Country',
                                     'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
                                     'MediaRating',
                                  )
                             );
		while (!$film)
		{
			$id = rand(1, $max);
			if(($limit) < 0)
				break;
			if(($limit--)<=0)
			{
				$id = $max;
			}
			$film = Cache::read('Catalog.film_view_' . $id, 'media');
			if (!$film)
			{
	        	$film = $this->read(null, $id);
	        	if (($film) && (isset($film["FilmVariant"])))
	        	{
		    		Cache::write('Catalog.film_view_' . $id, $film,'media');
	        	}
		    	else
		    	{
		    		$film = null;
		    	}
			}
			$cnt++;
		}

/*
print_r($max);
echo '<pre>';
print_r($id);
print_r($film);
echo '</pre>';
exit;
//*/
		return $film;
    }

    function savePics($pics, $film, $type = 'poster')
    {
        $picturesFrom = $this->picturesFrom;
        $picturesTo = $this->picturesTo;

        $picturesCmd = '';

        $picSql = 'INSERT INTO film_pictures (file_name, film_id, type) VALUES ';
        $values = array();


        foreach ($pics as $image)
        {
            $image = trim($image);
            if (empty($image))
                continue;
            $values[] = '(\'' . $image . '\', \'' . $film['id'] . '\', \'' . $type . '\')';
            if ($type != 'frame')
                $picturesCmd .= $picturesFrom . $image . '" ' . $picturesTo . $image . "\"\r\n";
        }

        if (!empty($values))
        {
            $picSql .= implode(', ', $values);
            $this->query($picSql);
        }

        if ($type == 'frame')
            $picturesCmd .= $picturesFrom . 'frames\\' . $film['id'] . '_'.$film['dir'].'" '
                            . $picturesTo . 'frames\\' . $film['id'] . '_'.$film['dir'].'"' . "\r\n";

        return $picturesCmd;
    }

	function set_input_server($dir='',$ip=FALSE, $all = false)
	{
		//$dir='asbc';
		$letter=strtolower(substr($dir,0,1));
		//echo $letter;
		if(( $letter >= '0' and $letter <= '9')||$letter=='0')$letter='0-999';
		//$this->layout='ajax';
		//$this->data
		$servers=Configure::read('Catalog.downloadServers');
		//pr($servers);
    	$ip=(isset($_REQUEST['ip']))?$_REQUEST['ip']:$_SERVER['REMOTE_ADDR'];
		$zones=Configure::read('Catalog.allowedIPs');
    	$zone = checkAllowedMasks($zones,$ip,1);

		$serversbyZone=Set::combine($servers,'{n}.server','{n}','{n}.zone');

		$serversinZonebyLetter=array();
		$outservers=array();

		//Ищем все сервера в зоне у которых есть эта буква
    	if(empty($zone)) $zone = 'default';
//*
		foreach ($serversbyZone[$zone] as $serv)
		{
			list($letterA,$letterZ)=explode('-',$serv['letter']);
			//echo $letterA." ".$letterZ."<br>";
			if(ord($letter) >=ord($letterA)  and ord($letter) <= ord($letterZ))$outservers[]=$serv;
		}
//*/

/*
		foreach ($servers as $serv)
		{
			list($letterA,$letterZ)=explode('-',$serv['letter']);
			if (ord($letter) >= ord($letterA) and ord($letter) <= ord($letterZ))
			{
				$outservers[]=$serv;
			}
		}
*/
//pr($outservers);
		if (count($outservers) < 1)
			return NULL;
		elseif (count($outservers)==1)
			$downloadServer=$outservers[0]['server'].$letter."/".$dir;
		else
		{
			$num=mt_rand(0,count($outservers)-1);
			$downloadServer=$outservers[$num]['server'].$letter."/".$dir;
		}

		if ($all)
		{
			$mirrors = array();
			foreach ($outservers as $s)
			{
				$mirrors[] = $s['server'] . $letter . "/" . $dir;
			}
			return $mirrors;
		}

		return $downloadServer;
	}


	function set_input_share($dir='',$ip=FALSE)
	{
		$letter=strtolower(substr($dir,0,1));
		if(( $letter >= '0' and $letter <= '9')||$letter=='0')$letter='0-999';
		$servers=Configure::read('Catalog.downloadServers');
    	$ip=(isset($_REQUEST['ip']))?$_REQUEST['ip']:$_SERVER['REMOTE_ADDR'];
		$zones=Configure::read('Catalog.allowedIPs');
    	$zone = checkAllowedMasks($zones,$ip,1);
		$zone = $zones[$zone]['zone'];
		$serversbyZone=Set::combine($servers,'{n}.share','{n}','{n}.zone');

		$serversinZonebyLetter=array();
		$outservers=array();

		//�?щем все сервера в зоне у которых есть эта буква
		if(!isset($serversbyZone[$zone]))$zone='default';
		foreach ($serversbyZone[$zone] as $serv)
		{
			list($letterA,$letterZ)=explode('-',$serv['letter']);
			if(ord($letter) >=ord($letterA)  and ord($letter) <= ord($letterZ))$outservers[]=$serv;
		}

		if(count($outservers)<1)return NULL;
		return $outservers[0]['share'].$letter."/".$dir;
	}

    /**
     * Получает список фильмов с жанрами
     *
     * @return array
     */
    public function getFilmsWithGenres()
    {
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_en';
        $sql = 'select Film.id, Film.title' . $langFix . ', g.id from films as Film
         join films_genres as fg on (fg.film_id=Film.id)
         join genres as g on (fg.genre_id = g.id)
         where Film.active = 1 order by g.id, Film.title';

        $this->contain(array());
        $records = $this->query($sql);
        return $records;
    }

    /**
     * Получает список фильмов с картинками
     *
     * @return array
     */
    public function getFilmsWithPictures()
    {
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_en';
        $sql = 'select Film.id, Film.title' . $langFix . ', p.file_name from films as Film
         join film_pictures as p on (p.film_id = Film.id and p.type="poster")
         where Film.active = 1 group by Film.id';

        $this->contain(array());
        $records = $this->query($sql);
        return $records;
    }

	/**
	 * рекурсивное переключение моделей на указанный профиль из config
	 *
	 * @param string $confName
	 */
	public function useDbRecursive($confName, &$model)
	{
		if (empty($confName)) return false;
		$model->useDbConfig = $confName;
		$alst = $model->getAssociated();

		foreach ($alst as $key => $value)
		{
			if ($model->$key->useDbConfig <> $confName)
			{
				$this->useDbRecursive($confName, $model->$key);
			}
		}
		return true;
	}
}

?>