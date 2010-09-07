<?php
class FaqCategoriesController extends AppController {

    var $name = 'FaqCategories';
    var $helpers = array('Html', 'Form');

    function index() {
        $this->FaqCategory->recursive = 0;
        $this->set('faqCategories', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid FaqCategory.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('faqCategory', $this->FaqCategory->read(null, $id));
    }

    function admin_index() {
        $this->FaqCategory->recursive = 0;
        $this->set('faqCategories', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid FaqCategory.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('faqCategory', $this->FaqCategory->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->FaqCategory->create();
            if ($this->FaqCategory->save($this->data)) {
                $this->Session->setFlash(__('The FaqCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The FaqCategory could not be saved. Please, try again.', true));
            }
        }
        $faqCategoryParents = $this->FaqCategory->FaqCategoryParent->find('list');
        $this->set('parents', $faqCategoryParents);
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid FaqCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->FaqCategory->save($this->data)) {
                $this->Session->setFlash(__('The FaqCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The FaqCategory could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->FaqCategory->read(null, $id);
        }
        $faqCategoryParents = $this->FaqCategory->FaqCategoryParent->find('list');
        $this->set('parents', $faqCategoryParents);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for FaqCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->FaqCategory->del($id)) {
            $this->Session->setFlash(__('FaqCategory deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>