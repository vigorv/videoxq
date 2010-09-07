<?php
class SitePagesController extends AppController {

    var $name = 'SitePages';
    var $helpers = array('Html', 'Form' );

    function display() {
        $path = func_get_args();

        if (!count($path)) {
            $this->redirect('/');
        }
        $count = count($path);
        $page = $subpage = $title = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title = Inflector::humanize($path[$count - 1]);
        }
        $this->set(compact('page', 'subpage', 'title'));
        $this->render(join('/', $path));
    }


    function index() {
        $this->SitePage->recursive = 0;
        $this->set('sitePages', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash('Invalid Site Page.');
            $this->redirect(array('action'=>'index'), null, true);
        }
        $page = $this->SitePage->read(null, $id);
        $this->set('sitePage', $page);
        $this->pageTitle = $page['SitePage']['title'];
    }



    function admin_index() {
        $this->SitePage->recursive = 0;
        $this->set('sitePages', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash('Invalid Site Page.');
            $this->redirect(array('action'=>'index'), null, true);
        }
        $this->set('sitePage', $this->SitePage->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->cleanUpFields();
            $this->SitePage->create();
            if ($this->SitePage->save($this->data)) {
                $this->Session->setFlash('The Site Page has been saved');
                $this->redirect(array('action'=>'index'), null, true);
            } else {
                $this->Session->setFlash('The Site Page could not be saved. Please, try again.');
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash('Invalid Site Page');
            $this->redirect(array('action'=>'index'), null, true);
        }
        if (!empty($this->data)) {
            $this->cleanUpFields();
            if ($this->SitePage->save($this->data)) {
                $this->Session->setFlash('The Site Page saved');
                $this->redirect(array('action'=>'index'), null, true);
            } else {
                $this->Session->setFlash('The Site Page could not be saved. Please, try again.');
            }
        }
        if (empty($this->data)) {
            $this->data = $this->SitePage->read(null, $id);
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('Invalid id for Site Page');
            $this->redirect(array('action'=>'index'), null, true);
        }
        if ($this->SitePage->del($id)) {
            $this->Session->setFlash('Site Page #'.$id.' deleted');
            $this->redirect(array('action'=>'index'), null, true);
        }
    }

}
?>