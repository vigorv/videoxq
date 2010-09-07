<?php
class SearchWordsController extends AppController {

    var $name = 'SearchWords';
    var $helpers = array('Html', 'Form');
    //var $uses = array('SearchLog');


    function admin_index() {
        $this->SearchWord->recursive = 0;
        $this->set('searchWords', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid SearchWord.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('searchWord', $this->SearchWord->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->SearchWord->create();
            if ($this->SearchWord->save($this->data)) {
                $this->Session->setFlash(__('The SearchWord has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The SearchWord could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid SearchWord', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->SearchWord->save($this->data)) {
                $this->Session->setFlash(__('The SearchWord has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The SearchWord could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->SearchWord->read(null, $id);
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for SearchWord', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->SearchWord->del($id)) {
            $this->Session->setFlash(__('SearchWord deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

    function autoComplete()
    {
	    $this->layout = "ajax";
    	return;

    	$words = array();
        $search = $this->params['url']['q'];
        //$words = $this->SearchWord->findAll("words LIKE '%{$search}%'");
		//$this->set('words', $words);

        $this->paginate['SearchLog']['fields'] = array('COUNT(keyword) AS count', 'keyword');
        $this->paginate['SearchLog']['group'] = 'keyword';
        $this->paginate['SearchLog']['order'] = 'count desc, created desc';
        $this->paginate['SearchLog']['limit'] = 9;
        if (!empty($search))
            $this->paginate['SearchLog']['conditions'][] = array('keyword like ' => '%' . $search . '%');

        $this->set('keyword', $this->paginate('SearchLog'));

    }
}
?>