<?php
class CatalogCategoriesController extends AppController {

    var $name = 'CatalogCategories';
    var $helpers = array('Html', 'Form');

    function index() {
        $this->CatalogCategory->recursive = 0;
        $this->set('catalogCategories', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid CatalogCategory.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('catalogCategory', $this->CatalogCategory->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->CatalogCategory->create();
            if ($this->CatalogCategory->save($this->data)) {
                $this->Session->setFlash(__('The CatalogCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The CatalogCategory could not be saved. Please, try again.', true));
            }
        }
        $catalogCategoryParents = $this->CatalogCategory->CatalogCategoryParent->find('list');
        $this->set('parents', $catalogCategoryParents);
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid CatalogCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->CatalogCategory->save($this->data)) {
                $this->Session->setFlash(__('The CatalogCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The CatalogCategory could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->CatalogCategory->read(null, $id);
        }
        $catalogCategoryParents = $this->CatalogCategory->CatalogCategoryParent->find('list');
        $this->set('parents', $catalogCategoryParents);
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for CatalogCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->CatalogCategory->del($id)) {
            $this->Session->setFlash(__('CatalogCategory deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }


    function admin_index() {
        $this->CatalogCategory->recursive = 0;
        $this->set('catalogCategories', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid CatalogCategory.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('catalogCategory', $this->CatalogCategory->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->CatalogCategory->create();
            if ($this->CatalogCategory->save($this->data)) {
                $this->Session->setFlash(__('The CatalogCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The CatalogCategory could not be saved. Please, try again.', true));
            }
        }
        $catalogCategoryParents = $this->CatalogCategory->CatalogCategoryParent->find('list');
        $this->set(compact('catalogCategoryParents'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid CatalogCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->CatalogCategory->save($this->data)) {
                $this->Session->setFlash(__('The CatalogCategory has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The CatalogCategory could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->CatalogCategory->read(null, $id);
        }
        $catalogCategoryParents = $this->CatalogCategory->CatalogCategoryParent->find('list');
        $this->set(compact('catalogCategoryParents'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for CatalogCategory', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->CatalogCategory->del($id)) {
            $this->Session->setFlash(__('CatalogCategory deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>