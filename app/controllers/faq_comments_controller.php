<?php
class FaqCommentsController extends AppController {

    var $name = 'FaqComments';
    var $helpers = array('Html', 'Form');

    function add($faqItemId = null)
    {
        if (!empty($this->data))
        {
            $this->FaqComment->create();
            $this->data['FaqComment']['user_id'] = $this->Auth2->user('userid');
            if ($this->FaqComment->save($this->data))
            {
                $this->Session->setFlash(__('The FaqComment has been saved', true));
//                $this->redirect(array('action'=>'index'));
            }
            else
            {
                $this->Session->setFlash(__('The FaqComment could not be saved. Please, try again.', true));
            }

            $this->redirect(array('action'=>'view', 'controller' => 'faq_items', $this->data['FaqComment']['faq_item_id']));
        }
        $this->data['FaqComment']['faq_item_id'] = $faqItemId;
    }

    function admin_index() {
        $this->FaqComment->recursive = 0;
        $this->set('faqComments', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid FaqComment.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('faqComment', $this->FaqComment->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->FaqComment->create();
            if ($this->FaqComment->save($this->data)) {
                $this->Session->setFlash(__('The FaqComment has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The FaqComment could not be saved. Please, try again.', true));
            }
        }
        $faqItems = $this->FaqComment->FaqItem->find('list');
        $this->set(compact('faqItems'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid FaqComment', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->FaqComment->save($this->data)) {
                $this->Session->setFlash(__('The FaqComment has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The FaqComment could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->FaqComment->read(null, $id);
        }
        $faqItems = $this->FaqComment->FaqItem->find('list');
        $this->set(compact('faqItems'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for FaqComment', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->FaqComment->del($id)) {
            $this->Session->setFlash(__('FaqComment deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>