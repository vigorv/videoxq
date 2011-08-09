<?php
class Userlottery extends AppModel {

	public $name = 'Userlottery';

	public function getActiveInvites($lotteryId, $userId)
	{
   	    $sql = 'SELECT COUNT(userlotteries.id) as cnt FROM userlotteries
   	    LEFT JOIN user ON (user.userid = userlotteries.bid_user_id)
   	    INNER JOIN user as u2 ON (u2.userid=userlotteries.user_id AND u2.usergroupid != 3
   	    	AND u2.lastvisit - u2.joindate > 1000)
   	    WHERE userlotteries.lottery_id=' . $lotteryId . ' AND userlotteries.bid_user_id=' . $userId . ' GROUP BY userlotteries.bid_user_id ORDER BY cnt DESC';
        $cnt = $this->query($sql);
        return $cnt;
	}

	/**
	 * получить статистику по лотерее
	 *
	 * @param int $id
	 * @param mixed $args
	 */
	public function getLotteryStat($id, $args)
	{
   	    $sql = 'SELECT COUNT(userlotteries.id) as cnt, userlotteries.winner, userlotteries.registered,
   	    		userlotteries.user_id as uid, user.username, user.email, userlotteries.fraze
   	    		 FROM userlotteries LEFT JOIN user ON (user.userid = userlotteries.user_id)
   	    		 WHERE userlotteries.lottery_id=' . $id . '
   	    		 GROUP BY userlotteries.user_id ORDER BY cnt DESC';
        $lst = $this->query($sql);
        return $lst;
	}

	/**
	 * получить список победителей лотереи
	 *
	 * @param int $lotteryId
	 * @return mixed
	 */
	public function getWinners($lotteryId)
	{
		$lst = Cache::read('Office.winners', 'office');
		if (!$lst)
		{
    	    $sql = 'SELECT userlotteries.id, userlotteries.winner, userlotteries.registered, userlotteries.user_id as uid, user.username, user.email FROM userlotteries LEFT JOIN user ON (user.userid = userlotteries.user_id AND user.usergroupid <> 3) WHERE userlotteries.winner > 0 AND userlotteries.inv_user_id = 0 AND userlotteries.lottery_id=' . $lotteryId . ' GROUP BY userlotteries.user_id';
	        $lst = $this->query($sql);
			Cache::write('Office.winners', $lst, 'office');
		}
		return $lst;
//pr($lst);
	}

	/**
	 * получить список участников лотереи зарегистрировавшихся по прошествии срока
	 *
	 * @param int $lotteryId
	 * @param int $delay - срок в секундах
	 * @return mixed
	 */
	public function getDelayers($lotteryId, $delay)
	{
		$lst = Cache::read('Office.delayers', 'office');
		if (!$lst)
		{
    	    $sql = 'SELECT userlotteries.id, userlotteries.registered, userlotteries.user_id as uid FROM userlotteries WHERE userlotteries.winner = 0 AND userlotteries.inv_user_id = 0 AND userlotteries.lottery_id=' . $lotteryId . ' AND userlotteries.registered < FROM_UNIXTIME(UNIX_TIMESTAMP() - ' . $delay . ')';
	        $lst = $this->query($sql);
			Cache::write('Office.delayers', $lst, 'office');
		}
		return $lst;
//pr($lst);
	}

    public function getInvitesCnt($lotteryId = 0, $userId = 0)
    {
    	$res = 0;
    	$userId = intval($userId);
//ДОБАВИТЬ КЭШИРОВАНИЕ НА 1 МИНУТУ
    	if (!empty($userId))
    	{
			$cnt = Cache::read('Office.invitescnt', 'office');
			if (!$cnt)
			{
	    	    $sql = 'SELECT userlotteries.bid_user_id as uid, COUNT(userlotteries.id) as cnt, user.username FROM userlotteries
	    	    	LEFT JOIN user ON (user.userid = userlotteries.bid_user_id)
	    	    	INNER JOIN user as u2 ON (u2.userid=userlotteries.user_id AND u2.usergroupid != 3 AND u2.lastvisit - u2.joindate > 1000)
	    	    	WHERE userlotteries.bid_user_id > 0 AND userlotteries.lottery_id=' . $lotteryId . '
	    	    		AND userlotteries.registered > "' . _START_LOTTERY_PERIOD_ . '"
	    	    		AND userlotteries.registered < "' . _FIN_LOTTERY_PERIOD_ . '"
	    	    	GROUP BY userlotteries.bid_user_id ORDER BY cnt DESC';
		        $cnt = $this->query($sql);
				Cache::write('Office.invitescnt', $cnt, 'office');
//echo $sql;
//pr($cnt);
			}
	        if (!empty($cnt))
	        {
	        	$res = array();//СКЛАДЫВАЕМ ТОП5 (5м индексом идет результат данного юзера)
	        	foreach ($cnt as $key => $value)
	        	{
	        		if ($key < 5)
	        		{
	        			$res[] = array($key + 1, $value[0]['cnt'], $value['user']['username']);
	        		}
	        		if ($value['userlotteries']['uid'] == $userId)
	        		{
		        		$res[5] = array($key + 1, $value[0]['cnt'], $value['user']['username']);
	        		}
	        		if (count($res) > 5)
	        		{
	        			//break;
	        		}
	        	}
	        }
    	}
//pr($res);
    	return $res;
    }
}