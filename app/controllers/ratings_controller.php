<?php
class RatingsController extends AppController
{

    var $name = 'Ratings';
    var $helpers = array('Html' , 'Form');
    var $uses = array('MediaRating', 'Rating', 'RatingHit');

    function vote($objectId = null, $vote = null, $type = 'film')
    {
        $this->layout = 'ajax';

        if (!$objectId || !$vote)
            return;

        $rating = $this->MediaRating->find(array('object_id' => $objectId, 'type' => $type));

        $cookie = $this->Cookie->read('Voting.' . $type);
        if ($cookie && isset($cookie[$objectId]) && $cookie[$objectId] > (time() - 24*60*60))
        {
            $rating = round($rating['MediaRating']['rating']);
            $this->set('rating', $rating);
            return;
        }

        $cookie[$objectId] = time();
        $this->Cookie->write('Voting.' . $type, $cookie, true, '+1 day');


        if (!$rating)
        {
            $rating = array('MediaRating' => array('object_id' => $objectId,
                                                   'type' => $type,
                                                   'num_votes' => 1,
                                                   'rating' => $vote));
        }
        else
        {
            $rating['MediaRating']['rating'] =
                ($rating['MediaRating']['rating'] * $rating['MediaRating']['num_votes'] + $vote)
                 / ($rating['MediaRating']['num_votes'] + 1);
            $rating['MediaRating']['num_votes']++;

        }
        $this->MediaRating->save($rating);
        $rating = round($rating['MediaRating']['rating']);
        $this->set('rating', $rating);

    }

    function admin_index()
    {
        $this->Rating->recursive = 0;
        $this->set('ratings', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Rating.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('rating', $this->Rating->read(null, $id));
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Rating->create();
            if ($this->Rating->save($this->data))
            {
                $this->Session->setFlash(__('The Rating has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Rating could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Rating', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Rating->save($this->data))
            {
                $this->Session->setFlash(__('The Rating has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Rating could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Rating->read(null, $id);
        }
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Rating', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Rating->del($id))
        {
            $this->Session->setFlash(__('Rating deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>