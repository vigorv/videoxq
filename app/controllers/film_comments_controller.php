<?php
class FilmCommentsController extends AppController
{
    var $helpers = array('Rss');
    var $components = array('RequestHandler');	
    var $name = 'FilmComments';
    var $viewPath = 'media/film_comments';

    function add()
    {
        if (!empty($this->data))
        {
            if ($this->authUser['userid'])
            {
                $this->data['FilmComment']['user_id'] = $this->authUser['userid'];
                $this->data['FilmComment']['username'] = $this->authUser['username'];
                $this->data['FilmComment']['email'] = $this->authUser['email'];
            }

            $this->data['FilmComment']['ip'] = $_SERVER['REMOTE_ADDR'];
            $this->FilmComment->create();
            if ($this->FilmComment->save($this->data))
            {
                //$this->Session->setFlash(__('The FilmComment has been saved', true));
                $this->redirect($this->referer('/media'));
            }
            else
            {
                //$this->Session->setFlash(__('The FilmComment could not be saved.', true));
                $this->redirect($this->referer('/media'));
            }
        }
        $this->redirect('/media');
    }

    function admin_index()
    {
        $this->FilmComment->recursive = 0;
        $this->set('filmComments', $this->paginate());
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid FilmComment', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->FilmComment->save($this->data))
            {
                $this->Session->setFlash(__('The FilmComment has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The FilmComment could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->FilmComment->read(null, $id);
        }
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for FilmComment', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->FilmComment->recursive = -1;
        $comment = $this->FilmComment->read(null, $id);
        if ($this->FilmComment->del($id))
        {
            Cache::delete('Catalog.lastComments');
        	$this->Session->setFlash(__('FilmComment deleted', true));
            $this->redirect('/media/view/' . $comment['FilmComment']['film_id']);
            
        }
    }
    
	function rss($limit=100)
	{
		header("Content-Type: text/xml; charset=utf-8");
		$this->pageTitle='RSS комментариев к фильмам';		
		Configure::write('debug', 0);
		$this->layout = 'rss/default';
        $this->FilmComment->recursive = 0;
		$FilmComments = $this->FilmComment->findAll(null, null, 'FilmComment.created desc', $limit);
		$this->set('FilmComments',$FilmComments);
	}     

}
?>