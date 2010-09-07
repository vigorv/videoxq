<?php
class CatalogItemsController extends AppController {

	var $name = 'CatalogItems';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->CatalogItem->recursive = 0;
		$this->set('catalogItems', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid CatalogItem.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('catalogItem', $this->CatalogItem->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->CatalogItem->create();
			if ($this->CatalogItem->save($this->data)) {
				$this->Session->setFlash(__('The CatalogItem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The CatalogItem could not be saved. Please, try again.', true));
			}
		}
		$catalogCategories = $this->CatalogItem->CatalogCategory->find('list');
		$this->set(compact('catalogCategories'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid CatalogItem', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->CatalogItem->save($this->data)) {
				$this->Session->setFlash(__('The CatalogItem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The CatalogItem could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->CatalogItem->read(null, $id);
		}
		$catalogCategories = $this->CatalogItem->CatalogCategory->find('list');
		$this->set(compact('catalogCategories'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for CatalogItem', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CatalogItem->del($id)) {
			$this->Session->setFlash(__('CatalogItem deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}


	function admin_index() {
		$this->CatalogItem->recursive = 0;
		$this->set('catalogItems', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid CatalogItem.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('catalogItem', $this->CatalogItem->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->CatalogItem->create();
			if ($this->CatalogItem->save($this->data)) {
				$this->Session->setFlash(__('The CatalogItem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The CatalogItem could not be saved. Please, try again.', true));
			}
		}
		$catalogCategories = $this->CatalogItem->CatalogCategory->find('list');
		$this->set(compact('catalogCategories'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid CatalogItem', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->CatalogItem->save($this->data)) {
				$this->Session->setFlash(__('The CatalogItem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The CatalogItem could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->CatalogItem->read(null, $id);
		}
		$catalogCategories = $this->CatalogItem->CatalogCategory->find('list');
		$this->set(compact('catalogCategories'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for CatalogItem', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CatalogItem->del($id)) {
			$this->Session->setFlash(__('CatalogItem deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>