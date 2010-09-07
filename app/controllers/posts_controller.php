<?php
class PostsController extends AppController
{
    var $name = 'Posts';
    var $helpers = array('Html' , 'Form' , 'TagCloud');

    function index()
    {
        //$this->Post->recursive = 1;
        //'order' => array('Post.created' => 'desc'),
        //                                    ,
        //                                    'fields' => array('Post.*', 'Blog.title','Blog.id',
        //                                                      'UserPicture.file_name', 'COUNT(Comment.id) as comments'),
        //                                    'limit' => 20);
        //        $pagination['Post']['contain'] = array('Blog', 'User', 'UserPicture');
        //        $pagination['Post']['contain']['Comment'] = array('fields' => array('COUNT(Comment.id) as comments'));
        //        //pr($pagination);
        //        $this->paginate = $pagination;
        $this->pageTitle = 'Блоги - Лента';
        $this->paginate['Post'] = array('conditions' => array('or' => array('Post.access' => 'public',
                                                              'Post.user_id' => $this->authUser['userid'])));
        $this->set('posts', $this->paginate());
    }

    function view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Post.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Post->contain(array('Blog' , 'User' => array('fields' => array('User.username' , 'User.userid')),
                                   'UserPicture', 'Tag',
                                   'Comment' => array('User' => array('fields' => array('User.username' , 'User.userid')),
                                   'UserPicture', 'order' => 'Comment.lft ASC, Comment.created ASC')));
        $post = $this->Post->read(null, $id);
        $stack = array();
        foreach ($post['Comment'] as &$comment)
        {
            while ($stack && ($stack[count($stack) - 1] < $comment['rght']))
            {
                array_pop($stack);
            }
            $comment['level'] = count($stack);
            $stack[] = $comment['rght'];
        }

        $this->data['Blocks']['Blog'] = $post['Blog'];
        $this->Post->contain();
        $conditions = array('blog_id' => $post['Blog']['id']);
        $neighbors = $this->Post->find('neighbors', compact('conditions'));
        $this->set(compact('neighbors', 'post'));
        $this->pageTitle = 'Блоги - Блог ' . $post['Blog']['title'] . ' - ' . $post['Post']['title'];
    }

    function add()
    {
        $blog = $this->Post->Blog->find(array('user_id' => $this->authUser['userid']));
        $this->pageTitle = 'Блоги - Блог ' . $blog['Blog']['title'] . ' - Добавление поста';
        if (empty($blog['Blog']))
            $this->redirect('/blogs/add');

        if (!empty($this->data))
        {
            $this->data['Post']['blog_id'] = $blog['Blog']['id'];
            $this->data['Post']['user_id'] = $this->authUser['userid'];
            $this->data['Post']['ip'] = env('REMOTE_ADDR');

            //now lets parse the tags
            $this->data['Tag']['Tag'] = $this->Post->Tag->parseTags($this->data['Post']['tags']);
            //TODO: add tag cache
            $this->Post->create();
            if ($this->Post->save($this->data))
            {
                $this->Session->setFlash("Пост сохранен.");
                $this->redirect(array('action' => 'view', 'controller' => 'blogs', $this->data['Post']['blog_id']));
            }
            else
            {
                $this->Session->setFlash('Не могу сохранить пост');
            }
        }
        //$tags = $this->Post->Tag->find('list', array());
        //pr($tags);
        //$blogs = $this->Post->Blog->find('list');
        //pr($blogs);
        //$userPictures = $this->Post->UserPicture->find('list');
        $accesses = $this->Post->accessLevels;
        $this->set(compact('accesses'));
    }

    function edit($id = null)
    {
        if (!$id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Post', true));
            $this->redirect(array('action' => 'index'));
        }

        $post = $this->Post->read(null, $id);
        $this->pageTitle = 'Блоги - Блог ' . $post['Blog']['title'] . ' - Редактирование поста';

        if (!empty($post['Post']) && $post['Post']['user_id'] != $this->authUser['userid'])
        {
            $this->redirect(array('action' => 'view', 'controller' => 'blogs', $post['Post']['blog_id']));
        }


        if (!empty($this->data))
        {
            //now lets parse the tags
            $this->data['Tag']['Tag'] = $this->Post->Tag->parseTags($this->data['Post']['tags']);
            //TODO: add tag cache

            if ($this->Post->save($this->data))
            {
                $this->Session->setFlash("Пост сохранен.");
                $this->redirect(array('action' => 'view', 'controller' => 'posts', $this->data['Post']['id']));
            }
            else
            {
                $this->Session->setFlash('Не могу сохранить пост');
            }
        }
        if (empty($this->data))
        {
            $this->data = $post;
            $this->data['Post']['tags'] = implode(', ', Set::extract('/Tag/title', $this->data));
        }
        $accesses = $this->Post->accessLevels;
        $this->set(compact('accesses'));
        $this->data['Blocks']['Blog'] = $post['Blog'];
    }

    function delete($id = null)
    {
        if (!$id)
        {
            $this->Session->setFlash(__('Invalid id for Post', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Post->recursive = -1;
        $post = $this->Post->read(null, $id);

        if (!empty($post['Post']) && $post['Post']['user_id'] != $this->authUser['userid'])
        {
            $this->redirect(array('action' => 'view', 'controller' => 'blogs', $post['Post']['blog_id']));
        }

        if ($this->Post->del($id))
        {
            $this->Session->setFlash(__('Post deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_index()
    {
        $this->Post->recursive = 0;
        $this->set('posts', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Post.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('post', $this->Post->read(null, $id));
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Post->create();
            if ($this->Post->save($this->data))
            {
                $this->Session->setFlash(__('The Post has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Post could not be saved. Please, try again.', true));
            }
        }
        $tags = $this->Post->Tag->find('list');
        $users = $this->Post->User->find('list');
        $blogs = $this->Post->Blog->find('list');
        $userPictures = $this->Post->UserPicture->find('list');
        $this->set(compact('tags', 'users', 'blogs', 'userPictures'));
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Post', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Post->save($this->data))
            {
                $this->Session->setFlash(__('The Post has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Post could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Post->read(null, $id);
        }
        $tags = $this->Post->Tag->find('list');
        $users = $this->Post->User->find('list');
        $blogs = $this->Post->Blog->find('list');
        $userPictures = $this->Post->UserPicture->find('list');
        $this->set(compact('tags', 'users', 'blogs', 'userPictures'));
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Post', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Post->del($id))
        {
            $this->Session->setFlash(__('Post deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>