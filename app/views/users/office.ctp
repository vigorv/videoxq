<div class="contentColumns">
<h2><?php __('Office'); ?></h2>
<?php
	echo '<h3>' . __('Hi', true) . ', ' , $authUser['username'] . '!</h3>';

	$lotteryContent = ''; $clearTakePart = false;
	if (!empty($curLottery))
	{
		$takePartLink = '<p><a href="/users/lottery">' . __('Participate in the lottery', true) . '</a></p>';
	}
	else
	{
		$takePartLink = '';
	}
	if ((!empty($userLotteries)) && !empty($lotteryList))
	{
		$curWinLot = '';
		foreach ($lotteryList as $lL)
		{
			$oneLine = false; $status = '';
			foreach ($userLotteries as $uL)
			{
				if ($uL['Userlottery']['lottery_id'] == $lL['Lottery']['id'])
				{
//					$status = __('complete', true);
					if (!empty($curLottery))
					{
						if (empty($uL['Userlottery']['inv_user_id']) && $curLottery['Lottery']['id'] == $uL['Userlottery']['lottery_id'])
						{
							$clearTakePart = true;
							$status = __('Acting lottery', true);
						}
					}

					if ($uL['Userlottery']['winner'])
					{
						$status .= ' <font color="red">' . __('you win', true) . '!</font> ' . __('lot of winning', true) . ' - ' . $uL['Userlottery']['unique_code'];
						if ($uL['Userlottery']['winner'] > 0) //КОГДА ПРИЗ ВРУЧЕН WINNER ПРИСВОИТЬ -1
						{
//							$status .='  <a href="/users/lottery/' . $uL['Userlottery']['lottery_id'] . '/getprize">' . __('get the prize', true) . '</a>';
						}
					}
					if (!$oneLine)
						$lotteryContent .= '"<a href="/users/lottery/' . $lL['Lottery']['id'] . '">' . $lL['Lottery']['hd'] . '</a>" ';

					switch(abs($uL['Userlottery']['winner']))
					{
						case 1:
							$registered = strtotime($uL['Userlottery']['registered']);
							$letterTime = $registered + 3600 * 24 * 5 + 3600;
							$days = $letterTime - time();
							$daysStr = '';
							if ($days > 3600 * 24)
							{
						    	$size = $days;
						    	$d = 24*60*60;
						    	$o = $size % $d;
						    	$d = intval($size/$d);
						    	if ($d) $daysStr = $app->pluralForm($d, array(__('day', true), __('daya', true), __('days', true)));

								$daysStr = 'которая придет на ваш электронный адрес через ' . $daysStr;
							}
							else
							{
								if($days > 0)
									$daysStr = 'которая придет на ваш электронный адрес сегодня';
								else
									$daysStr = 'которая пришла на ваш электронный адрес';
							}

							$winLot =  'Вы ' . $uL['Userlottery']['id'] . 'й! Вы выиграли сувенир! Приходите в ЦВП и скажите кодовую фразу, ' . $daysStr . '!';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($uL['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
						case 2:
							//$winLot =  'Вы ' . $uL['Userlottery']['id'] . 'й! Поздравляем! Вы получили статус VIP в подарок!';
							$winLot =  'Поздравляем! Вы получили статус VIP в подарок!';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($uL['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
						case 3:
							$winLot =  'Поздравляем! Вы пригласили больше всех друзей! Вы выиграли главный приз недели';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($uL['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
						case 4:
							$winLot =  'Поздравляем! Вы оставили больше всех комментариев в обсуждениях фильмов!';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($uL['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
					}
					$oneLine = true;
				}
			}
			$lotteryContent .= $status . '<br />';

		}
		if (($clearTakePart) && (!empty($takePartLink)))
			$takePartLink = '<p>' . __('You are already in lottery', true) . '!</p>';
		$lotteryContent .= '</p>';
	}

	if (!$clearTakePart && !empty($curLottery) && !$curLottery['Lottery']['hidden'])
	{
		echo '<div class="attention">' . __('Attention! Lottery!', true) . ' <b>"' . $curLottery['Lottery']['hd'] . '"</b>' . $takePartLink . '</div><br />';
	}

	if ($authUser['usergroupid'] == 3) //NOT CONFIRMED
	{
		echo'
			<div class="bordered">
			<h2>' . __('Registration', true) . '</h2>
			<h4>' . __('Registration is not confirmed', true) . '</h4>
			<p><a href="/users/confirm">' . __('Confirm registration', true) . '</a></p>
			</div>
		';
	}

	echo'
		<div class="bordered">
		<h2>' . __('User agreement', true) . '</h2>
	';
	if (!$authUser['agree']) //NOT AGREE
	{
		echo '<h4>' . __('You are not accepted user agreement', true) . '</h4><p>';
	    echo __('WARNING! Pay for the service V.I.P. Access can be by taking a', true) . ' <a target="_blank" href="/pages/agreement">' . __('user agreement', true) . '</a>.';
	    echo'<br /><a href="/pays/agree" onclick=\'return confirm("' . __("Are You sure?", true) . '");\'>' . __('I accept the agreement', true) . '</a></p>';
	}
	else
	{
		echo '<h4>' . __('You are accepted user agreement', true) . '</h4><p>';
	    echo '<a target="_blank" href="/pages/agreement">' . __('user agreement', true) . '</a>.';
	    echo '<br /><a href="/users/drop" onclick=\'return confirm("' . __("Are You sure?", true) . '");\'>' . __('Reject and delete my account', true) . '</a></p>';
	}
	echo'</div>';

	if ($authUser['userid'] > 0)
	{
		echo'
			<div class="bordered">
			<h2>' . __('Geography', true) . '</h2>
		';
	    $geoPlace = '<h4>' . __('Your geographical location', true);
	    if (!empty($geoInfo['Geoip']['region_id']))
	    {
	    	$geoPlace .= ' - ' . implode(' ', array($geoInfo['city'], $geoInfo['region'])) . '. ';;
	    }
	    else
	    {
	    	$geoPlace .= ' ' . __('not identified', true) . '. ';
	    }

	    if (!empty($authUser['userid']))
	    {
	    	$adminLink = '<a href="/media/geoerr">' . __("contact administrator", true) . '</a>';
	    }
	    else
	    {
	    	$adminLink = __("contact administrator", true);
	    }
	    echo $geoPlace . '<br />' . __("If your geographical location is incorrect", true) . ', ' . $adminLink . '.</h4>
	    <p>' . __('Depends on this is available for download by you of certain films', true) . '</p>';

	    echo '</div>';
	}

	if (!empty($curLottery))
	{
		echo'
			<div class="bordered">
			<h2>' . __('Acting lottery', true) . '</h2>
		';
		echo '<h4>' . __('Acting lottery', true) . ' - "<i><a href="/users/lottery">' . $curLottery['Lottery']['hd'] . '</a></i>"</h4>';

		echo 'До конца акции ' . $app->timeFormat(strtotime($curLottery['Lottery']['finished']) - time());

		echo $takePartLink;

		if (!empty($curWinLot))
		{
			echo '<div class="attention">' . $curWinLot . '</div><br />';
		}

		$td1 = '';
		if (!empty($userPostsCnt))
		{
			if (!empty($userPostCnt[5]))
				echo '<p>Коментов к фильмам: ' . $userPostsCnt[5][1] . ' (это ' . $userPostsCnt[5][0] . ' место)</p>';
			else
				echo '<p>Вы не оставляли комментарии к фильмам</p>';
			if (count($userPostsCnt) > 0)
			{
				$td1 .= '<ol><b>Первые по комментариям:</b>';
				for ($i = 0; $i < 5; $i++)
				{
					if (!empty($userPostsCnt[$i]))
					{
						$td1 .= '<li>' . $userPostsCnt[$i][2] . ' - ' . $userPostsCnt[$i][1] . '</li>';
					}
				}
				$td1 .= '</ol>';
			}
		}
		else
			echo '<p>Вы не оставляли комментарии к фильмам</p>';

		$td2 = '';
		if (!empty($userInvitesCnt))
		{
			if (!empty($userInvitesCnt[5]))
				echo '<p>Количество ваших приглашенных: ' . $userInvitesCnt[5][1] . ' (это ' . $userInvitesCnt[5][0] . ' место)</p>';
			else
				echo '<p>Вы никого не пригласили участвовать</p>';
			if (count($userInvitesCnt) > 0)
			{
				$td2 .= '<ol><b>Первые по приглашенным:</b>';
				for ($i = 0; $i < 5; $i++)
				{
					if (!empty($userInvitesCnt[$i]))
					{
						$td2 .= '<li>' . $userInvitesCnt[$i][2] . ' - ' . $userInvitesCnt[$i][1] . '</li>';
					}
				}
				$td2 .= '</ol>';
			}
		}
		else
			echo '<p>Вы никого не пригласили участвовать</p>';
?>
	<table cellpadding="0" cellspacing="5" border="0"><tr valign="top"><td width="50%">
<?php
		echo $td1;
?>
</td><td>
<?php
		echo $td2;
?>
</td></tr></table>
</div>
<?php
	}

	if (!empty($lotteryContent))
	{
		echo'
			<div class="bordered">
			<h2>' . __('Participation in lotteries', true) . '</h2>
		';
		echo $lotteryContent;
		echo'</div>';
	}

	if (!empty($payList))
	{
		echo'
			<div class="bordered">
			<h2>' . __('Last payments', true) . '</h2>
		';

		echo'<ul>';
		foreach ($payList as $l)
		{
			switch ($l['Pay']['paysystem'])
			{
				case _PAY_PAYPAL_:
					$valute = Configure::read('paypal.currency');
				break;
				case _PAY_SMSCOIN_:
					$valute = 'у.е.';
				break;
				case _PAY_ASSIST_:
					$valute = 'RUR';
				break;
				default:
					$valute = 'WMR';
			}

			echo '<li>№ ' . $l["Pay"]['id'] . ' ' . __('date', true) . ' ' . date('d.m.y H:i', $l["Pay"]['paydate']) . ' (' . $l["Pay"]['summ'] . ' ' . $valute . ')';
			if ($l["Pay"]['findate'] > time()) echo ' - ' . __('Paid by', true) . ' ' . date('d.m.y H:i', $l["Pay"]['findate']) . '</li>';
		}
		echo '</ul></div>';
	}
/*
pr($userLotteries);
pr($lotteryList);
pr($userMessages);
pr($userVideos);
*/
?>
</div>