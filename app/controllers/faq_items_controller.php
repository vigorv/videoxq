<?php
class FaqItemsController extends AppController {

    var $name = 'FaqItems';
    var $helpers = array('Html', 'Form');
    var $uses = array('FaqItem', 'FaqCategory', 'User');

    function index() {
        $this->FaqItem->recursive = 0;
        $this->set('faqItems', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid FaqItem.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->FaqItem->recursive = 0;
        $this->set('faqItem', $this->FaqItem->read(null, $id));
        $this->set('faqComments', $this->FaqItem->FaqComment->findAllByFaqItemId($id));
    }

    function admin_index() {
        $this->FaqItem->recursive = 0;
        $this->set('faqItems', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid FaqItem.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('faqItem', $this->FaqItem->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->FaqItem->create();
            if ($this->FaqItem->save($this->data)) {
                $this->Session->setFlash(__('The FaqItem has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The FaqItem could not be saved. Please, try again.', true));
            }
        }
        $faqCategories = $this->FaqItem->FaqCategory->find('list');
        $this->set(compact('faqCategories'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid FaqItem', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->FaqItem->save($this->data)) {
                $this->Session->setFlash(__('The FaqItem has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The FaqItem could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->FaqItem->read(null, $id);
        }
        $faqCategories = $this->FaqItem->FaqCategory->find('list');
        $this->set(compact('faqCategories'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for FaqItem', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->FaqItem->del($id)) {
            $this->Session->setFlash(__('FaqItem deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>