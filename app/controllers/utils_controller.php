<?php
App::import('Model', 'MediaModel');
class UtilsController extends AppController
{
    var $name = 'Utils';
    var $uses = array('Person', 'Country', 'Genre', 'Publisher', 'Film', 'Profession',
                      'VideoType', 'FilmType', 'Translation', 'Language', 'Migration',
                      'SearchLog','FilmClick','FilmFile','Transtat','RedirectCount',
                      'Tvfilm','Tvcategory','Tvchannel',
                      'Banner',
                      'OzonCategory', 'OzonProduct');

    var $filmTypes = array();
    var $translations = array();

    /**
     * модель баннеров
     *
     * @var AppModel
     */
    var $Banner;

    /**
     * модель Статистики кликов на скачивание фильма
     *
     * @var AppModel
     */
    var $FilmClick;

    /**
     * модель фильмов ТВ программы
     *
     * @var AppModel
     */
    var $Tvfilm;
    /**
     * модель категорий ТВ программы
     *
     * @var AppModel
     */
    var $Tvcategory;
    /**
     * модель каналов ТВ программы
     *
     * @var AppModel
     */
    var $Tvchannel;

    function __construct()
    {
//        if (Configure::read() < 2)
//            die();
        parent::__construct();

        $this->filmTypes[] = "";
        $this->filmTypes[] = "Не определен";
        $this->filmTypes[] = "Документальный сериал";
        $this->filmTypes[] = "Документальный фильм";
        $this->filmTypes[] = "Научно - популярный фильм";
        $this->filmTypes[] = "Концерт";
        $this->filmTypes[] = "Короткометражный фильм";
        $this->filmTypes[] = "Мультсериал";
        $this->filmTypes[] = "Мьюзикл-опера";
        $this->filmTypes[] = "Полнометражный мульт";
        $this->filmTypes[] = "Сборник мультфильмов";
        $this->filmTypes[] = "Спорт. видеопрограмма";
        $this->filmTypes[] = "Телеспектакль";
        $this->filmTypes[] = "Телепередача";
        $this->filmTypes[] = "Худ. кинофильм";
        $this->filmTypes[] = "Худ. телесериал";
        $this->filmTypes[] = "Худ. телефильм";
        $this->filmTypes[] = "Черно-белый";

        $this->translations[] = "";
        $this->translations[] = "Не определен";
        $this->translations[] = "Дубляж";
        $this->translations[] = "На языке оригинала";
        $this->translations[] = "Профессиональный многоголосый";
        $this->translations[] = "Профессиональный одноголосый";
        $this->translations[] = "Любительский многоголосый";
        $this->translations[] = "Одноголосый";
        $this->translations[] = "Субтитры";
        $this->translations[] = "Tycoon Studio";
        $this->translations[] = "Гоблин (правильный)";
        $this->translations[] = "Гоблин (смешной)";
        $this->translations[] = "секта Володарского";
    }



    function additional($date = null, $initial = false)
    {
        ini_set('memory_limit', '1G');
        $this->Country->migrate($date);
        $this->Genre->migrate($date);
        $this->Profession->migrate($date);
        $this->Publisher->migrate($date);
        $this->VideoType->migrate($date);

        if (!$initial)
            return;

        $this->FilmType->migrate($this->filmTypes);
        $this->Translation->migrate($this->translations);
        $this->Language->migrate($this->Language->languages);
    }

    function films($date = null)
    {
        ini_set('memory_limit', '1G');
        $this->Film->migrate($date);
    }

    function people($date = null)
    {
        ini_set('memory_limit', '1G');
        $this->Person->migrate($date);
    }

    function migrate()
    {
        ini_set('memory_limit', '1G');
        set_time_limit(50000000000);

        $endDate = $this->Migration->lastCheckDate();
        $date = ($endDate ? ' WHERE timestamp <= "' . $endDate . '"' : ' ');

        $this->additional($date, true);
        $this->people($date);
        $this->films($date);

        $this->Migration->checkPoint();
    }


    function migrate_incremental()
    {
        ini_set('memory_limit', '1G');
        set_time_limit(50000000000);

        //УДАЛЕНИЕ СООБЩЕНИЯ О ЛИЦЕНЗИИ
		system('wget http://www.videoxq.com/admin/utils/fix_license');

        $startDate = $this->Migration->lastMigrationDate();
        $endDate = $this->Migration->lastCheckDate();

        $date = ' WHERE ' . ($startDate ? ' timestamp >= "' . $startDate . '"' : ' ') . ($endDate ? ($startDate ? ' AND ' : ' ') . 'timestamp <= "' . $endDate . '"' : ' ');

        $this->additional($date);
        $this->people($date);
        $this->films($date);

        $this->Migration->checkPoint();
		cache::delete('Catalog.indexFilter', 'default');
		cache::delete('Catalog.topFilms', 'default');
		cache::delete('Catalog.filmStats', 'default');
		cache::delete('Catalog.peopleIndex', 'default');
		cache::clear(false, 'searchres');//СБРАСЫВАЕМ ВЕСЬ КЭШ ТК В НЕИ ХРАНИМ ПОСТРАНИЧНЫЕ И ПОИСКОВЫЕ РЕЗУЛЬТАТЫ
		echo `indexer --rotate --all`;

		//ОБНОВЛЕНИЕ sitemap.xml
		//system('wget -O ' . $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/sitemap.xml http://www.videoxq.com/admin/media/sitemap');
		//ОБНОВЛЕНИЕ rss.xml
		system('wget -O ' . $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/rss.xml http://www.videoxq.com/media/rsslist');
    }


	function admin_banners()
	{
        $this->Banner->recursive = 0;
		unset($this->paginate);
		$this->paginate['Banner']['fields'] = array('Banner.id', 'Banner.name', 'Banner.start', 'Banner.stop', 'Banner.place', 'Banner.forever', 'Banner.is_webstream', 'Banner.is_internet');
        $this->paginate['Banner']['limit'] = '';
        //$this->paginate['Banner']['order'] = 'Banner.srt desc';
		$banners = $this->paginate('Banner');
		$this->set('banners', $banners);
	}

	/**
	 * добавление/изменение баннера
	 *
	 * @param integer $id - идентификатор баннера
	 */
	function admin_banners_edit($id = null)
	{
		if (!empty($this->data))
		{
			if (empty($this->data['Banner']['id']))
			{
				$this->Banner->create();
			}
			if (empty($this->data['Banner']['start']))
				$this->data['Banner']['start'] = '0000-00-00 00:00:00';
			if (empty($this->data['Banner']['stop']))
				$this->data['Banner']['stop'] = '0000-00-00 00:00:00';
			if (empty($this->data['Banner']['fixed']))
				$this->data['Banner']['fixed'] = 0;
			if (empty($this->data['Banner']['forever']))
				$this->data['Banner']['forever'] = 0;
			if (empty($this->data['Banner']['srt']))
				$this->data['Banner']['srt'] = 0;
			if (empty($this->data['Banner']['priority']))
				$this->data['Banner']['priority'] = 1;
			if (empty($this->data['Banner']['code']))
				$this->data['Banner']['code'] = '';
			if (empty($this->data['Banner']['tail']))
				$this->data['Banner']['tail'] = '';

	        if (!empty($this->data['Banner']['id'])) //СБРАСЫВАЕМ КЭШ СТАРОГО МЕСТА
	        {
		        $old = $this->Banner->read(null, $this->data['Banner']['id']);
				Cache::delete('Catalog.banners4' . $old['Banner']['place'], 'searchres');
				Cache::delete('Catalog.banners4' . $old['Banner']['place'] . '_0', 'searchres');
				Cache::delete('Catalog.banners4' . $old['Banner']['place'] . '_1', 'searchres');
	        }

            if ($this->Banner->save($this->data))
            {
                $this->Session->setFlash('The Banner saved');
				Cache::delete('Catalog.banners4' . $this->data['Banner']['place'], 'searchres');
				Cache::delete('Catalog.banners4' . $this->data['Banner']['place'] . '_0', 'searchres');
				Cache::delete('Catalog.banners4' . $this->data['Banner']['place'] . '_1', 'searchres');
                $this->redirect(array('action' => 'banners'), null, true);
            } else
            {
                $this->Session->setFlash('The Banner could not be saved. Please, try again.');
            }
		}
		else
		{
	        if ($id)
	        {
		        $this->data = $this->Banner->read(null, $id);
		        if (empty($this->data))
		        {
	            	$this->Session->setFlash('Invalid id for Banner');
	            	$this->redirect(array('action' => 'banners'), null, true);
		        }
	        }
		}
	}

    function admin_banners_delete($id = null)
    {
        if (!$id)
        {
            $this->Session->setFlash('Invalid id for Banner');
            $this->redirect(array('action' => 'banners'), null, true);
        }

        $banner = $this->Banner->read(array('place'), $id);
        if ($this->Banner->del($id))
        {
			Cache::delete('Catalog.banners4' . $banner['Banner']['place'], 'searchres');
			Cache::delete('Catalog.banners4' . $banner['Banner']['place'] . '_0', 'searchres');
			Cache::delete('Catalog.banners4' . $banner['Banner']['place'] . '_1', 'searchres');
            $this->Session->setFlash('Banner #' . $id . ' deleted');
            $this->redirect(array('action' => 'banners'), null, true);
        }
    }

	function admin_memcache()
	{
		$this->layout = 'admin';
		if (!empty($_REQUEST["IMG"]))
		{
			$this->layout = 'playlist';
		}
	}

    /**
     * действие вырезает сообщение об истекшей лицензии из поля description
     * в "родной" базе и базе видеокаталога
     *
     */
    function admin_fix_license()
    {
    	$db = &ConnectionManager::getDataSource($this->Film->useDbConfig);
    	//$db = &ConnectionManager::getDataSource("productionMedia");
    	$sql = 'select id, description from films where description regexp "<div(.*)?solid red(.*)?div>(.*)?"';
    	$query = $db->connection->query($sql);
    	$filmsResult = array();
    	while ($row = mysqli_fetch_row($query))
    	{
    		$description = $row[1];
    		$description = ereg_replace('<div(.*)?solid red(.*)?div>', '', $description);
    		$row[] = $description;
    		$filmsResult[] = $row;
    		$db->connection->query('update films set description = "' . addslashes($description). '" where id = ' . $row[0]);
    	}

    	$db = &ConnectionManager::getDataSource("migration");
    	$query = $db->connection->query($sql);
    	$lmsResult = array();
    	while ($row = mysqli_fetch_row($query))
    	{
    		$description = $row[1];
    		$description = ereg_replace('<div(.*)?solid red(.*)?div>', '', $description);
    		$row[] = $description;
    		$lmsResult[] = $row;
    		$db->connection->query('update films set description = "' . addslashes($description). '" where id = ' . $row[0]);
    	}

    	$this->set('filmsResult', $filmsResult);
    	$this->set('lmsResult', $lmsResult);
    }

    /**
     * действие для периодического вызова (несколько раз в сутки)
     * упорядочивает статистику поиска, подсчитывает количество одинаковых поисковых запросов
     * устанавливает счетчик, удаляет дубли
     *
     */
    function admin_search_logs_cron()
    {
		//корректировка счетчиков
    	$db = &ConnectionManager::getDataSource($this->SearchLog->useDbConfig);
//pr($db->connection);
//exit;
    	$sql = '
CREATE TEMPORARY TABLE sl_temp LIKE search_logs;
    	';
    	$db->connection->query($sql);

    	$sql = '
INSERT into sl_temp SELECT * from search_logs;
    	';
    	$db->connection->query($sql);

    	$sql = '
UPDATE search_logs set search_logs.hits = (select count(sl_temp.id)+sum(sl_temp.hits)-1 from sl_temp where sl_temp.keyword = search_logs.keyword group by sl_temp.keyword);
    	';
    	$db->connection->query($sql);

    	$sql = '
UPDATE search_logs set updated = (select max(sl_temp.created) from sl_temp where sl_temp.keyword = search_logs.keyword group by sl_temp.keyword);
    	';
    	$db->connection->query($sql);

		//УДАЛЯЕМ ДУБЛИ
    	$sql = '
TRUNCATE sl_temp;
    	';
    	$db->connection->query($sql);
    	$sql = '
INSERT into sl_temp SELECT * from search_logs group by keyword order by id;
    	';
    	$db->connection->query($sql);

    	$sql = '
UPDATE sl_temp set hits = 1 where hits <= 0;
    	';
    	$db->connection->query($sql);

    	$sql = '
truncate search_logs;
    	';
    	$db->connection->query($sql);
    	$sql = '
INSERT into search_logs SELECT * from sl_temp;
    	';
    	$db->connection->query($sql);
    	$this->layout = 'ajax';
    }

    function admin_ozon($action = '')
    {
		$categoriesAdd = 0;
		$channelsAdd = 0;
		$filmsAdd = 0;
		$ozonList = array();
		$i = 0;
		$ozonList[$i]['url'] = 'http://www.ozon.ru/multimedia/yml/partner/detfilm.zip';
		$ozonList[$i]['zip'] = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/detfilm.zip';
		$ozonList[$i]['xml'] = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/detfilm.xml';

		$i++;
		$ozonList[$i]['url'] = 'http://www.ozon.ru/multimedia/yml/partner/kino.zip';
		$ozonList[$i]['zip'] = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/kino.zip';
		$ozonList[$i]['xml'] = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/kino.xml';

		$i++;
		$ozonList[$i]['url'] = 'http://www.ozon.ru/multimedia/yml/partner/movie.zip';
		$ozonList[$i]['zip'] = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/movie.zip';
		$ozonList[$i]['xml'] = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/movie.xml';

    	switch ($action)
    	{
    		case "import":
    			set_time_limit(10000000);
		    	//СКАЧИВАЕМ С ИСТОЧНИКА И СОХРАНЯЕМ ВО ВРЕМЕННУЮ ДИРЕКТОРИЮ
   				$bufLen = 4096 * 20; $i = 0;
   				$errors = array();
//*
    			foreach ($ozonList as $ol)
    			{
	    			if ($r = fopen($ol['url'], 'r'))
	    			{
	    				if ($w = fopen($ol['zip'], 'w'))
	    				{
		    				while (!feof($r))
		    				{
		    					$buf = fread($r, $bufLen);
		    					fwrite($w, $buf);
		    				}
		    				fclose($w);
	    				}
	    				else
	    				{
		    				$errors[] = 'невозможно сохранить "' . $ol['zip'] . '"';
		    				fclose($r);
		    				break;
	    				}
	    				fclose($r);

	    				//РАЗВОРАЧИВАЕМ ИЗ АРХИВА
						if ($z = zip_open($ol['zip']))
						{
						    while ($zip_entry = zip_read($z))
						    {
						        if (zip_entry_open($z, $zip_entry, "r"))
						        {
				    				$w = fopen($ol['xml'], 'w');
						        	while ($buf = zip_entry_read($zip_entry, $bufLen))
						        	{
						        		fwrite($w, $buf);
						        	}
						        	fclose($w);
						            zip_entry_close($zip_entry);
						        }
						    }
						    zip_close($z);
						}
						else
						{
							$errors[] = 'ошибка архива "' . $ol['zip'] . '"';
							break;
						}
	    			}
	    			else
	    			{
	    				$errors[] = 'источник "' . $ol['url'] . '" не доступен';
	    				break;
	    			}
	    			$i++;
    			}
    			if ($i < count($ozonList))
    			{
    				$errors[] = 'ошибка подготовки дампов данных';
    			}
//*/
    			if (empty($errors))
    			{
			    	$db = &ConnectionManager::getDataSource($this->SearchLog->useDbConfig);
	    			//ОЧИЩАЕМ ТАБЛИЦЫ КАТЕГОРИЙ И ТОВАРОВ
			    	$sql = 'truncate ozon_categories;';
			    	$db->connection->query($sql);
			    	$sql = 'truncate ozon_products;';
			    	$db->connection->query($sql);
			    	$sql = 'truncate ozoncategories_ozonproducts;';
			    	$db->connection->query($sql);

					//ПАРСИНГ
					global $tag;
					global $habtms;
					global $categories;
					global $category;
					global $products;
					global $product;

					$tag = '';
					$categories = array();
					$products = array();
					$habtms = array();

					function startElement($parser, $name, $attrs)
					{
					    global $tag;
					    global $habtms;
						global $category;
						global $product;
					    $tag = $name;

					    switch ($name)
					    {
					    	case "CATEGORY":
				    			$category = array();
					    		if (!empty($attrs['ID']))
					    		{
									$category['id'] = $attrs['ID'];
									if (!empty($attrs['PARENTID']))
									{
										$category['parent_id'] = $attrs['PARENTID'];
									}
					    		}
					    	break;
					    	case "OFFER":
				    			$product = array();
					    		if (!empty($attrs['ID']))
					    		{
									$product['id'] = $attrs['ID'];
					    			$habtms[$product['id']] = array();
									$product['avail'] = intval((strtolower($attrs['AVAILABLE']) == 'true'));
					    		}
					    	break;
					    }
					}

					function characterData($parser, $data)
					{
					    global $tag;
					    global $habtms;
						global $category;
						global $product;

						if (!trim($data))
							return;

						switch ($tag)
						{
					    	case "CATEGORY":
								//$data = iconv('windows-1251', 'utf-8', $data);
								$category['title'] = $data;
							break;

					    	case "TITLE":
								//$data = iconv('windows-1251', 'utf-8', $data);
								$product['title'] = $data;
							break;

							case "CATEGORYID":
								$habtms[$product['id']][] = $data;
							break;

					    	case "URL":
					    		$data = explode('/?', $data);
								$product['url'] = $data[0] . '/?from=' . Configure::read('Ozon.partnerId');
					    	break;

					    	case "PRICE":
								$product['price'] = $data;
					    	break;

					    	case "CURRENCYID":
								$product['currency'] = $data;
					    	break;

					    	case "PICTURE":
								$product['picture'] = $data;
					    	break;

					    	case "DELIVERY":
								$product['delivery'] = intval((strtolower($data) == 'true'));
					    	break;

					    	case "YEAR":
								$product['year'] = $data;
					    	break;

					    	case "MEDIA":
								//$data = iconv('windows-1251', 'utf-8', $data);
								$product['media'] = $data;
					    	break;

					    	case "ORIGINALNAME":
								$data = iconv('windows-1251', 'utf-8', $data);
								$product['original_name'] = $data;
					    	break;
						}
					}

					function endElement($parser, $name)
					{
						global $category;
						global $product;
						global $categories;
						global $products;

					    switch ($name)
					    {
					    	case "CATEGORY":
					    		if (!empty($category['id']))
					    		{
					    			$categories[] = $category;
					    		}
					    	break;
					    	case "OFFER":
					    		if (!empty($product['id']))
					    		{
					    			$products[] = $product;
					    		}
					    	break;
					    }
					}

			    	foreach($ozonList as $ol)
			    	{
						$xml_parser = xml_parser_create();
						xml_set_element_handler($xml_parser, "startElement", "endElement");
						xml_set_character_data_handler($xml_parser, "characterData");

						if ($fp = fopen($ol['xml'], "r"))
						{
							while ($data = fread($fp, $bufLen)) {
								//СОХРАНЯЕМ В БАЗУ ПО МЕРЕ РАЗБОРА
								if (!empty($categories))
								{
									$this->OzonCategory->saveAll($categories);
									$categories = array();
								}
								if (!empty($products))
								{
									$this->OzonProduct->saveAll($products);
									foreach ($habtms as $key => $cats)
									{
										foreach ($cats as $ct)
										{
								    		$sql = 'insert ozoncategories_ozonproducts (category_id, product_id) values(' . $ct . ', ' . $key . ');';
								    		$db->connection->query($sql);
										}
									}
									$habtms = array();
									$products = array();
								}
							    if (!xml_parse($xml_parser, $data, feof($fp))) {
							    	continue;

							        die(sprintf("XML error: %s at line %d",
							                    xml_error_string(xml_get_error_code($xml_parser)),
							                    xml_get_current_line_number($xml_parser)));
							    }
							}
							fclose($fp);
						}
						xml_parser_free($xml_parser);
			    	}
    			}
    			else
    			{
    				$errors[] = 'Операция импорта не выполнена';
    			}
				$this->set('errors', $errors);

    		default:
    			//СТАТИСТИКА ПО КАТАЛОГУ ОЗОНА
				foreach ($ozonList as $key => $ol)
				{
					if (file_exists($ol['xml']))
						$ozonList[$key]['updated'] = filemtime($ol['xml']);
				}
				$this->set('ozonList', $ozonList);
				$this->set('ozonCategoryCount', $this->OzonCategory->findCount());
				$this->set('ozonProductCount', $this->OzonProduct->findCount());
    	}
		$this->set('action', $action);
    }

    function admin_tvs($action = '')
    {
		$needCategories = array(
			'Художественный фильм',
			'Сериал',
			'Детям',
		);
		$i = 0;
		$categoriesAdd = 0;
		$channelsAdd = 0;
		$filmsAdd = 0;
    	switch ($action)
    	{
    		case "import":
    			$url = "http://www.teleguide.info/download/new3/tvguide.zip";
   				$zip = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/tv.zip';
   				$xml = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/tv.xml';

//СКАЧИВАНИЕ С ИСТОЧНИКА
   				$bufLen = 4096 * 20;
    			if ((!file_exists($zip)) && ($r = fopen($url, 'r')))
    			{
    				if ($w = fopen($zip, 'a+'))
    				{
	    				while (!feof($r))
	    				{
	    					$buf = fread($r, $bufLen);
	    					fwrite($w, $buf);
	    				}
	    				fclose($w);
	    				fclose($r);
    				}
    			}

//РАЗВОРАЧИВАНИЕ АРХИВА
				if (file_exists($zip) && ($z = zip_open($zip)))
				{
				    while ($zip_entry = zip_read($z))
				    {
				        if (zip_entry_open($z, $zip_entry, "r"))
				        {
		    				$w = fopen($xml, 'a');
				        	while ($buf = zip_entry_read($zip_entry, $bufLen))
				        	{
				        		fwrite($w, $buf);
				        	}
				        	fclose($w);
				            zip_entry_close($zip_entry);
				        }
				    }
				    zip_close($z);
				}
//ПАРСИНГ
				$file = $xml;
				global $depth;
				global $channelData;
				global $filmData;
				global $tag;
				global $channels;
				global $categories;
				global $films;

				$depth = array();
				$channelData = array();
				$filmData = array();
				$tag = '';
				$channels = array();
				$categories = array();
				$films = array();

				function startElement($parser, $name, $attrs)
				{
					global $channelData;
					global $filmData;
				    global $depth;
				    global $tag;
				    $tag = $name;

				    switch ($name)
				    {
				    	case "CHANNEL":
				    		if (!empty($attrs['ID']))
				    		{
								$channelData['channel'] = $attrs['ID'];
				    		}
				    	break;
				    	case "PROGRAMME":
							$start = sscanf($attrs['START'], "%04s%02s%02s%02s%02s%02s");
							$filmData['start'] = sprintf("%04s-%02s-%02s %02s:%02s:%02s", $start[0], $start[1], $start[2], $start[3], $start[4], $start[5]);
							$filmData['channel'] = $attrs['CHANNEL'];
				    	break;
				    }
				    $depth[$parser]++;
				}

				function characterData($parser, $data)
				{
					global $channelData;
					global $filmData;
				    global $tag;

					if (!trim($data))
						return;

					switch ($tag)
					{
				    	case "DISPLAY-NAME":
							$channelData['name'] = $data;
						break;

				    	case "TITLE":
							$filmData['name'] = $data;
				    	break;

				    	case "CATEGORY":
							$filmData['category'] = $data;
				    	break;
					}
				}

				function endElement($parser, $name)
				{
				    global $depth;
					global $channelData;
					global $filmData;
					global $channels;
					global $categories;
					global $films;

				    $depth[$parser]--;
					$needCategories = array(
						'Художественный фильм',
						'Сериал',
						'Детям',
					);

				    switch ($name)
				    {
				    	case "CHANNEL":
				    		$channels[$channelData['channel']] = $channelData;
				    		$channelData = array();
				    	break;
				    	case "PROGRAMME":
				    		if ((!empty($filmData['category'])) && (in_array($filmData['category'], $needCategories)))
				    		{
				    			$categories[$filmData['category']] = -1;
				    			$films[] = $filmData;
				    		}
				    		$filmData = array();
				    	break;
				    }
				}

				$xml_parser = xml_parser_create();
				xml_set_element_handler($xml_parser, "startElement", "endElement");
				xml_set_character_data_handler($xml_parser, "characterData");

				if ($fp = fopen($file, "r"))
				{
					while ($data = fread($fp, 4096)) {
					    if (!xml_parse($xml_parser, $data, feof($fp))) {
					    	continue;

					        die(sprintf("XML error: %s at line %d",
					                    xml_error_string(xml_get_error_code($xml_parser)),
					                    xml_get_current_line_number($xml_parser)));
					    }
					}
					fclose($fp);
					unlink($xml);
					unlink($zip);
				}
				xml_parser_free($xml_parser);

    			$tvChannels = $this->Tvchannel->findAll();
    			foreach ($tvChannels as $c)
    			{
    				$channels[$c['Tvchannel']['channel']] = $c['Tvchannel'];
    			}

    			$tvCategories = $this->Tvcategory->findAll();
    			foreach ($tvCategories as $c)
    			{
    				$categories[$c['Tvcategory']['name']] = $c['Tvcategory']['id']; //для удобной проверки существующих категорий
    			}

				//СОХРАНЯЕМ НОВЫЕ КАНАЛЫ
				foreach ($channels as $channel => $data)
				{
					if (empty($data['id']))
					{
						$this->Tvchannel->create();
						if ($this->Tvchannel->save(array('Tvchannel' => $data)))
						{
							$channels[$channel]['id'] = $this->Tvchannel->getLastInsertID();
						}
					}
				}

				//СОХРАНЯЕМ НОВЫЕ КАТЕГОРИИ
				foreach ($categories as $category => $id)
				{
					if ($id <= 0)
					{
						$this->Tvcategory->create();
						if ($this->Tvcategory->save(array('Tvcategory' => array('name' => $category))))
						{
							$categories[$category] = $this->Tvcategory->getLastInsertID();
						}
					}
				}
				//СОХРАНЯЕМ НОВЫЕ ФИЛЬМЫ
				$tvUnique = array();
				foreach ($films as $film)
				{
					$start = $film['start'];
					$title = $film['name'];
					$category = $film['category'];
					$channel = $film['channel'];
					if (!empty($start) && !empty($channels[$channel]['id']) && !empty($categories[$category]) && !empty($title))
					{
						if (empty($tvUnique[$start .'_'.$channels[$channel]['id']]))
						{
							$data = array(
								'Tvfilm' => array(
									'start' => $start,
									'tvchannel_id' => $channels[$channel]['id'],
									'tvcategory_id' => $categories[$category],
									'name' => $title,
								)
							);
							$this->Tvfilm->create();
							$this->Tvfilm->save($data);
							$filmsAdd++;
						}
						$tvUnique[$start .'_'.$channels[$channel]['id']] = $channels[$channel]['id'];
					}
				}

/*
echo '<pre>';
print_r($channels);
echo '</pre>';
echo '<pre>';
print_r($categories);
echo '</pre>';
echo '<pre>';
print_r($films);
echo '</pre>';
*/
/*
ПЕРВЫЙ ТУПОЙ ВАРИАНТ
    			$films = array();
				$bufLen = 4096 * 40;
				$buf = ''; $tvUnique = array();
				if (file_exists($xml) && ($f = fopen($xml, "r")))
				{
					while (!feof($f))
					{
						$buf .= fread($f, $bufLen);
						$item = array();
						while (mb_eregi('<([\S]{1,})(.*?)>(.*?)<\/\\1>', $buf, $item))
						{
							//вырезаем найденный тэг
							$strt = mb_strpos($item[0], $buf);
							$buf = mb_substr($buf, $strt + mb_strlen($item[0]));
							switch ($item[1])
							{
								case'channel':
									$channel = array();
									mb_eregi('id="(.*?)"', $item[2], $channel);
									if (!empty($channel[1]))
										$channel = $channel[1];

									if (!empty($item[3]))
									{
										$name = array();
										mb_eregi('<([\S]{1,})(.*?)>(.*?)<\/\\1>', $item[3], $name);
										if (!empty($name[3]))
										{
											$name = $name[3];
											$channel = intval($channel);
											if (((empty($channels[$channel])) && (!in_array($name, $newChannels))))
											{
												$newChannels[$channel] = $name;
												$this->Tvchannel->create();
												$data = array(
													'Tvchannel' => array(
														'channel'	=> $channel,
														'name'		=> $name,
													)
												);
												if ($this->Tvchannel->save($data))
												{
													$channelId = $this->Tvchannel->getLastInsertID();
													$data['Tvchannel']['id'] = $channelId;
													$channels[$channel] = $data['Tvchannel'];
													$channelsAdd++;
												}
											}
										}
									}
								break;
								case'programme':
									$category = array();
									mb_eregi('<category(.*?)>(.*?)<\/category>', $item[0], $category);
									if (!empty($category[2]))
									{
										$category = $category[2];
										if (!in_array($category, $needCategories))
											continue;
										if ((empty($categories[$category])) && (!in_array($category, $newCategories)))
										{
											$newCategories[] = $category;
											$this->Tvcategory->create();
											$data = array(
												'Tvcategory' => array(
													'name'		=> $category,
												)
											);
											if ($this->Tvcategory->save($data))
											{
												$categoryId = $this->Tvcategory->getLastInsertID();
												$categories[$category] = $categoryId;
												$categoriesAdd++;
											}
										}
									}
									else
										continue;

									$title = array();
									mb_eregi('<title(.*?)>(.*?)<\/title>', $item[0], $title);
									if (!empty($title[2]))
										$title = $title[2];

									$channel = array();
									mb_eregi('channel="(.*?)"', $item[0], $channel);
									if (!empty($channel[1]))
										$channel = $channel[1];

									$start = array();
									mb_eregi('start="(.*?)"', $item[0], $start);
									if (!empty($start[1]))
									{
										$start = $start[1];
										$start = sscanf($start, "%04s%02s%02s%02s%02s%02s");
										$start = sprintf("%04s-%02s-%02s %02s:%02s:%02s", $start[0], $start[1], $start[2], $start[3], $start[4], $start[5]);
									}

									//СОХРАНЕНИЕ ИНФОРМАЦИИ О ФИЛЬМЕ
									if (!empty($start) && !empty($channels[$channel]['id']) && !empty($categories[$category]) && !empty($title))
									{
										if (!empty($tvUnique[$start .'_'.$channels[$channel]['id']]))
										{
											$data = array(
												'Tvfilm' => array(
													'start' => $start,
													'tvchannel_id' => $channels[$channel]['id'],
													'tvcategory_id' => $categories[$category],
													'name' => $title,
												)
											);
											$this->Tvfilm->create();
											$this->Tvfilm->save($data);
											$filmsAdd++;
										}
										$tvUnique[$start .'_'.$channels[$channel]['id']] = $channels[$channel]['id'];
									}
								break;
							}
							$item = array();
						}
if ($i++ > 1)
	break;
					}
					fclose($f);
					//unlink($xml);
				}
*/
/*
echo'<pre>';
print_r($data);
echo'</pre>';
//*/
    		break;
    		default:
    		//УДАЛЯЕМ ПЕРЕДАЧИ ПРОШЕДШИЕ БОЛЕЕ ХХХ ДНЕЙ НАЗАД
    			$this->Tvfilm->deleteAll(array('Tvfilm.start < ' => date('Y-m-d H:i:s', time() - 3600 * 24 * 3)));
    	}

		$this->set('categoriesAdd', $categoriesAdd);
		$this->set('channelsAdd', $channelsAdd);
		$this->set('filmsAdd', $filmsAdd);

		$this->paginate['Tvfilm']['fields'] = array('Tvfilm.name', 'Tvfilm.start', 'Tvchannel.name', 'Tvcategory.name');
        $this->paginate['Tvfilm']['order'] = 'Tvfilm.start asc';
        if (!empty($this->data))
        {
			if (!empty($this->data['Tvfilm']['from']))
        		$this->paginate['Tvfilm']['conditions'][] = array('Tvfilm.start >= ' => $this->data['Tvfilm']['from']);
			if (!empty($this->data['Tvfilm']['to']))
        		$this->paginate['Tvfilm']['conditions'][] = array('Tvfilm.start <= ' => $this->data['Tvfilm']['to']);
        }
        $this->Tvfilm->recursive = 0;
		$this->Tvfilm->contain(array('Tvchannel', 'Tvcategory'));

        $this->set('films', $this->paginate('Tvfilm'));
    }

    function admin_geo($action = '')
    {
		Configure::write('debug', 0);

    	App::import('Model', 'Geocity');
    	$Geocity = new Geocity();

    	App::import('Model', 'Georegion');
    	$Georegion = new Georegion();

    	App::import('Model', 'Geocounty');
    	$Geocounty = new Geocounty();

    	App::import('Model', 'Geoip');
    	$Geoip = new Geoip();

		$add = 0;
    	switch ($action)
    	{
    		case "import":
    			$url = "http://ipgeobase.ru/files/db/Map_db/block_coord.zip";
   				$zip = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/geo.zip';
   				$xml = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/geo.db';

//СКАЧИВАНИЕ С ИСТОЧНИКА
   				$bufLen = 4096 * 20;
    			if ((!file_exists($zip)) && ($r = fopen($url, 'r')))
    			{
    				if ($w = fopen($zip, 'a+'))
    				{
	    				while (!feof($r))
	    				{
	    					$buf = fread($r, $bufLen);
	    					fwrite($w, $buf);
	    				}
	    				fclose($w);
	    				fclose($r);
    				}
    			}

//РАЗВОРАЧИВАНИЕ АРХИВА
				if (file_exists($zip) && ($z = zip_open($zip)))
				{
				    while ($zip_entry = zip_read($z))
				    {
				        if (zip_entry_open($z, $zip_entry, "r"))
				        {
		    				$w = fopen($xml, 'w');
				        	while ($buf = zip_entry_read($zip_entry, $bufLen))
				        	{
				        		fwrite($w, $buf);
				        	}
				        	fclose($w);
				            zip_entry_close($zip_entry);
				        }
				    }
				    zip_close($z);
				}

//ПАРСИНГ
				$file = $xml;
				$eoCell = chr(9);	//РАЗДЕЛИТЕЛЬ КОЛОНОК
				$eoLn = chr(10);	//РАЗДЕЛИТЕЛЬ СТРОК
				$tail = '';			//НЕРАЗОБРАННЫЙ ХВОСТ
				$colsNum = 8;		//В РАЗБИРАЕМОМ ФАЙЛЕ 8 КОЛОНОК

				$cities = array();
				$all = array();
				$all = $Geocity->findAll();
				foreach ($all as $a)
				{
					$cities[$a['Geocity']['name']] = $a;
				}

				$regions = array();
				$all = array();
				$all = $Georegion->findAll();
				foreach ($all as $a)
				{
					$regions[$a['Georegion']['name']] = $a;
				}

				$counties = array(); //КЭШИРУЕМ ОКРУГА В МАССИВ
				$all = array();
				$all = $Geocounty->findAll();
				foreach ($all as $a)
				{
					$counties[$a['Geocounty']['name']] = $a;
				}

				if ($fp = fopen($file, "r"))
				{
					$sql = 'TRUNCATE TABLE `geoips`';
					$Geoip->query($sql);
					set_time_limit(600000);

					while ($data = fread($fp, 4096))
					{
						$data = $tail . $data;
						$lines = explode($eoLn, $data);
						$tail = '';
						for ($i = 0; $i < count($lines); $i++)
						{
							$cells = explode($eoCell, $lines[$i]);
							if ($i == count($lines) - 1) //ЕСЛИ ЭТО ПОСЛЕДНЯЯ СТРОКА
							{
								$tail = $lines[$i]; //СОХРАНЯЕМ ЕЕ В ХВОСТ
								break;
							}
							if (count($cells) < $colsNum) //ЕСЛИ В СТРОКЕ МЕНЬШЕ КОЛОНОК, ЧЕМ ДОЛЖНО БЫТЬ ПО ФОРМАТУ
							{
								continue;
							}
							$ip1 = $cells[1];
							$ip2 = $cells[2];
							$city = iconv('windows-1251', 'utf-8', $cells[3]);
							$region = iconv('windows-1251', 'utf-8', $cells[4]);
							$county = iconv('windows-1251', 'utf-8', $cells[5]);

							if (!empty($counties[$county]))
							{
								$countyInfo = $counties[$county];
							}
							else
							{
								$countyInfo = array('Geocounty' => array(
												'name' => $county
											)
										);
								$Geocounty->create();
								if ($Geocounty->save($countyInfo))
								{
									$id = $Geocounty->getLastInsertId();
									$countyInfo['Geocounty']['id'] = $id;
								}
								else
								{
									$countyInfo = $Geocounty->find(array('name' => $county));
								}

								if (empty($countyInfo['Geocounty']['id']))
								{
									die('Fatal Error. County dictionary cannot generated');
								}
								$counties[$county] = $countyInfo;
							}

							if (!empty($regions[$region]))
							{
								$regionInfo = $regions[$region];
							}
							else
							{
								$regionInfo = array('Georegion' => array(
												'name' => $region,
												'county_id' => $countyInfo['Geocounty']['id'],
											)
										);
								$Georegion->create();
								if ($Georegion->save($regionInfo))
								{
									$id = $Georegion->getLastInsertId();
									$regionInfo['Georegion']['id'] = $id;
								}
								else
								{
									$regionInfo = $Georegion->find(array('name' => $region));
								}

								if (empty($regionInfo['Georegion']['id']))
								{
									die('Fatal Error. Region dictionary cannot generated');
								}
								$regions[$region] = $regionInfo;
							}

							if (!empty($cities[$city]))
							{
								$cityInfo = $cities[$city];
							}
							else
							{
								$cityInfo = array('Geocity' => array(
												'name' => $city,
												'region_id' => $regionInfo['Georegion']['id'],
												'county_id' => $countyInfo['Geocounty']['id'],
											)
										);
								$Geocity->create();
								if ($Geocity->save($cityInfo))
								{
									$id = $Geocity->getLastInsertId();
									$cityInfo['Geocity']['id'] = $id;
								}
								else
								{
									$cityInfo = $Geocity->find(array('name' => $city));
								}

								if (empty($cityInfo['Geocity']['id']))
								{
									die('Fatal Error. Cities dictionary cannot generated');
								}
								$cities[$city] = $cityInfo;
							}

							$add++;
							$ipInfo = array('Geoip' => array(
									'ip1' => $ip1,
									'ip2' => $ip2,
									'city_id' => $cityInfo['Geocity']['id'],
									'region_id' => $regionInfo['Georegion']['id'],
									'county_id' => $countyInfo['Geocounty']['id'],
								)
							);
							$Geoip->create();
							$Geoip->save($ipInfo);
						}
//if ($add > 150000) break;
					}
					fclose($fp);
					//unlink($xml);
					//unlink($zip);
				}
				$this->set('add', $add);
			break; //ENDOF IMPORT
    	}
    }


    function admin_transtats()
    {
        $this->paginate['Transtat']['fields'] = array('created', 'search');
        $this->paginate['Transtat']['order'] = 'created desc';
        $this->set('search', $this->paginate('Transtat'));
    }

    function admin_search_logs($from = null, $to = null)
    {
        if (!empty($this->data['SearchLog']))
        {
            $from = $this->data['SearchLog']['from'];
            $to = $this->data['SearchLog']['to'];
            $this->redirect('/admin/utils/search_logs/' . $from . '/' . $to);
        }
/*
        $this->paginate['SearchLog']['fields'] = array('COUNT(keyword) AS count', 'keyword');
        $this->paginate['SearchLog']['group'] = 'keyword';
        $this->paginate['SearchLog']['order'] = 'count desc, created desc';
*/
        $this->paginate['SearchLog']['fields'] = array('hits AS count', 'keyword');
        $this->paginate['SearchLog']['order'] = 'count desc, created desc';
        $this->paginate['SearchLog']['limit'] = 100;
/*
//ДЛЯ СТАРОГО ВАРИАНТА СТАТИСТИКИ ПОИСКА (БЕЗ СЧЕТЧИКОВ)
        if (!empty($from))
            $this->paginate['SearchLog']['conditions'][] = array('created >=' => $from);
        if (!empty($to))
            $this->paginate['SearchLog']['conditions'][] = array('created <=' => $to);
*/
        if (!empty($from))
            $this->paginate['SearchLog']['conditions'][] = array('updated >=' => $from);
        if (!empty($to))
            $this->paginate['SearchLog']['conditions'][] = array('updated <=' => $to);

        $this->set('keywords', $this->paginate('SearchLog'));
    }

    function admin_lost_files()
    {
    	if (!empty($this->data))
    	{
/*
    		echo'<pre>';
    		print_r($this->data);
    		echo'</pre>';
    		exit;
*/
    		$this->FilmFile->updateAll(array('FilmFile.is_lost' => 0), array('FilmFile.id' => $this->data));
    	}
    	$sql = '
    		select FilmFile.file_name, FilmFile.id as filmfile_id, Film.id, Film.dir, Film.title from film_files as FilmFile
    		inner join film_variants as FilmVariant on (FilmVariant.id = FilmFile.film_variant_id)
    		inner join films as Film on (Film.id = FilmVariant.film_id) where FilmFile.is_lost = 1 limit 100;
    	';
        $this->set('films', $this->FilmFile->query($sql));
/*
        $this->paginate['FilmFile']['fields'] = array('FilmVariant.id', 'FilmFile.id', 'FilmFile.file_name');
        $this->paginate['FilmFile']['conditions'] = array('FilmFile.is_lost' => '1');
        $this->paginate['FilmFile']['limit'] = 100;
        $this->paginate['FilmFile']['contain'] = array('FilmVariant' => array('Film'));
        $this->FilmFile->recursive = 2;
        $this->FilmFile->FilmVariant->belongsTo = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ));
		//$this->FilmFile->contain('FilmVariant' => array('Film')));// => array('conditions' => array('Film.id' => 'FilmVariant.film_id', 'FilmVariant.id' => 12103)))));
        if (isset($this->passedArgs["sort"]))
        {
	        $order = $this->passedArgs["sort"] . ' ' . $this->passedArgs["direction"];
        }
        else
        {
        	$order = 'FilmFile.id asc';
        }
        $this->paginate['FilmFile']['order'] = $order;
        $this->set('films', $this->FilmFile->find('all', $this->paginate));
*/
    }

	function film_clicks($id = 0)
	{
		$this->layout = 'ajax';
		if (!empty($id) && !empty($this->authUser['userid']))
		{
			$data = $this->FilmClick->find(
				array(
					'FilmClick.film_id' => $id,
					'FilmClick.user_id' => $this->authUser['userid'],
					'FilmClick.created > ' => date('Y-m-d H:i:s', time() - 60*60*24)
				),
				null);
			if (empty($data))
			{
				$ip = $_SERVER['REMOTE_ADDR'];
				$zones=Configure::read('Catalog.allowedIPs');
		    	    $zone = checkAllowedMasks($zones, $ip, 1);
		    	  //  $zone1=$zone;
				//$zone = $zones[$zone]['zone'];

				$data = array(
					'FilmClick' => array(
						'created'	=> date('Y-m-d H:i:s'),
						'user_id'	=> $this->authUser['userid'],
						'film_id'	=> $id,
						//'info'		=> 'ip: ' . $ip . '; zone: ' . $zone,
						'ip'		=> $ip,
						'zone'		=> $zone,
					)
				);
				$this->FilmClick->create();
			}
			$this->FilmClick->save($data);
		}
	}

    function admin_film_clicks($from = null, $to = null)
    {
        if (!empty($this->data['FilmClick']))
        {
            $from = $this->data['FilmClick']['from'];
            $to = $this->data['FilmClick']['to'];
            $this->redirect('/admin/utils/film_clicks/' . $from . '/' . $to);
        }
/*
		$this->Film->unbindModel(array('hasOne' => array('MediaRating'), 'belongsTo' =>array('FilmType')), false);
		//$this->Film->bindModel(array('hasMany' => array('FilmClick')), false);
		//$this->Film->bind('FilmClick', array('foreignKey' => 'film_id'));
		//$this->Film->bind('FilmClick', array('foreignKey' => 'id' , 'conditions' => '`Film`.`id` = `FilmClick`.`film_id`'), false);
        $this->Film->recursive = 0;
        $this->Film->contain(array('FilmClick'));
*/
        $this->paginate['FilmClick']['fields'] = array('count(Film.id) AS `cnt`', 'Film.id', 'Film.title', 'FilmClick.created');
        $this->paginate['FilmClick']['group'] = 'Film.id';

        if (isset($this->passedArgs["sort"]) && isset($this->passedArgs["direction"]))
        {
	        $order = $this->passedArgs["sort"] . ' ' . $this->passedArgs["direction"];
	        unset($this->passedArgs["sort"]);
        }
        else
        {
        	$order = '`cnt` desc';
        }
        $this->paginate['FilmClick']['order'] = $order;

        if (!empty($from))
            $this->paginate['FilmClick']['conditions'][] = array('FilmClick.created >=' => $from);
        if (!empty($to))
            $this->paginate['FilmClick']['conditions'][] = array('FilmClick.created <=' => $to);

        $this->set('films', $this->paginate('FilmClick'));
    }

    function admin_redirect_counts($from = null, $to = null)
    {
    	if (!empty($this->data['RedirectCount']))
        {
            $from = $this->data['RedirectCount']['from'];
            $to = $this->data['RedirectCount']['to'];
            $this->redirect('/admin/utils/redirect_counts/' . $from . '/' . $to);
        }

        $this->paginate['RedirectCount']['fields'] = array('COUNT(redirect_id) AS count', 'Redirect.url','concat(referer,RedirectCount.link) as referer');
        $this->paginate['RedirectCount']['group'] = 'redirect_id,referer';
        $this->paginate['RedirectCount']['order'] = 'count desc, date desc';
        $this->paginate['RedirectCount']['limit'] = 500;
        if (!empty($from))
        	{
        		$this->paginate['RedirectCount']['conditions'][] = array('date >=' => $from);
            $this->data['RedirectCount']['from']=$from;
        	}
        if (!empty($to))
        	{
        		$this->paginate['RedirectCount']['conditions'][] = array('date <=' => $to);
            $this->data['RedirectCount']['to']=$to;
        	}
        	$this->set('RedirectCount', $this->data['RedirectCount']);
        	$this->set('keywords', $this->paginate('RedirectCount'));
    }

    /**
     * Обновляет данные с imdb.com в фильме(рейтинг и кол-во голосов)
     *
     */
    function update_imdb()
    {
        ini_set('memory_limit', '1G');
        set_time_limit(50000000000);

        App::import('Vendor', 'IMDB_Parser', array('file' => 'class.imdb_parser.php'));
        $this->Film->contain();
        $count = $this->Film->find('count');

        //$count = 50;
        for ($page = 1; $page <= ceil($count / 50); $page++)
        {
            $this->paginate['Film']['page'] = $page;
            $this->paginate['Film']['limit'] = 50;
            $this->paginate['Film']['conditions'] = array('or' => array('imdb_date < ' => date('Y-m-d H:i:s', time() - 30*24*60*60),
                                                                        'imdb_date' => null));
            $this->paginate['Film']['contain'] = array();
            $films = $this->paginate('Film');

            foreach ($films as $film)
            {
                $imdb_website = file_get_contents('http://imdb.com/title/' . $film['Film']['imdb_id']);
                $parser = new IMDB_Parser();
                $rating = $parser->getMovieStars($imdb_website);
                $votes = $parser->getMovieVotes($imdb_website);
                $film['Film']['imdb_rating'] = $rating;
                $film['Film']['imdb_votes'] = str_replace(',', '', $votes);
                $film['Film']['imdb_date'] = date('Y-m-d H:i:s');
                $this->Film->save($film);
            	Cache::delete('Catalog.film_view_' . $film["Film"]["id"], 'media');
            }
        }
    }


    function set_input_server()
    {
    	$type=@(int)$_REQUEST['type'];
    	$dir=(isset($_REQUEST['dir']))?$_REQUEST['dir']:NULL;
    	$ip=(isset($_REQUEST['ip']))?$_REQUEST['ip']:$_SERVER['REMOTE_ADDR'];
    	return FILM::set_input_server($dir);

		return FALSE;
    }

	function check_consistent()
	{
		$this->layout='ajax';
		if ($data = Cache::read('Utils.checkconsistent', array('config' => 'default', 'duration' => '+30 minutes')))
        {
            echo $data;
        }
		else
		{
		$time_start = getmicrotime();
		ini_set('memory_limit', '1G');
        set_time_limit(50000000000);

       	$this->Film->contain();
        	$this->Film->recursive = 2;
//        	$this->Film->contain(array('FilmType',
//                                     'Genre',
//                                     'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
//                                     'Country',
//                                     'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
//                                     'MediaRating',
//                                     'FilmComment' => array('order' => 'FilmComment.created ASC',
//                                                            'conditions' => array('FilmComment.hidden' => 0))));


       	$count = $this->Film->find('count',array('conditions' => array('active'=>'1')));
        $FilmCountPlus=0;
        $FilmCountMinus=0;
        $FileCountPlus=0;
        $FileCountMinus=0;

		$ftp_server1='video3.videoxq.com';
		$ftp_server2='video4.videoxq.com';
		$ftp_user_name='anonymous';
		$ftp_user_pass='anon@ngs.ru';

		// установка соединения
		$ftp1 = ftp_connect($ftp_server1);
		$login_result1 = ftp_login($ftp1, $ftp_user_name, $ftp_user_pass);
		// проверка соединения
		if ((!$ftp1) || (!$login_result1)) {
		        echo "Не удалось установить соединение с FTP сервером!";
		        echo "Попытка подключения к серверу $ftp_server1 под именем $ftp_user_name!";
		        //Cache:delete('Utils.checkconsistent');
		        die();
		}
			// установка соединения
		$ftp2 = ftp_connect($ftp_server2);
		$login_result2 = ftp_login($ftp2, $ftp_user_name, $ftp_user_pass);
		// проверка соединения
		if ((!$ftp2) || (!$login_result2)) {
		        echo "Не удалось установить соединение с FTP сервером!";
		        echo "Попытка подключения к серверу $ftp_server2 под именем $ftp_user_name!";
		        //Cache:delete('Utils.checkconsistent');
		        die();
		}
        for ($page = 1; $page <=ceil($count / 50); $page++)
        {
            $this->paginate['Film']['page'] = $page;
            $this->paginate['Film']['limit'] = 50;
            $this->paginate['Film']['conditions'] = array('active'=>'1');
            $this->paginate['Film']['contain'] = array(
									'FilmVariant' => array('FilmFile' => array('order' => 'file_name'))
            										  );

            $films = $this->paginate('Film');
            foreach ($films as $film)
            {
            	$filmabsent=FALSE;
            	foreach ($film['FilmVariant'] as $variant)
            	{
            		//pr($variant);
            		foreach ($variant['FilmFile'] as $file)
            		{
            			//pr($file);
            			$dir= $film['Film']['dir']."/".$file['file_name'];
            			$link=FILM::set_input_server($dir);
            			//$link="ftp://video3.videoxq.com/1/1.avi";
            			//pr(stat($link));

            			//if(!stat($link))
            			//$Headers = get_headers($link);
            			//die();
            			//if(!fopen($link,'r'))
            			$letter=strtolower(substr($dir,0,1));
						if(( $letter >= '0' and $letter <= '9')||$letter=='0')$letter='0-999';
            			if(strpos($link,$ftp_server1))$res = ftp_size($ftp1, $letter."/".$dir);
            			elseif(strpos($link,$ftp_server2))$res = ftp_size($ftp2, $letter."/".$dir);
            			if ($res == -1)
            			{
            				$data1="<br/><a href='http://videoxq.com/media/view/{$film['Film']['id']}' target=_blank>*</a>".$link."<br />";
            				$data.=$data1;
            				echo $data1;
            				//echo $dir."<br />";
            				$filmabsent=TRUE;
            				$FileCountMinus++;
            			}
            			else
            			{
            				//echo $link."<br />";
            				$FileCountPlus++;
            			}
            		}
            	}
            	if($filmabsent)$FilmCountMinus++;else $FilmCountPlus++;
            }
			echo "-";
			$data.="-";
            flush ();
        }
        $data1="<hr >Отсуствует {$FileCountMinus} файлов из {$FilmCountMinus} фильмов";
        $data.=$data1;
		echo $data1;
		$data1="<hr >Присутствует {$FileCountPlus} файлов из {$FilmCountPlus} фильмов";
		$data.=$data1;
		echo $data1;
		$time_end = getmicrotime();
		$time = $time_end - $time_start;
		$data1="<hr>выполненно за {$time} времени <hr>";
        $data.=$data1;
		echo $data1;
		// закрытие соединения
		ftp_close($ftp1);
		ftp_close($ftp2);
        Cache::write('Utils.checkconsistent', $data, array('config' => 'default', 'duration' => '+30 minutes'));
		}

	}

	function statistic()
	{
		include Configure::read('App.siteUrl')."/media/statistic";
		include Configure::read('App.siteUrl')."/FILMComments/statistic";
		include Configure::read('App.siteUrl')."/blogs/statistic";
		include Configure::read('App.siteUrl')."/posts/statistic";

	}
	function arrayDiff()
	{
	    function getinfo($fname)
	    {
		$lines=file($fname);
    		$out=array();
		foreach($lines as $line)
		{
	    	    if(0)
	    	    {
	    		$value=ereg_replace("[' ']{2,}"," ",$line);
    			$value=trim($value);
    			$pieces = explode(" ", $value);
			$out[$pieces[10]]=$pieces[6];
		    }
		//echo $line;
    		//$line=trim($line);
    		//$pattern="";
    		$pattern="\s*[0-9]+\s+[0-9]+\s+[^\s]+\s+[^\s]+\s+[^\s]+\s+[^\s]+\s+([^\s]+)";//find size
    		$pattern.="+\s+[^\s]+\s+[^\s]+\s+[^\s]+\s+([.]\/.*)";
		preg_match("/^".$pattern."/",$line,$pieces);
		$out[$pieces[2]]=$pieces[1];
		//pr($pieces);
		//die();
		}
		unset($lines);
		return $out;
	    }

	$a=getinfo('/home/hawk/1.txt');
	$b=getinfo('/home/hawk/2.txt');


	 //to delete
	 $pushdiff=set::pushDiff($b,$a);
	 //$pushdiff=array_merge_recursive($b,$a);
	 echo "to delete";

	 // pr(set::diff($pushdiff,$a)); to delete
	 pr(array_diff_assoc($pushdiff,$a));
	 $pushdiff=set::pushDiff($a,$b);
	 //$pushdiff=array_merge($a,$b);
	 echo "to rename";
	 pr(array_diff_assoc($pushdiff,$b));
	 //pr(set::diff($pushdiff,$b)); to delete


	}
}
?>