<?php
class BlogsController extends AppController
{

    var $name = 'Blogs';
    var $helpers = array('Html' , 'Form' , 'TagCloud');
    var $uses = array('Post' , 'Blog');

    function index()
    {
        $this->redirect('/posts');
    }

    function view($id = null)
    {
        if (!$id)
        {
            $blog = $this->Blog->findByUserId($this->authUser['userid']);
            if (!empty($blog['Blog']))
            {
                $this->redirect('/blogs/view/' . $blog['Blog']['id']);
            }
            else
            {
                $this->redirect('/blogs/add');
            }
        }

        $pagination['Post'] = array('order' => array('Post.created' => 'desc'),
                                    'conditions' => array('Post.access' => 'public' , 'Post.blog_id' => $id));
        $this->Blog->contain(array('User' => array('fields' => array('User.username' , 'User.userid'))));
        $this->paginate = $pagination;

        $blog = $this->Blog->read(null, $id);
        $this->set('blog', $blog);
        $this->set('posts', $this->paginate('Post'));
        $this->data['Blocks']['Blog'] = $blog['Blog'];

        $this->pageTitle = 'Блоги - Блог ' . $blog['Blog']['title'];
    }

    function add()
    {
        $this->pageTitle = 'Блоги - Создание блога';
        $this->Blog->recursive = -1;
        $blog = $this->Blog->findByUserId($this->authUser['userid']);
        if (!empty($blog['Blog']))
        {
            $this->redirect('/blogs/view/' . $blog['Blog']['id']);
        }

        if (!empty($this->data))
        {
            $this->Blog->create();
            $this->data['Blog']['user_id'] = $this->authUser['userid'];
            if ($this->Blog->save($this->data))
            {
                $this->Session->setFlash("Блог сохранен.");
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash("Не могу сохранить блог, заполните все поля.");
            }
        }
    }

    function edit()
    {
        $this->pageTitle = 'Блоги - Редактирование блога';
        if (!empty($this->data))
        {
            $this->data['Blog']['user_id'] = $this->authUser['userid'];
            if ($this->Blog->save($this->data))
            {
                $this->Session->setFlash("Блог сохранен.");
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash("Не могу сохранить блог, заполните все поля.");
            }
        }
        if (empty($this->data))
        {
            $this->Blog->recursive = -1;
            $this->data = $this->Blog->findByUserId($this->authUser['userid']);
            $this->data['Blocks']['Blog'] = $this->data['Blog'];
        }
    }

    function delete()
    {
        $this->Blog->recursive = -1;
        $blog = $this->Blog->findByUserId($this->authUser['userid']);
        if ($this->Blog->del($blog['Blog']['id']))
        {
            $this->Session->setFlash(__('Blog deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_index()
    {
        $this->Blog->recursive = 0;
        $this->set('blogs', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Blog.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('blog', $this->Blog->read(null, $id));
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Blog->create();
            if ($this->Blog->save($this->data))
            {
                $this->Session->setFlash("Блог сохранен.");
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash("Не могу сохранить блог, заполните все поля.");
            }
        }
        $users = $this->Blog->User->find('list');
        $this->set(compact('users'));
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Blog', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Blog->save($this->data))
            {
                $this->Session->setFlash("Блог сохранен.");
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash("Не могу сохранить блог, заполните все поля.");
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Blog->read(null, $id);
        }
        $users = $this->Blog->User->find('list');
        $this->set(compact('users'));
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Blog', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Blog->del($id))
        {
            $this->Session->setFlash(__('Blog deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>