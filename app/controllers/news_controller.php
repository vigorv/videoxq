<?php
class NewsController extends AppController {

    var $name = 'News';
    var $helpers = array('Html', 'Form');

    var $uses = array('News');

	/**
	 * модель таблицы News
	 *
	 * @var AppModel
	 */
    var $News;

    function index()
    {
    	$lst = $this->News->findAll(array('News.hidden' => 0), null, 'News.created DESC');
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

		if (!empty($info) && !$info['News']['hidden'])
		{
            $this->set('info', $info);

        	$dat = date('Y-m-d', strtotime($info['News']['created']));
	        $this->set('dat', $dat);
	        $flowServerAddr = '92.63.196.52';
	        $this->set('flowServerAddr', $flowServerAddr);
	        $flowServerAddrPort = '92.63.196.52:80';
	        $this->set('flowServerAddrPort', $flowServerAddrPort);
//			$ftpInfo = Cache::read('News.ftpInfo', 'searchres');
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
			        	$res = ftp_pasv($ftp_id, true);
				        $lst = ftp_nlist($ftp_id, $dat);
				        if (!empty($lst))
				        {
				        	for ($match = 1; $match < 20; $match++)//БЕРЕМ КОЛ-ВО МАТЧЕЙ С ЗАПАСОМ
				        	{
				        		$matchContent = ftp_nlist($ftp_id, $dat . '/' . $match);
				        		if (!empty($matchContent))
				        		{
				        			$ftpInfo[$dat][$match] = array(
				        				'video' => ftp_nlist($ftp_id, $dat . '/' . $match . '/video'),
				        				'foto'	=> ftp_nlist($ftp_id, $dat . '/' . $match . '/foto'),
				        			);
				        		}
				        	}
				        	$video = ftp_nlist($ftp_id, $dat . '/other/video');
				        	$foto = ftp_nlist($ftp_id, $dat . '/other/foto');
				        	$ftpInfo[$dat]['video'] = $video;//ПРОЧЕЕ ВИДЕО
				        	$ftpInfo[$dat]['foto'] = $foto;//ПОРЧЕЕ ФОТО
				        	Cache::write('News.ftpInfo', $ftpInfo, 'searchres');
				        }
			        }
			        ftp_close($ftp_id);
		        }
			}
	        $this->set('ftpInfo', $ftpInfo);
		}
    }

    function admin_index() {
        $this->News->recursive = 0;
        $this->set('lst', $this->paginate());
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
    	$uploadDir = '/app/webroot/files/news';
    	$this->set('uploadDir', $uploadDir);
        if (!empty($this->data)) {
        	if (!empty($this->data['picture']))
        	{
        		//ПЕРЕИМЕНОВЫВАЕМ ВРЕМЕННЫЕ ИМЕНА
				$dir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
				$picture = $dir . '/' . $this->findByPreview($dir, $this->data['picture']);
				$info = pathinfo($picture);
				$newPicture = $dir . '/' . $this->authUser['userid'] . '_' . time() . '.' . $info['extension'];
				rename($picture, $newPicture);

				$preview = $dir . '/small/' . $this->data['picture'];
				$info = pathinfo($preview);
				$newPreview = $dir . '/small/' . $this->authUser['userid'] . '_' . time() . '.' . $info['extension'];
				rename($preview, $newPreview);

        		$this->data['News']['img'] = basename($newPreview);
        	}
        	else
        	{
        		unset($this->data['News']['img']);
        	}
        	$this->data['News']['modified'] = date('Y-m-d H:i:s');
            if ($this->News->save($this->data)) {

            	cache::delete('News.ftpInfo', 'searchres');

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