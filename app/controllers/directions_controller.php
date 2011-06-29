<?php
class DirectionsController extends AppController {

    var $name = 'Directions';
    var $helpers = array('Html', 'Form');

    var $uses = array('Directions');

	/**
	 * модель таблицы Directions
	 *
	 * @var AppModel
	 */
    var $Directions;


    function admin_index() {
        $this->Directions->recursive = 0;
        $this->set('lst', $this->paginate());
    }

    function admin_edit($id = null) {
    	if (!empty($this->data))
    	{
	    	$this->data['Directions']['modified'] = date('Y-m-d H:i:s');
	        if ($this->Directions->save($this->data)) {

	            $this->Session->setFlash(__('The Direction has been saved', true));
	            $this->redirect(array('action'=>'index'));
	        }
    	}

        if (!empty($id)) {
            $info = $this->Directions->read(null, $id);
            $this->set('info', $info);
        }
        $this->set('authUser', $this->authUser);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Directions', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Directions->del($id)) {
            $this->Session->setFlash(__('Directions deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>