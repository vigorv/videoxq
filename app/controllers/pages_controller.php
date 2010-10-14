<?php
class PagesController extends AppController {

    var $name = 'Pages';
    var $helpers = array('Html', 'Form');

    var $uses = array('Page', 'Error404');

	/**
	 * модель таблицы 404
	 *
	 * @var AppModel
	 */
    var $Error404;

    function index()
    {
    	$this->redirect('/');
    }

    function not_found()
    {
    	$this->cakeError('error404');
    }

    function not_translate()
    {

    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Page.', true));
            $this->redirect('/');
        }

		$lang = Configure::read('Config.language');
		if (($lang == _ENG_) && ($id != 'reklama_en') && ($id != 'kontaktyi_en') && ($id != 'nashi-partneryi_en'))
		{
            $this->redirect('/pages/not_translate');
    	}

		if (is_numeric($id) && $page = $this->Page->findById($id))
            $this->set('page', $page);
        elseif ($page = $this->Page->findByUrl($id))
            $this->set('page', $page);
        else
            $this->set('page', array('Page' => array('text' => '')));

        if (!empty($page['Page']['layout']))
            $this->layout = $page['Page']['layout'];


        switch ($id == 'error404')
        {
        	case 'error404':
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				$user_ip = (empty($_SERVER['HTTP_X_REAL_IP']) ? '' : $_SERVER['HTTP_X_REAL_IP']);
				$user_id = 0;
				if (!empty($this->authUser['userid']))
					$user_id = $this->authUser['userid'];
				$link = '';
				if (!empty($this->passedArgs['url']))
					$link = strtr($this->passedArgs['url'], array('|' => '/', '!' => ':'));//ДЕКОДИРУЕМ УРЛ
				//ФИКСИРУЕМ ОШИБКУ В БД
				$info = $this->Error404->find(array('Error404.link' => $link), array('Error404.id', 'Error404.count'));
				$event= (empty($_SERVER['HTTP_REFERER'])) ? '' : $_SERVER['HTTP_REFERER'];
				if (empty($info['Error404']['id']))
				{
					$this->Error404->create();
					$info = array('Error404' => array(
							'user_ip'	=> $user_ip,
							'user_id'	=> $user_id,
							'link'		=> $link,
							'event'		=> $event,
							'info'		=> $user_agent,
							'date'		=> date('Y-m-d H:i:s'),
						)
					);
				}
				else
				{
					$info['Error404']['count']++;

				}
				$this->Error404->save($info);
        	break;
        	case 'error503':

        	break;
        }
        $this->pageTitle = $page['Page']['title'];
    }

    function admin_index() {
        $this->Page->recursive = 0;
        $this->set('pages', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Page.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('page', $this->Page->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Page->create();
            if ($this->Page->save($this->data)) {
                $this->Session->setFlash(__('The Page has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Page could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Page', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Page->save($this->data)) {
                $this->Session->setFlash(__('The Page has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Page could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Page->read(null, $id);
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Page', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Page->del($id)) {
            $this->Session->setFlash(__('Page deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>