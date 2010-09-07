<?php
class TagsController extends AppController
{

    var $name = 'Tags';
    var $helpers = array('Html', 'Form', 'TagCloud');

    function ajax_taglist()
    {
        $this->layout = 'ajax';
        $search = $this->params['url']['q'];
        $this->Tag->recursive = - 1;
        $this->set('tags', $this->Tag->find('all', array('conditions' => array('title LIKE' => $search . '%'))));
    }

    function index($type=Null)
    {
        if (!empty($this->passedArgs['type']))
        {
        	die();
        }
            //$this->passedArgs['direction'] = 'desc';
        $this->Tag->recursive = 0;
        //$tags = $this->Tag->getTagFrequency(null, 'Post', 1000);
        //pr($tags);
        $tags=$this->Tag->findAll();
        //pr($tags);
        //$counts = Set::extract('/0/count', $tags);
        //$tags = Set::extract('/Tag/title', $tags);
        $tags=set::combine('{n}.Tag.title','{n}.Tag',$tags);
        pr($tags);
        $this->set('tags', $tags);
    }

    function view($id = null)
    {
        if (!$id)
        {
            $this->redirect(array('action' => 'index'));
        }

        $this->Tag->recursive = -1;
        if (is_numeric($id))
            $tag = $this->Tag->read(null, $id);
        else
            $tag = $this->Tag->findByTitle($id);

        $pagination['Post'] = array('order' => array('Post.created' => 'desc'),
                                    'conditions' => array('Post.access' => 'public',
                                                          'tag' => $tag['Tag']['id']));
        $this->paginate = $pagination;
        $this->set('tag', $tag);
        $this->set('posts', $this->paginate('Post'));

    }

    function add()
    {
        if (! empty($this->data))
        {
            $this->Tag->create();
            if ($this->Tag->save($this->data))
            {
                $this->Session->setFlash(__('The Tag has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
            }
        }
    }

    function edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Tag', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Tag->save($this->data))
            {
                $this->Session->setFlash(__('The Tag has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Tag->read(null, $id);
        }
    }

    function delete($id = null)
    {
        if (!$id)
        {
            $this->Session->setFlash(__('Invalid id for Tag', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Tag->del($id))
        {
            $this->Session->setFlash(__('Tag deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_index()
    {
        $this->Tag->recursive = 0;
        $this->set('tags', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Tag.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('tag', $this->Tag->read(null, $id));
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Tag->create();
            if ($this->Tag->save($this->data))
            {
                $this->Session->setFlash(__('The Tag has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
            }
        }
        $posts = $this->Tag->Post->find('list');
        $blogs = $this->Tag->Blog->find('list');
        $this->set(compact('posts', 'blogs'));
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Tag', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Tag->save($this->data))
            {
                $this->Session->setFlash(__('The Tag has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Tag->read(null, $id);
        }
        $posts = $this->Tag->Post->find('list');
        $blogs = $this->Tag->Blog->find('list');
        $this->set(compact('posts', 'blogs'));
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Tag', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Tag->del($id))
        {
            $this->Session->setFlash(__('Tag deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>