<?php
class DoController extends AppController {

    var $name = 'Do';
    var $helpers = array('Html', 'Form');
    var $uses = array('DoCategory');

    function index() {
        $this->DoCategory->recursive = 0;
        $this->set('doCategories', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid DoCategory.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('doCategory', $this->DoCategory->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->DoCategory->create();
            //$this->data['DoCategory']['url'] = $this->_getUniqueUrl('DoCategory', $this->data['DoCategory']['title']);
            if ($this->DoCategory->save($this->data)) {
                $this->Session->setFlash(__('The DoCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The DoCategory could not be saved. Please, try again.', true));
            }
        }
        $parents = $this->DoCategory->Parent->find('list');
        $this->set(compact('parents'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid DoCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            //$this->data['DoCategory']['url'] = $this->_getUniqueUrl('DoCategory', $this->data['DoCategory']['title']);
            if ($this->DoCategory->save($this->data)) {
                $this->Session->setFlash(__('The DoCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The DoCategory could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->DoCategory->read(null, $id);
        }
        $parents = $this->DoCategory->Parent->find('list');
        $this->set(compact('parents'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for DoCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->DoCategory->del($id)) {
            $this->Session->setFlash(__('DoCategory deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }


    function admin_index() {
        $this->DoCategory->recursive = 0;
        $this->set('doCategories', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid DoCategory.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('doCategory', $this->DoCategory->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->DoCategory->create();
            if ($this->DoCategory->save($this->data)) {
                $this->Session->setFlash(__('The DoCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The DoCategory could not be saved. Please, try again.', true));
            }
        }
        $parents = $this->DoCategory->Parent->find('list');
        $this->set(compact('parents'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid DoCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->DoCategory->save($this->data)) {
                $this->Session->setFlash(__('The DoCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The DoCategory could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->DoCategory->read(null, $id);
        }
        $parents = $this->DoCategory->Parent->find('list');
        $this->set(compact('parents'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for DoCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->DoCategory->del($id)) {
            $this->Session->setFlash(__('DoCategory deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>