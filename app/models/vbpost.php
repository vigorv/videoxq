<?php
/**
 * посты (сообщения) ветки форума обсуждений фильмов (VBulletin)
 *
 */
class Vbpost extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Vbpost';

    public $useTable = 'post'; //имя таблицы в вструктуре VB
    public $primaryKey = 'postid'; //имя поля первичного ключа

    public function getFilmsCommentsCnt($userId = 0)
    {
    	$res = 0;
    	$userId = intval($userId);
//ДОБАВИТЬ КЭШИРОВАНИЕ НА 1 МИНУТУ
    	if (!empty($userId))
    	{
			$cnt = Cache::read('Office.commentcnt', 'office');
			if (!$cnt)
			{
	    	    $sql = 'SELECT post.userid as uid, COUNT(post.postid) as cnt, post.username FROM post
	    	    	INNER JOIN thread ON (thread.threadid = post.threadid AND thread.forumid = ' . Configure::read('forumId') . ')
	    	    	WHERE post.visible=1 AND post.username <> "MediaRobot" AND post.username <> "Igorm85"
	    	    		AND post.dateline > UNIX_TIMESTAMP("' . _START_LOTTERY_PERIOD_ . '")
	    	    		AND post.dateline < UNIX_TIMESTAMP("' . _FIN_LOTTERY_PERIOD_ . '")
	    	    	GROUP BY post.userid ORDER BY cnt DESC';
		        $cnt = $this->query($sql);
				Cache::write('Office.commentcnt', $cnt, 'office');
			}
//pr($cnt);
	        if (!empty($cnt))
	        {
	        	$res = array();//СКЛАДЫВАЕМ ТОП5 (5м индексом идет результат данного юзера)
	        	foreach ($cnt as $key => $value)
	        	{
	        		if ($key < 5)
	        		{
	        			$res[] = array($key + 1, $value[0]['cnt'], $value['post']['username']);
	        		}
	        		if ($value['post']['uid'] == $userId)
	        		{
		        		$res[5] = array($key + 1, $value[0]['cnt'], $value['post']['username']);
	        		}
	        		if (count($res) > 5)
	        		{
	        			//break;
	        		}
	        	}
	        }
    	}
    	return $res;
    }
}
