<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: ' . date('r', time() - 100000));
$content = '
	<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
	<tr><td height="100%">
';
$tail = '</td></tr></table>';
switch ($pageName)
{
	case "favorites":
		$content .= '
			<div id="innerDiv">
		';
		foreach($bookmarks as $b)
		{
			$content .= '<div class="favlinkdiv"><nobr>';
			$favId = '';
			if (!empty($b['Bookmark']['id']))
			{
				$content .= '<a href="#" onclick="return rocketDeleteFavorite(' . $b['Bookmark']['id'] . ');" alt="удалить" title="удалить"><img src="/img/icons/delete.gif" width="13" alt="удалить" title="удалить" /></a>&nbsp;';
				$content .= '<a href="#" onclick="return rocketEditFavorite(' . $b['Bookmark']['id'] . ');" alt="редактировать" title="редактировать"><img src="/img/icons/edit.gif" width="13" alt="редактировать" title="редактировать" /></a>&nbsp;';
				$favId = ' id="fav' . $b['Bookmark']['id'] . '"';
			}
			$content .= '<a' . $favId . ' href="' . $b['Bookmark']['url'] . '" alt="' . htmlspecialchars($b['Bookmark']['title']). '" title="' . htmlspecialchars($b['Bookmark']['title']). '">' . $b['Bookmark']['title'] . '</a></nobr></div>';
		}
		$content .= '</div>';
	break;

	case "chat":
		$msg = '';
		$msg2 = ''; //ДЛЯ ВСТАВКИ СТРОК В ТАБЛИЦУ
		if (!empty($chatMessages))
		{
			$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			$ysday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
			$msg = '<table id="chatlinestable" width="100%" cellspacing="2" cellpadding="1" border="0"><tbody>';
			$msg2 = ''; //ДЛЯ ВСТАВКИ СТРОК В ТАБЛИЦУ
			$chatTr = '';
			foreach ($chatMessages as $m)
			{
				$dFormat = 'd-m H:i';
				if ($m['CybChat']['dateline'] > $ysday)
					$dFormat = 'вчера в H:i';
				if ($m['CybChat']['dateline'] > $today)
					$dFormat = 'сегодня в H:i';

				$dt = '<nobr>[' . date($dFormat, $m['CybChat']['dateline']) . ']</nobr>';
				$msg .= '<tr><td>' . $dt . '</td>';

				$userAlt = array();
				$userTag1 = array();
				$userTag2 = array();
				$membergroupids = (empty($m['User']['displaygroupid'])) ? 0 : $m['User']['displaygroupid'];
				if (!empty($membergroupids))
				{
					//$groups = explode(',', $membergroupids);
					//foreach ($groups as $g)
					$g = $membergroupids;
					{
						$g = intval($g);
						if (empty($vbGroups[$g])) continue;

						$userAlt[] = $vbGroups[$g]['usertitle'];
						$userTag1[] = $vbGroups[$g]['opentag'];
						$userTag2[] = $vbGroups[$g]['closetag'];
					}
				}
				$userAlt = htmlspecialchars(strip_tags(implode(', ', $userAlt)));
				$userTag1 = implode('', $userTag1);
				$userTag2 = implode('', $userTag2);

				$us = ((empty($m['User']['username'])) ? '' : '<a href="/forum/member.php?u=' . $m['User']['userid'] . '" alt="' . $userAlt . '" title="' . $userAlt . '">' . $userTag1 . $m['User']['username']) . $userTag2 . '</a>';
				$msg .= '<td>' . $us . '</td>';

				$message = $m['CybChat']['message'];
				$props = unserialize($m["CybChat"]["textprop"]);
				if (!empty($props["bold"]) && ($props["bold"] == 'bold'))
				{
					$message = '<b>' . $message . '</b>';
				}
				if (!empty($props["italic"]) && ($props["italic"] == 'italic'))
				{
					$message = '<i>' . $message . '</i>';
				}
				if (!empty($props["underline"]) && ($props["underline"] == 'underline'))
				{
					$message = '<u>' . $message . '</u>';
				}
				$message = strtr($message, $allSmiles);
				$color = (empty($props["color"])) ? "black" : $props["color"];

				$ms = nl2br('<font color="' . $color . '">' . $message . '</font>');
				$msg .= '<td width="100%">' . $ms . '</td></tr>';

				$msg2 .= $chatTr . $dt . '[CHAT_TD]' . $us . '[CHAT_TD]' . $ms;
				$chatTr = '[CHAT_TR]';
			}
			$msg .= '</tbody></table>';
		}

		if (!empty($newMsg)) //значит выводим только последние
		{
			$content = $msg2;
			$tail = '';
		}
		else
		{
			$blocked = ($authUser['membergroupids'] == 8) || in_array(8, explode(',', $authUser['membergroupids']));
			if (($authUser['userid']) && !$blocked)
			{
				$form = '
				<form name="chatform" id="chatform" method="post" onsubmit="return chatSubmit()">
				<hr /><table>
				<input type="hidden" name="bold" value="" />
				<input type="hidden" name="color" value="" />
				<input type="hidden" name="italic" value="" />
				<input type="hidden" name="underline" value="" />
				<tr valign="top">
					<td width="100%">
						<textarea id="chatmemo" name="message"></textarea>
<div id="smiliediv">
';
				foreach($smilies as $s)
				{
					$form .= '<div id="smile"><a href="#" onclick="return insertSmile(\'' . $s['code'] . '\')">' . $s['img'] . '</a></div>';
				}
				$form .='
</div>
					</td>
					<td>
<select id="chatcolorselect" onchange="chatColor(this.value)" style="width: 40px; background-color: rgb(0, 0, 0);">
<option selected="selected" value="#000000" style="background-color: rgb(0, 0, 0); color: rgb(0, 0, 0);"> </option>
<option value="Gold" style="background-color: Gold; color: Gold;"> </option>
<option value="Khaki" style="background-color: Khaki; color: Khaki;"> </option>
<option value="Orange" style="background-color: Orange; color: Orange;"> </option>
<option value="LightPink" style="background-color: LightPink; color: LightPink;"> </option>
<option value="Salmon" style="background-color: Salmon; color: Salmon;"> </option>
<option value="Tomato" style="background-color: Tomato; color: Tomato;"> </option>
<option value="Red" style="background-color: Red; color: Red;"> </option>
<option value="Brown" style="background-color: Brown; color: Brown;"> </option>
<option value="Maroon" style="background-color: Maroon; color: Maroon;"> </option>
<option value="DarkGreen" style="background-color: DarkGreen; color: DarkGreen;"> </option>
<option value="DarkCyan" style="background-color: DarkCyan; color: DarkCyan;"> </option>
<option value="LightSeaGreen" style="background-color: LightSeaGreen; color: LightSeaGreen;"> </option>
<option value="LawnGreen" style="background-color: LawnGreen; color: LawnGreen;"> </option>
<option value="MediumSeaGreen" style="background-color: MediumSeaGreen; color: MediumSeaGreen;"> </option>
<option value="BlueViolet" style="background-color: BlueViolet; color: BlueViolet;"> </option>
<option value="Cyan" style="background-color: Cyan; color: Cyan;"> </option>
<option value="Blue" style="background-color: Blue; color: Blue;"> </option>
<option value="DodgerBlue" style="background-color: DodgerBlue; color: DodgerBlue;"> </option>
<option value="LightSkyBlue" style="background-color: LightSkyBlue; color: LightSkyBlue;"> </option>
<option value="White" style="background-color: White; color: White;"> </option>
<option value="DimGray" style="background-color: DimGray; color: DimGray;"> </option>
<option value="DarkGray" style="background-color: DarkGray; color: DarkGray;"> </option>
<option value="Black" style="background-color: Black; color: Black;"> </option>
</select>

<input id="chatboldbutton" type="button" onclick="chatBold(\'\')" value="B" style="width: 40px; height: 28px; font-weight: bold;" />
<!--
<img src="/forum/images/editor/smilie.gif" width="21" alt="выбери смайлик" title="выбери смайлик" />
-->
<input id="chatitalicbutton" type="button" onclick="chatItalic(\'\')" value="I" style="width: 40px; height: 28px; font-style: italic;" />
<input id="chatunderbutton" type="button" onclick="chatUnder(\'\')" value="U" style="width: 40px; height: 28px; text-decoration: underline;" />

<br /><input type="submit" id="chatsubmitbutton" value="Ok" style="width: 40px; height: 28px;" />
					</td>
				</tr>
				</table>
				</form>
				';
				//$form = '<hr /><h3>Чат работает в режиме "только чтение". Пока общаемся в <a href="/forum">чате форума</a></h3>';
			}
			else
			{
				if ($blocked)
					$form = '<hr /><center><h3>Чат не доступен для заблокированных пользователей</h3></center>';
				else
					$form = '<hr /><center><h3>Чат доступен для авторизованных пользователей</h3></center>';
			}
			$content .= '
				<div id="innerDiv">
	<div id="chatstream">' . $msg . '</div>
				</div>
			</td></tr>
			<tr><td>
			' . $form;
		}
	break;

	case "news":
		$content .= '
			<div id="loading" align="center"><div id="innerDiv">
<div id="loaded">' . $pageName . ' "Новости" на стадии разработки</div>
			</div></div>';
	break;

	case "save": //ПУСТОЙ ОТВЕТ
		$content = serialize($rocketInfo);
		$tail = '';
	break;

	case "add": //добавление сообщения
		//НИЧЕГО НЕ ДЕЛАЕМ
		$content = '';
		$tail = '';
	break;

	default:
		$content .= '
			<div id="loading" align="center"><div id="innerDiv">
<div id="loaded">what are you wish to see here?</div>
			</div></div>';
}
$content .= $tail;

echo $content;