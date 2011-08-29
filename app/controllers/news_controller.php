<?php
class NewsController extends AppController {

    var $name = 'News';
    var $helpers = array('Html', 'Form');

    var $uses = array('News', 'Direction');
    var $components = array('BlockStats');

	/**
	 * модель таблицы News
	 *
	 * @var AppModel
	 */
    var $News;

    /**
     * вывод списка новостей
     * если указан $dir_id, то выводим список новостей категории
     *
     * @param integer $dir_id - идентификатор направления (категории)
     */
    function index($dir_id = 0)
    {
		$lang = $this->Session->read("language");
		if ($lang == _ENG_)
			$this->redirect('/media');
    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);

    	$conditions = array('News.hidden' => 0);
    	if (!empty($dir_id))
    	{
    		$conditions['News.direction_id'] = $dir_id;
    	}
    	$lst = $this->News->findAll($conditions, null, 'News.created DESC');
    	$this->set('lst', $lst);
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid New.', true));
            $this->redirect('/');
        }

		if (is_numeric($id))
		{
			$info = $this->News->findById($id);
		}

		if (!empty($info))// && !$info['News']['hidden'])
		{
			if (!empty($info['News']['poll_id']))
			{
				$voteData = $this->BlockStats->getPoll($info['News']['poll_id']);
				$this->set('block_poll', $voteData);
			}
            $this->set('info', $info);

	    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
	    	$this->set('dirs', $dirs);

        	$dat = date('Y-m-d', strtotime($info['News']['created']));
	        $this->set('dat', $dat);

	    	$isWS = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER["REMOTE_ADDR"], 1);
	    	if ($isWS == 'OPERA-MINI') $isWS = false;
	        if ($isWS)
	        {
	        	$flowServerAddr = '92.63.196.52';
	        	$flowServerAddrPort = '92.63.196.52:82';
	        }
	        else
	        {
	        	$flowServerAddr = '87.226.225.78:83';
	        	$flowServerAddrPort = '87.226.225.78:83';
	        }
	        $this->set('flowServerAddr', $flowServerAddr);
	        $this->set('flowServerAddrPort', $flowServerAddrPort);
			$ftpInfo = Cache::read('News.ftpInfo.' . $info['News']['id'], 'rocket');
			if (empty($ftpInfo))
			{
				$ftpInfo = array();
			}
			if (empty($ftpInfo[$dat]))
			{
				$ftp_id = ftp_connect($flowServerAddr, 0, 5);
		        if ($ftp_id)
		        {
			        $login = ftp_login($ftp_id, 'mp4', '9043uj53456t');
			        if ($login)
			        {
			        	$dir = $dat;
			        	if (!empty($info['News']['ftpdir']))
			        	{
			        		$dir = $info['News']['ftpdir'];
			        	}
			        	$res = ftp_pasv($ftp_id, true);
				        $lst = ftp_nlist($ftp_id, $dir);
				        if (!empty($lst))
				        {
				        	for ($match = 1; $match < 20; $match++)//БЕРЕМ КОЛ-ВО МАТЧЕЙ С ЗАПАСОМ
				        	{
				        		$matchContent = ftp_nlist($ftp_id, $dir . '/' . $match);
				        		if (!empty($matchContent))
				        		{
				        			$infoTxt = @file_get_contents('http://' . $flowServerAddr . '/' . $dir . '/' . $match . '/info.txt');
				        			$ftpInfo[$dir][$match] = array(
				        				'video' => ftp_nlist($ftp_id, $dir . '/' . $match . '/video'),
				        				'foto'	=> ftp_nlist($ftp_id, $dir . '/' . $match . '/foto'),
				        				'info'	=> $infoTxt,
				        			);
				        		}
				        	}
				        	$video = ftp_nlist($ftp_id, $dir . '/other/video');
				        	$foto = ftp_nlist($ftp_id, $dir . '/other/foto');
				        	$ftpInfo[$dir]['video'] = $video;//ПРОЧЕЕ ВИДЕО
				        	$ftpInfo[$dir]['foto'] = $foto;//ПОРЧЕЕ ФОТО
		        			$infoTxt = @file_get_contents('http://' . $flowServerAddr . '/' . $dir . '/other/info.txt');
				        	$ftpInfo[$dir]['info'] = $infoTxt;
				        	Cache::write('News.ftpInfo.' . $info['News']['id'], $ftpInfo, 'rocket');
				        }
			        }
			        ftp_close($ftp_id);
		        }
			}
//pr($ftpInfo);
	        $this->set('ftpInfo', $ftpInfo);
		}
		else
		{
			$this->redirect('/news');
		}
    }

    /**
     * импорт новостей партнеров
     *
     */
    public function frompartners()
    {
    	$pDirection = Configure::read('News.partnerDirectionId');
    	$feeds  = array(
    		array(
    			'partner'	=> 'ООО "Мастер Тэйп"',
    			'url'		=> 'http://master-tape.ru',
    			'prefix'	=> 'mastertape',
    			'rss'		=> 'http://master-tape.ru/component/option,com_rss/feed,RSS2.0/no_html,1',
    			'encoding'	=> 'windows-1251',
    		),
    	);

			//ПАРСИНГ
			global $tag;
			global $item;
			global $items;
			global $itemStart;
			global $encoding;

			function mastertape_start_el($parser, $name, $attrs)
			{
			    global $tag;
				global $item;
				global $itemStart;
			    $tag = strtoupper($name);

			    switch ($tag)
			    {
			    	case "ITEM":
			    		$itemStart = true;
		    			$item = array();
			    	break;
			    }
			}

			function mastertape_data($parser, $data)
			{
			    global $tag;
			    global $item;
			    global $itemStart;
			    global $encoding;

				if (!trim($data))
					return;

				if (!$itemStart)
					return;

				$data = iconv($encoding, 'utf-8', $data);
				switch ($tag)
				{
			    	case "TITLE":
						$item['title'] = $data;
						if (mb_strlen($data) >= 150)
						{
							mb_substr($data, 0, 145);
							/*
							$words = mb_split('[\s]{1,}', $data);
							$str = '';
							foreach($words as $w)
							{
								if (mb_strlen($str . $w . ' ') > 145)
								{
									break;
								}
								$str .= $w . ' ';
							}
							*/
							$str .= '...';
							$item['title'] = $str;
						}
					break;

			    	case "LINK":
						$item['link'] = $data;
			    	break;

			    	case "DESCRIPTION":
						$item['stxt'] = '';
						$item['txt'] = '';
						if (mb_strlen($data) >= 255)
						{
							$item['txt'] = $data;
						}
						else
						{
							$item['stxt'] = $data;
						}
			    	break;

			    	case "LINK":
						$item['link'] = $data;
			    	break;

			    	case "PUBDATE":
						$item['created'] = date('Y-m-d H:i:s', strtotime($data));
						$item['modified'] = $item['created'];
			    	break;
				}
			}

			function mastertape_end_el($parser, $name)
			{
			    global $tag;
			    global $item;
			    global $items;
			    global $itemStart;
			    $tag = strtoupper($name);

			    switch ($tag)
			    {
			    	case "ITEM":
			    		$itemStart = false;
		    			$items[] = $item;
		    			$tag = '';
			    	break;
			    }
			}

	    	foreach($feeds as $feed)
	    	{
	    		if (!empty($feed['rss']))
	    		{
					$xml = file_get_contents($feed['rss']);
					if (!empty($xml))
					{
						$items = array();
						$encoding = $feed['encoding'];
						$xml_parser = xml_parser_create();
						xml_set_element_handler($xml_parser, $feed['prefix'] . "_start_el", $feed['prefix'] . "_end_el");
						xml_set_character_data_handler($xml_parser, $feed['prefix'] . "_data");

						if (!xml_parse($xml_parser, $xml))
						{
							echo xml_error_string(xml_get_error_code($xml_parser));
                    		echo xml_get_current_line_number($xml_parser);
						}
						xml_parser_free($xml_parser);

						if (!empty($items))
						{
//СОХРАНЕНИЕ В БД
							foreach ($items as $item)
							{
								$dub = $this->News->find(array('News.created' => $item['created']));
								if (empty($dub))
								{
									if (empty($item['txt']) && empty($item['stxt']))
									{
										$item['stxt'] = $item['title'];
										$item['txt'] = '';
									}

									if(!empty($item['link']))
									{
										$a = '<br /><a href="' . $item['link'] . '">оригинал на сайте ' . $feed['partner'] . '</a>';
										unset($item['link']);
										if (!empty($item['txt']))
										{
											$item['txt'] .= $a;
										}
										if (!empty($item['stxt']))
										{
											$item['stxt'] .= $a;
										}
									}
									$item['img'] = '';
									$item['hidden'] = 0;
									$item['filesinfo'] = '';
									$item['matchesinfo'] = '';
									$item['direction_id'] = Configure::read('News.partnerDirectionId');
									$item['ftpdir'] = '';
									$item['poll_id'] = 0;
									$data = array('News' => $item);
/*
echo '<pre>';
echo $xml;
pr($data);
echo '</pre>';
//exit;
//*/
									$this->News->create();
									$this->News->save($data);
								}
							}
						}
					}
			    }
			    else
			    {
			    	//ПАРСИМ СО СТРАНИЦЫ
			    }
	    	}

    }

    /**
     * администрирование списка новостей
     * если указан $dir_id, то выводим список новостей категорим
     *
     * @param integer $dir_id - идентификатор направления (категории)
     */
    function admin_index($dir_id = 0) {

        $dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);
    	$paginate = array(
    		'conditions' => array('hidden' => 0),
    		'order' => 'created DESC'
    		);
    	if (!empty($dir_id))
    	{
    		$paginate['conditions']['direction_id'] = $dir_id;
    	}
        $this->set('lst', $this->News->find('all', $paginate));
    }

	function unlinkTempFiles($dir, $userid)
	{
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		            if (ereg('^temp_' . $userid, $file))
		            {
		            	unlink($dir . '/' . $file);
		            }
		        }
		        closedir($dh);
		    }
		}
	}

	function findByPreview($dir, $name)
	{
		$result = '';
		if (is_dir($dir)) {
			$info = pathinfo($name);
			$name = ereg_replace('.' . $info['extension'] . '$', '', $name);
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		            if (ereg('^' . $name, $file))
		            {
		            	$result = $file;
		            	break;
		            }
		        }
		        closedir($dh);
		    }
		}
		return $result;
	}

    function admin_edit($id = null) {
    	$uploadDir = Configure::read('App.webroot') . '/files/news';
    	$this->set('uploadDir', $uploadDir);

    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);

        if (!empty($this->data)) {
        	if (!empty($this->data['picture']))
        	{
        		//ПЕРЕИМЕНОВЫВАЕМ ВРЕМЕННЫЕ ИМЕНА
				$dir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
				$picture = $dir . '/' . $this->findByPreview($dir, $this->data['picture']);
				$info = pathinfo($picture);
				$newPicture = $dir . '/' . $this->authUser['userid'] . '_' . time() . '.' . $info['extension'];

				$preview = $dir . '/small/' . $this->data['picture'];
				$info = pathinfo($preview);
				$newPreview = $dir . '/small/' . $this->authUser['userid'] . '_' . time() . '.' . $info['extension'];

				if (file_exists($preview))
				{
					rename($preview, $newPreview);
					rename($picture, $newPicture);
        			$this->data['News']['img'] = basename($newPreview);
				}
        	}
        	else
        	{
        		unset($this->data['News']['img']);
        	}
        	$this->data['News']['modified'] = date('Y-m-d H:i:s');
            if ($this->News->save($this->data)) {

            	if ($this->data['News']['id'])
            	{
            		cache::delete('News.ftpInfo.' . $this->data['News']['id'], 'rocket');
            	}

                $this->Session->setFlash(__('The New has been saved', true));
                $this->redirect(array('action'=>'index'));
            }
        }

        if (!empty($id)) {
            $info = $this->News->read(null, $id);
            $this->set('info', $info);
        }
        $this->set('authUser', $this->authUser);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for News', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->News->del($id)) {
            $this->Session->setFlash(__('News deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>