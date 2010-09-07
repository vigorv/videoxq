<?php
class BlogGroupsController extends AppController {

	var $name = 'BlogGroups';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->BlogGroup->recursive = 0;
		$this->set('blogGroups', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid BlogGroup.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('blogGroup', $this->BlogGroup->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->BlogGroup->create();
			if ($this->BlogGroup->save($this->data)) {
				$this->Session->setFlash(__('The BlogGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The BlogGroup could not be saved. Please, try again.', true));
			}
		}
		$users = $this->BlogGroup->User->find('list');
		$blogs = $this->BlogGroup->Blog->find('list');
		$this->set(compact('users', 'blogs'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid BlogGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->BlogGroup->save($this->data)) {
				$this->Session->setFlash(__('The BlogGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The BlogGroup could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->BlogGroup->read(null, $id);
		}
		$users = $this->BlogGroup->User->find('list');
		$blogs = $this->BlogGroup->Blog->find('list');
		$this->set(compact('users','blogs'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for BlogGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->BlogGroup->del($id)) {
			$this->Session->setFlash(__('BlogGroup deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}


	function admin_index() {
		$this->BlogGroup->recursive = 0;
		$this->set('blogGroups', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid BlogGroup.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('blogGroup', $this->BlogGroup->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->BlogGroup->create();
			if ($this->BlogGroup->save($this->data)) {
				$this->Session->setFlash(__('The BlogGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The BlogGroup could not be saved. Please, try again.', true));
			}
		}
		$users = $this->BlogGroup->User->find('list');
		$blogs = $this->BlogGroup->Blog->find('list');
		$this->set(compact('users', 'blogs'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid BlogGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->BlogGroup->save($this->data)) {
				$this->Session->setFlash(__('The BlogGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The BlogGroup could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->BlogGroup->read(null, $id);
		}
		$users = $this->BlogGroup->User->find('list');
		$blogs = $this->BlogGroup->Blog->find('list');
		$this->set(compact('users','blogs'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for BlogGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->BlogGroup->del($id)) {
			$this->Session->setFlash(__('BlogGroup deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>