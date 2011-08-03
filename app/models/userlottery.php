<?php
class Userlottery extends AppModel {

	public $name = 'Userlottery';

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
	    	    $sql = 'SELECT userlotteries.bid_user_id as uid, COUNT(userlotteries.id) as cnt, user.username FROM userlotteries LEFT JOIN user ON (user.userid = userlotteries.bid_user_id AND user.usergroupid <> 3) WHERE userlotteries.bid_user_id > 0 AND userlotteries.lottery_id=' . $lotteryId . ' GROUP BY userlotteries.bid_user_id ORDER BY cnt DESC';
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