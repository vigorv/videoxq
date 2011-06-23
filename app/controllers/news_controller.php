<?php
class NewsController extends AppController {

    var $name = 'News';
    var $helpers = array('Html', 'Form');

    var $uses = array('News', 'Direction');

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
            $this->set('info', $info);

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