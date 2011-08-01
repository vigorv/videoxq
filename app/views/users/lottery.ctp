<div class="contentColumns">
<?php
if (!empty($lotteryData))
{
	echo '<h2>' . $lotteryData['Lottery']['hd'] . '</h2>';
?>
	<h3>Сроки проведения с <?php $dArr = explode(' ', $lotteryData['Lottery']['created']); echo $dArr[0]; ?> по <?php $dArr = explode(' ', $lotteryData['Lottery']['finished']); echo $dArr[0]; ?></h3>

<?php
	if (date('Y-m-d H:i:s') > $lotteryData['Lottery']['finished'])
	{
		echo '<h3>Акция закончена</h3>';
	}
	else
	{
		echo '<h3>До конца акции ' . $app->timeFormat(strtotime($curLottery['Lottery']['finished']) - time()) . '</h3>';
	}
?>
	<h3>Правила участия</h3>
<?php
	echo $lotteryData['Lottery']['txt'];
	$number = count($inviteUsers);

	$isRegistered = false;
	if (!empty($lotteryChances))
	{
		foreach ($lotteryChances as $lC)
		{
			if (empty($lC['Userlottery']['inv_user_id']))//У "СВОЕЙ" РЕГИСТРАЦИИ ЭТО ПОЛЕ ВСЕГДА ПУСТОЕ
			{
				$isRegistered = true;
				break;
			}
		}
	}

if (!empty($authUser['userid']) && !$isRegistered && ($lotteryData['Lottery']['id'] == $curLottery['Lottery']['id']))
{
	if (empty($dup))
	{
	//ЗНАЧИТ МОЖЕТ УЧАСТВОВАТЬ И НЕ ЗАРЕГИСТРИРОВАН
?>
<br /><br /><div class="bordered">
<form name="lotteryform" method="post" action="/users/lottery">
	<h2>Форма регистрации в розыгрыше</h2>
	<h4>Email пригласившего вас пользователя</h4>
	<input type="hidden" name="lottery_id" value="<?php echo $curLottery['Lottery']['id'];?>" />
	<input type="text" name="bid_username" value="" /> (необязательное поле)<br />
	<input style="margin-top:10px;" type="submit" value="Хочу участвовать!">
</form></div>
<?php
	}
	else
	{
		echo '<h3>С вашего адреса уже была произведена регистрация участия в розыгрыше. Повторная регистрация невозможна.</h3>';
	}
}
	$chancesContent = '';
	if (!empty($lotteryChances))
	{
		$chancesContent .= '<h4>' . __('Your lots', true) . '</h4><p>';
		$winLot = '';
		foreach ($lotteryChances as $lC)
		{
			$lot = $lC['Userlottery']['unique_code'];
			if (($lC['Userlottery']['inv_user_id'] == 0) && ($lotteryData['Lottery']['id'] == $curLottery['Lottery']['id']))
			{
?>
<div class="bordered">
<form name="lotteryform" method="post" action="/users/lottery/<?php $curLottery['Lottery']['id']?>">
	<h2>Укажите фразу недели</h2>
	<input type="text" name="lottery_fraze" value="<?php echo $lC['Userlottery']['fraze'];?>" /><br />
	<input style="margin-top:10px;" type="submit" value="Отправить">
</form></div>
<?php
			}
			if ($lC['Userlottery']['winner'])
			{
				$lot = '<font color="red">' . $lot . '</font>';
/*
				if ($lC['Userlottery']['winner'] > 0) //КОГДА ПРИЗ ВРУЧЕН WINNER ПРИСВОИТЬ -1
				{
					$lot .=' <a href="/users/lottery/' . $lotteryData['Lottery']['id'] . '/getprize">' . __('get the prize', true) . '</a>';
				}
//*/
				$winLot =  '<h4>' . __('Congratulations! You Win!', true) . ' ' . __('lot of winning', true) . ' ' . $lot . '</h4>';
					switch(abs($lC['Userlottery']['winner']))
					{
						case 1:
							$registered = strtotime($lC['Userlottery']['registered']);
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

							$winLot =  'Вы ' . $lC['Userlottery']['id'] . 'й! Вы выиграли сувенир! Приходите в ЦВП и скажите кодовую фразу, ' . $daysStr . '!';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($lC['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
						case 2:
							//$winLot =  'Вы ' . $lC['Userlottery']['id'] . 'й! Поздравляем! Вы получили статус VIP в подарок!';
							$winLot =  'Поздравляем! Вы получили статус VIP в подарок!';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($lC['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
						case 3:
							$winLot =  'Поздравляем! Вы пригласили больше всех друзей! Вы выиграли главный приз недели';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($lC['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
						case 4:
							$winLot =  'Поздравляем! Вы оставили больше всех комментариев в обсуждениях фильмов!';
							$status = $winLot;
							if (!empty($curLottery['Lottery']['id']) && ($lC['Userlottery']['lottery_id'] == $curLottery['Lottery']['id']))
							{
								$curWinLot = $winLot;
							}
						break;
					}
			}
			$chancesContent .= $lot . '<br />';
		}

		if (!empty($winLot))
		{
			$winLot = '<div class="attention">' . $winLot . '</div><br />';
		}
		//$chancesContent .= $winLot . '</p>';
		$chancesContent .= '</p>';
	}

	if (!empty($curLottery)
		&& ($lotteryData['Lottery']['id'] == $curLottery['Lottery']['id'])
		&& ($lotteryData['Lottery']['created'] < date('Y-m-d H:i:s'))
		&& ($lotteryData['Lottery']['finished'] > date('Y-m-d H:i:s'))
		)
	{

		if (!empty($lotteryChances) && count($lotteryChances) > $number)
		{
			echo $winLot;

			if ($number > 0)
			{
				$titles = array('друг', 'друга', 'друзей');
				echo '<h3>Вы пригласили ' . $number . ' ' . $titles[ ($number%100>4 && $number%100<20)? 2 : min($number%10, 5)]. '</h3>';
			}
			else
			{
	?>
	<h3>Вы никого не пригласили участвовать :(</h3>
	<?php
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
<?php

		}
	}
	echo $chancesContent;

	if (empty($authUser['userid']))
	{
		echo '<br /><br /><h2><a href="/users/register">' . __('Available only to registered users', true) . '</a></h2>';
	}
}
else
{
?>
	<h3>Извините, не в этот раз. Ваш регион не участвует в розыгрыше.</h3>
<?php
}
?>
</div><br />
