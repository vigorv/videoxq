<?php
class CommentsController extends AppController
{

    var $name = 'Comments';
    var $helpers = array('Html' , 'Form');
    var $components = array('RequestHandler');

    function index()
    {
        $this->Comment->recursive = 0;
        $this->set('comments', $this->paginate());
    }

    function view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Comment.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('comment', $this->Comment->read(null, $id));
    }

    function add($postId = null, $parentId = null)
    {

        if (!$this->authUser['userid'])
            return;

        if ($this->RequestHandler->isAjax())
        {
            $this->layout = 'ajax';
            if (empty($this->data))
            {
                $this->data['Comment']['post_id'] = $postId;
                if ($parentId)
                    $this->data['Comment']['parent_id'] = $parentId;
                $this->set('parentId', $parentId);
                $this->render('add_ajax');
                return;
            }

            $this->Comment->create();
            $this->data['Comment']['user_id'] = $this->authUser['userid'];
            $this->Comment->Behaviors->detach('Tree');
            $this->Comment->Behaviors->attach('Tree', array('scope' => 'post_id = ' . $this->data['Comment']['post_id']));
            if ($this->Comment->save($this->data))
            {
                $newId = $this->Comment->getInsertID();
                $this->Session->setFlash('Комментарий добавлен.');
                $this->redirect('/posts/view/' . $this->data['Comment']['post_id'] . '#comm' . $newId);
            }
            $this->render('add_ajax');
            return;
        }

        if (! empty($this->data))
        {
            $this->Comment->create();
            $this->data['Comment']['user_id'] = $this->authUser['userid'];

            $this->Comment->Behaviors->detach('Tree');
            $this->Comment->Behaviors->attach('Tree', array('scope' => array('post_id' => $this->data['Comment']['post_id'])));
            if ($this->Comment->save($this->data))
            {
                $newId = $this->Comment->getInsertID();
                $this->Session->setFlash('Комментарий добавлен.');
                $this->redirect('/posts/view/' . $this->data['Comment']['post_id'] . '#comm' . $newId);
            }
            else
            {
                $this->Session->setFlash(__('The Comment could not be saved. Please, try again.', true));
            }
        }

        $this->set('parentId', $parentId);
        $this->data['Comment']['post_id'] = $postId;
        if ($parentId)
            $this->data['Comment']['parent_id'] = $parentId;
    }

    //    function edit($id = null)
    //    {
    //        if (! $id && empty($this->data))
    //        {
    //            $this->Session->setFlash(__('Invalid Comment', true));
    //            $this->redirect(array('action' => 'index'));
    //        }
    //        if (! empty($this->data))
    //        {
    //            if ($this->Comment->save($this->data))
    //            {
    //                $this->Session->setFlash(__('The Comment has been saved', true));
    //                $this->redirect(array('action' => 'index'));
    //            }
    //            else
    //            {
    //                $this->Session->setFlash(__('The Comment could not be saved. Please, try again.', true));
    //            }
    //        }
    //        if (empty($this->data))
    //        {
    //            $this->data = $this->Comment->read(null, $id);
    //        }
    //        $posts = $this->Comment->Post->find('list');
    //        $users = $this->Comment->User->find('list');
    //        $userPictures = $this->Comment->UserPicture->find('list');
    //        $parents = $this->Comment->Parent->find('list');
    //        $this->set(compact('posts', 'users', 'userPictures', 'parents'));
    //    }


    function delete($id = null)
    {
        if (!$id)
        {
            $this->Session->setFlash(__('Invalid id for Comment', true));
            $this->redirect(array('action' => 'index'));
        }

        //$this->Comment->recursive = -1;
        $comment = $this->Comment->read(null, $id);
        if (!empty($comment['Comment']) && $comment['Comment']['user_id'] != $this->authUser['userid']
            && $comment['Post']['user_id'] != $this->authUser['userid'])
        {
            $this->redirect('/posts/view/' . $comment['Comment']['post_id']);
        }

        $this->Comment->Behaviors->detach('Tree');
        $this->Comment->Behaviors->attach('Tree', array('scope' => array('post_id' => $comment['Comment']['post_id'])));

        if ($this->Comment->del($id))
        {
            $this->Session->setFlash("Комментарий удален");
            $this->redirect('/posts/view/' . $comment['Comment']['post_id']);
        }
    }

    function admin_index()
    {
        $this->Comment->recursive = 0;
        $this->set('comments', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Comment.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('comment', $this->Comment->read(null, $id));
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Comment->create();
            if ($this->Comment->save($this->data))
            {
                $this->Session->setFlash(__('The Comment has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Comment could not be saved. Please, try again.', true));
            }
        }
        $posts = $this->Comment->Post->find('list');
        $users = $this->Comment->User->find('list');
        $userPictures = $this->Comment->UserPicture->find('list');
        $parents = $this->Comment->Parent->find('list');
        $this->set(compact('posts', 'users', 'userPictures', 'parents'));
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Comment', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Comment->save($this->data))
            {
                $this->Session->setFlash(__('The Comment has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Comment could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Comment->read(null, $id);
        }
        $posts = $this->Comment->Post->find('list');
        $users = $this->Comment->User->find('list');
        $userPictures = $this->Comment->UserPicture->find('list');
        $parents = $this->Comment->Parent->find('list');
        $this->set(compact('posts', 'users', 'userPictures', 'parents'));
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Comment', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Comment->del($id))
        {
            $this->Session->setFlash(__('Comment deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>