<?php
class GroupsController extends AppController {

    var $name = 'Groups';
    var $helpers = array('Html', 'Form');

    function index() {
        $this->Group->recursive = 0;
        $this->set('groups', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Group.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('group', $this->Group->read(null, $id));
    }

    function admin_index() {
    	$this->Group->recursive = 0;
        $this->set('groups', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Group.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Group->restrict('Vbgroup');
        $this->set('group', $this->Group->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Group->create();
            if ($group = $this->Group->save($this->data)) {

                //создаем АЦЛ для новой группы
                $aro = new Aro();
                $aro->create();
                $self = $aro->node(array('model' => 'Group', 'foreign_key' => $group['Group']['id']));
                if (!$self)
                $aro->save(array('foreign_key' => $group['Group']['id'], 'parent_id' => $this->data['Group']['parent_id'],
                                 'alias' => $this->data['Group']['title'], 'model' => 'Group'));
                else
                {
                    $self[0]['Aro']['alias'] = $this->data['Group']['title'];
                    $self[0]['Aro']['parent_id'] = $this->data['Group']['parent_id'];
                    $aro->save($self[0]);
                }

                $this->Session->setFlash(__('The Group has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Group could not be saved. Please, try again.', true));
            }
        }
        $vbgroups = $this->Group->Vbgroup->find('list');
        $this->set(compact('vbgroups'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Group', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Group->save($this->data)) {

                $aro = new Aro();
                $gAro = $aro->node(array('model' => 'Group', 'foreign_key' => $this->data['Group']['id']));
                if (!$gAro)
                {
                    $aro->create();
                    $aro->save(array('foreign_key' => $this->data['Group']['id'], 'parent_id' => null,
                                     'alias' => $this->data['Group']['title'], 'model' => 'Group'));
                }

                $this->Session->setFlash(__('The Group has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Group could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->Group->restrict('Vbgroup');
            $this->data = $this->Group->read(null, $id);
        }
        $vbgroups = $this->Group->Vbgroup->find('list');
        $this->set(compact('vbgroups'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Group', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Group->del($id)) {
            $this->Session->setFlash(__('Group deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>