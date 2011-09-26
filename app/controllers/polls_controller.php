<?php
class PollsController extends AppController
{
    var $name = 'Polls';
    var $helpers = array('Html', 'Form');
	public $uses = array('Poll', 'PollOther');

    /**
     * модель таблицы голосований
     *
     * @var AppModel
     */
    public $Poll;

    /**
     * модель таблицы вариантов "другое"
     *
     * @var AppModel
     */
    public $PollOther;

    function vote()
    {

        $id = $this->data['Poll']['id'];
        $poll = $this->Poll->read(null, $id);

        if ($this->Cookie->read('Voting.' . $id) != null)
            $this->redirect($this->data['Poll']['redirect']);


        if (!$poll || ($this->authUser['userid']
            && strpos($poll['Poll']['voters'], ',' . $this->authUser['userid'] . ',') !== false))
            $this->redirect($this->data['Poll']['redirect']);

        $poll['Poll']['votes'] = explode('#', $poll['Poll']['votes']);
        if (!empty($this->data['Poll']['vote']))
        {
	        $vote = $this->data['Poll']['vote'];
	        $lastSelected = false;
	        if (is_array($vote))
	        {
	        	foreach ($poll['Poll']['votes'] as $key => $val)
	        	{
	        		if (!empty($vote[$key + 1]))//($key + 1) - КОМПЕНСИРУЕМ ВСТАВКУ ХОЛОСТОГО ИНДЕКСА
	        		{
	        			$poll['Poll']['votes'][$key]++;
	        			if ($key == count($poll['Poll']['votes']) - 1)
	        			{
	        				$lastSelected = true;//ПОСЛЕДНИЙ ВЫБОР ОТМЕЧЕН
	        			}
	        		}
	        	}
	        }
	        else
	        {
	        	$vote--;//КОМПЕНСИРУЕМ ВСТАВКУ ХОЛОСТОГО ИНДЕКСА
	        	$poll['Poll']['votes'][intval($vote)]++;
	        	if ($vote == count($poll['Poll']['votes']) - 1)
				{
					$lastSelected = true;//ПОСЛЕДНИЙ ВЫБОР ОТМЕЧЕН
				}
	        }

	        $poll['Poll']['votes'] = implode('#', $poll['Poll']['votes']);
	        $poll['Poll']['total_votes']++;

	        if ($this->authUser['userid'])
	        {
	            if (empty($poll['Poll']['voters']))
	                $poll['Poll']['voters'] = ',';
	            $poll['Poll']['voters'] .= $this->authUser['userid'] . ',';
	        }
	        $this->Poll->save($poll);
	        if (!empty($this->data['Poll']['other']) && $lastSelected)
	        {
	        	$this->PollOther->create();
	        	$otherInfo = array('PollOther' => array(
	        			'user_id' => (empty($this->authUser['userid']) ? 0 : $this->authUser['userid']),
	        			'poll_id' => $poll['Poll']['id'],
	        			'txt'		=> $this->data['Poll']['other']
	        		)
	        	);
	        	$this->PollOther->save($otherInfo);
	        }
	        Cache::delete('Block.mainVoting');
	        $this->Cookie->write('Voting.' . $id, $vote, true, '+1 month');
        }
        $this->redirect($this->data['Poll']['redirect']);
    }


    function admin_index()
    {
        $this->Poll->recursive = 0;
        $this->set('polls', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Poll.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('poll', $this->Poll->read(null, $id));
    }

    function admin_add()
    {
        if (! empty($this->data))
        {
        	if (empty($this->data['Poll']['id']))
            	$this->Poll->create();

            $votes = array();
            foreach ($this->data['Poll']['answers'] as $key => $answer)
            {
                if (empty($answer))
                {
                   unset($this->data['Poll']['answers'][$key]);
                   continue;
                }
                $votes[] = 0;
            }


            $this->data['Poll']['votes'] = implode('#', $votes);
            $this->data['Poll']['answers'] = serialize($this->data['Poll']['answers']);
            $this->data['Poll']['total_votes'] = 0;

            if ($this->Poll->save($this->data))
            {

                Cache::delete('Block.mainVoting');
                $this->Session->setFlash(__('The Poll has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Poll could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Poll', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            $votes = explode('#', $this->data['Poll']['votes']);
            foreach ($this->data['Poll']['answers'] as $key => $answer)
            {
                if (empty($answer))
                {
                   unset($this->data['Poll']['answers'][$key]);
                   continue;
                }
                if (!isset($votes[$key]))
                    $votes[$key] = 0;
            }

            $this->data['Poll']['votes'] = implode('#', $votes);
            $this->data['Poll']['answers'] = serialize($this->data['Poll']['answers']);
            $this->data['Poll']['multiple'] = intval($this->data['Poll']['multiple']);

            if ($this->Poll->save($this->data))
            {
                Cache::delete('Block.mainVoting');
                $this->Session->setFlash(__('The Poll has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Poll could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Poll->read(null, $id);
            $this->set('answers', unserialize($this->data['Poll']['answers']));
        }
    }

    function admin_delete($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid id for Poll', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Poll->del($id))
        {
            $this->Session->setFlash(__('Poll deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>