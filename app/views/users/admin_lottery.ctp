<?php
if (!empty($statInfo))
{
//pr($statInfo);
	echo'<table border="1">';
	foreach ($statInfo as $sInfo)
	{
		echo '<tr>
			<td>' . $sInfo['user']['username'] . '</td>
			<td>' . $sInfo['user']['email'] . '</td>
			<td>' . date('Y-m-d H:i', strtotime($sInfo['userlotteries']['registered'])) . '</td>
			<td>' . abs($sInfo['userlotteries']['winner']) . '</td>
			<td>' . ($sInfo[0]['cnt'] - 1) . ((!empty($sInfo[0]['cnt2'])) ? '(активных ' . $sInfo[0]['cnt2'] . ')' : '' ). '</td>
		</tr>';
	}
	echo '</table>';
/*
Имя	(ссылка на профиль)
Email	(ссылка на профиль)
Кого пригласил	(ссылки на профили)
Комментариев	(ссылка на форум)
Фраза
Место	(abs)
*/
}

if (!empty($lotteryLst))
{
	//pr($lotteryLst);
	echo'<ul>';
	foreach ($lotteryLst as $l)
	{
		echo '<li><a href="/admin/users/lottery/' . $l['Lottery']['id'] . '">' . $l['Lottery']['hd'] . '</a></li>';
	}
	echo'</ul>';
}
