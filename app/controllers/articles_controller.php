<?php
class ArticlesController extends AppController {

	var $name = 'Articles';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->Article->recursive = 0;
		$this->set('articles', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Article.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('article', $this->Article->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Article->create();
			if ($this->Article->save($this->data)) {
				$this->Session->setFlash(__('The Article has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Article could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Article->User->find('list');
		$articleCategories = $this->Article->ArticleCategory->find('list');
		$this->set(compact('users', 'articleCategories'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Article', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Article->save($this->data)) {
				$this->Session->setFlash(__('The Article has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Article could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Article->read(null, $id);
		}
		$users = $this->Article->User->find('list');
		$articleCategories = $this->Article->ArticleCategory->find('list');
		$this->set(compact('users','articleCategories'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Article', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Article->del($id)) {
			$this->Session->setFlash(__('Article deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}


	function admin_index() {
		$this->Article->recursive = 0;
		$this->set('articles', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Article.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('article', $this->Article->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Article->create();
			if ($this->Article->save($this->data)) {
				$this->Session->setFlash(__('The Article has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Article could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Article->User->find('list');
		$articleCategories = $this->Article->ArticleCategory->find('list');
		$this->set(compact('users', 'articleCategories'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Article', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Article->save($this->data)) {
				$this->Session->setFlash(__('The Article has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Article could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Article->read(null, $id);
		}
		$users = $this->Article->User->find('list');
		$articleCategories = $this->Article->ArticleCategory->find('list');
		$this->set(compact('users','articleCategories'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Article', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Article->del($id)) {
			$this->Session->setFlash(__('Article deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>