<?php
   	App::import('Model', 'PhotoOrder');
    App::import('Model', 'PhotoFeedback');
	
class PhotoOrdersController extends AppController
{
    public $uses = array('PhotoOrder', 'PhotoFeedback');
    public $name = 'PhotoOrder';
     /*
     * 
     *
     * @var PhotoOrder
     */
    public $PhotoOrder,$PhotoFeedback;

    public function index()
    {
        
    }
    /*
    public function scode()
    {
        $this->layout = 'ajax';
    	$this->set("captcha", $this->Session->read('captcha'));
    	$this->render();
    }
    function addusers ()
    {
    if (isset($this->data['User']['captcha']))
    $this->data['User']['captcha2'] = $this->Session->read('captcha');
    unset($this->data['User']['captcha']);
    unset($this->data['User']['captcha2']);
    }
    function captcha()
    {
        $this->layout = 'ajax';
        $this->Captcha->render();
        $this->view = null;
    }*/
    /*Запись заявок на фотографа*/
     function users()
     {
        if ($_POST['captcha'] != $this->Session->read('captcha') ) {
            $this->set("gopa", "123");
    }
    else
    {
      $data = Array
(
    "PhotoOrder" => Array
        (
            "fio" => $_POST['fio'],
            "tip" => $_POST['tip'],
            "date" => $_POST['date'],
            "place" => $_POST['place'],
            "duration" => $_POST['duration'],
            "phone" => $_POST['phone'],
            "mail" => $_POST['mail'],
            "note" => $_POST['note']
            
        )
);
    $this->PhotoOrder->save($data);
    $this->set("data", $data['PhotoOrder']['fio']);
    }   
     }
     /*ОБРАТНАЯ СВЯЗЬ*/
     function feedback()
     {
        if ($_POST['captcha'] != $this->Session->read('captcha') ) {
            $this->set("gopa", "123");
    }
    else
    {
      $data = Array
(
    "PhotoFeedback" => Array
        (
            "email" => $_POST['email'],
            "text" => $_POST['text']
        )
);
    $this->PhotoFeedback->save($data);
     }
     }
     function captcha()
    {
        $this->layout = 'ajax';
        $this->Captcha->render();
        $this->view = null;
    }
    public function admin_edit($id = null)
    {
        if (!$id && empty($this->data))
        {
//            $this->Session->setFlash(__('Invalid Payment', true));
//            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data))
        {
        	$this->data['Pay']['findate'] = mktime(
        		$this->data['Pay']['findate']['hour'],
        		$this->data['Pay']['findate']['min'],
        		0,
        		$this->data['Pay']['findate']['month'],
        		$this->data['Pay']['findate']['day'],
        		$this->data['Pay']['findate']['year']
        	);
            if ($this->Pay->save($this->data))
            {
            	//ВНОСИМ В ГРУППУ ВИП
	   			$sql = 'delete from groups_users where user_id = ' . $this->data['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
   	   			$this->Pay->query($sql);
	   			$sql= 'insert into groups_users (user_id, group_id) values(' . $this->data['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
   	   			$this->Pay->query($sql);

    			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
   				$uInfo = array('User' => array('userid' => $this->data['Pay']['user_id'], 'lastactivity' => time()));
				$this->User->create();
   				$this->User->save($uInfo);

    			$this->Session->setFlash(__('The Payment has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Payment could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Pay->read(null, $id);
        }
        $this->set("data", $this->data);
    }

}