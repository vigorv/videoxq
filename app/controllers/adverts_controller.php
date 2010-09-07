<?php
class AdvertsController extends AppController {

    var $name = 'Adverts';
    var $helpers = array('Html', 'Form');

    function index() {
        $this->Advert->recursive = 0;
        $this->set('adverts', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Advert.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('advert', $this->Advert->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            if (isset($this->data['Advert']['captcha']))
            $this->data['Advert']['captcha2'] = $this->Session->read(
                            'captcha');

            $this->Advert->create();
            if ($this->Advert->save($this->data)) {
                $this->Session->setFlash(__('The Advert has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Advert could not be saved. Please, try again.', true));
            }
        }

        $users = $this->Advert->User->find('list');
        $doCategories = $this->Advert->DoCategory->find('list');
        $this->set(compact('users', 'doCategories'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Advert', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Advert->save($this->data)) {
                $this->Session->setFlash(__('The Advert has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Advert could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Advert->read(null, $id);
        }
        $users = $this->Advert->User->find('list');
        $doCategories = $this->Advert->DoCategory->find('list');
        $this->set(compact('users','doCategories'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Advert', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Advert->del($id)) {
            $this->Session->setFlash(__('Advert deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }


    function admin_index() {
        $this->Advert->recursive = 0;
        $this->set('adverts', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Advert.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('advert', $this->Advert->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Advert->create();
            if ($this->Advert->save($this->data)) {
                $this->Session->setFlash(__('The Advert has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Advert could not be saved. Please, try again.', true));
            }
        }
        $users = $this->Advert->User->find('list');
        $doCategories = $this->Advert->DoCategory->find('list');
        $this->set(compact('users', 'doCategories'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Advert', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Advert->save($this->data)) {
                $this->Session->setFlash(__('The Advert has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Advert could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Advert->read(null, $id);
        }
        $users = $this->Advert->User->find('list');
        $doCategories = $this->Advert->DoCategory->find('list');
        $this->set(compact('users','doCategories'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Advert', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Advert->del($id)) {
            $this->Session->setFlash(__('Advert deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>