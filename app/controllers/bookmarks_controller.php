<?php
App::import('Sanitize');
class BookmarksController extends AppController {

    var $name = 'Bookmarks';
    var $helpers = array('Html', 'Form');
    var $components = array('RequestHandler');

    function index() {
        $this->Bookmark->recursive = -1;
        $this->set('bookmarks', $this->paginate(null, array('user_id' => $this->Auth2->user('userid'))));
    }

    function add() {
    	if ($this->RequestHandler->isAjax())
        {
        	$this->layout = 'ajax';
            if (empty($this->data))
            {
                $this->render('add_ajax');
                return;
            }

            $this->Bookmark->create();
            $this->data['Bookmark']['user_id'] = $this->Auth2->user('userid');
            if ($this->Bookmark->save($this->data))
            {
                $this->data['Bookmark']['saved'] = 'yes';
            }
            $this->render('add_ajax');
            return;
        }

        if (!empty($this->data)) {
            $this->Bookmark->create();
            $this->data['Bookmark']['user_id'] = $this->Auth2->user('userid');
            if ($this->Bookmark->save($this->data)) {
                $this->Session->setFlash(__('The Bookmark has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Bookmark could not be saved. Please, try again.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            $this->data['Bookmark']['user_id'] = $this->Auth2->user('userid');
            if ($this->Bookmark->save($this->data)) {
                $this->Session->setFlash(__('The Bookmark has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Bookmark could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Bookmark->read(null, $id);
            if ($this->data['Bookmark']['user_id'] != $this->Auth2->user('userid'))
            {
                $this->Session->setFlash(__('Invalid Bookmark', true));
                $this->redirect(array('action'=>'index'));
            }
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }
        $bookmark = $this->Bookmark->read(null, $id);
        if ($bookmark['Bookmark']['user_id'] != $this->Auth2->user('userid'))
        {
            $this->Session->setFlash(__('Invalid Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }

        if ($this->Bookmark->del($id)) {
            $this->Session->setFlash(__('Bookmark deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }


    function admin_index() {
        $this->Bookmark->recursive = 0;
        $this->set('bookmarks', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Bookmark.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('bookmark', $this->Bookmark->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Bookmark->create();
            if ($this->Bookmark->save($this->data)) {
                $this->Session->setFlash(__('The Bookmark has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Bookmark could not be saved. Please, try again.', true));
            }
        }
        $users = $this->Bookmark->User->find('list');
        $this->set(compact('users'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Bookmark->save($this->data)) {
                $this->Session->setFlash(__('The Bookmark has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Bookmark could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Bookmark->read(null, $id);
        }
        $users = $this->Bookmark->User->find('list');
        $this->set(compact('users'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Bookmark->del($id)) {
            $this->Session->setFlash(__('Bookmark deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>