<?php
App::import('component', 'BlocksParent');

/**
 * Компонент для получения различонй статы с форума
 *
 */
class BlockStatsComponent extends BlocksParentComponent
{

    var $components = array('Cookie');

    function siteStats($args)
    {
        Cache::config('default');
        if ($stats = Cache::read('Block.siteStats'))
            return $stats;

        $cache = '+1 minute';
        if (isset($args['cache']))
            $cache = $args['cache'];

        $sql = 'SELECT COUNT(postid) AS count FROM post WHERE visible = 1';
        $posts = $this->controller->User->query($sql);
        $sql = 'SELECT COUNT(threadid) AS count FROM thread WHERE visible = 1';
        $threads = $this->controller->User->query($sql);
        $sql = 'SELECT COUNT(id) AS count FROM faq_items';
        $faqs = $this->controller->User->query($sql);
        $users = $this->controller->User->find('count', array('conditions' => 'User.usergroupid != 3 AND User.usergroupid != 4'));

        $stats = array('posts' => $posts[0][0]['count'], 'threads' => $threads[0][0]['count'],
                       'faqs' => $faqs[0][0]['count'], 'users' => $users);

        Cache::config('default');
        Cache::write('Block.siteStats', $stats, $cache);
        return $stats;
    }

    function topPosters($args)
    {
        if ($stats = Cache::read('Block.topPosters'))
            return $stats;

        $cache = '+1 minute';
        if (isset($args['cache']))
            $cache = $args['cache'];

        $limit = 5;
        if (isset($args['limit']))
            $limit = $args['limit'];

        $sql = 'SELECT userid, username, joindate, posts
                FROM user ORDER BY posts DESC LIMIT 0, ' . $limit;
        $stats = $this->controller->User->query($sql);

        Cache::config('default');
        Cache::write('Block.topPosters', $stats, $cache);
        return $stats;
        //pr($users);
    }


    function showPoll()
    {

        if ($data = Cache::read('Block.mainVoting', 'default'))
        {
            $voted = (!empty($data['Poll']['id']) && ($this->Cookie->read('Voting.' . $data['Poll']['id'])) != null) ? true : false;
            $this->controller->set('main_voting_voted', $voted);

            return $data;
        }

        $model = ClassRegistry::init('Poll');
        $data = $model->find('first', array('order' => 'active DESC'));
        if (!empty($data['Poll']))
        {
	        $data['Poll']['answers'] = unserialize($data['Poll']['answers']);
	        $data['Poll']['votes'] = explode('#', $data['Poll']['votes']);
			if ($data['Poll']['active'])
			{
		        $tmp = array();
				$totalVotes = 0;
		        foreach ($data['Poll']['answers'] as $key => $vote)
		        {
		        	$votes = empty($data['Poll']['votes'][$key]) ? 0 : $data['Poll']['votes'][$key];
		        	$totalVotes += $votes;
		        }

		        foreach ($data['Poll']['answers'] as $key => $vote)
		        {
		            $votes = empty($data['Poll']['votes'][$key]) ? 0 : $data['Poll']['votes'][$key];
		            //$tmp[$key]['percent'] = round(($votes * 100) / $data['Poll']['total_votes']);
		            $tmp[$key]['percent'] = round(($votes * 100) / $totalVotes);
		            $tmp[$key]['width'] = round(($votes * 100) / max($data['Poll']['votes']));
		            $tmp[$key]['answer'] = $vote;
		            $tmp[$key]['voters'] = $votes;
		        }
		        $data['Poll']['data'] = $tmp;
		        $voted = ($this->Cookie->read('Voting.' . $data['Poll']['id']) != null) ? true : false;
		        $this->controller->set('main_voting_voted', $voted);
	    	}
	    	else
	    	{
	    		$data['Poll'] = array();
	    	}
        }
    	else
    	{
    		$data['Poll'] = array();
    	}
        Cache::write('Block.mainVoting', $data, array('config' => 'default', 'duration' => '+1 hour'));

        return $data;
    }

}
?>