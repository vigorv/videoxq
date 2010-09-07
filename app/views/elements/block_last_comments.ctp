<?php

if (count($block_last_comments))
{
	echo'
                <table border="0" cellspacing="0" cellpadding="0" width="260">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" id="c25" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1"> </td>
                      <td>
	<p><b>Последние комментарии</b></p>';
	$lastTitle = '';
	foreach ($block_last_comments as $post)
	{
		if ($lastTitle == $post['Film']['title'])
			continue;
		$lastTitle = $post['Film']['title'];

	    echo '<p><a href="/media/view/' . $post['Film']['id'] . '">'
	         . h($post['Film']['title'] ? $post['Film']['title'] : '(без названия)') . '</a><br />'
	         . $app->timeAgoInWords($post[0]['created']) . '</p>';
	}
	echo '
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" id="c26" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
                <br />
	';
}

//*
if (isset($onlineUsers))
{
	echo '
                <table border="0" cellspacing="0" cellpadding="0" width="260">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" id="c27" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1"> </td>
                      <td>
	<b>Сейчас на сайте:</b>';
	if ($onlineUsers['users'])
	{
		echo '
			<script type="text/javascript">
			<!--
				function switchOnliners()
				{
					o = document.getElementById("onlineusernames");
					if (o.style.display == "none")
					{
						o.style.display = "";
					}
					else
					{
						o.style.display = "none";
					}
					return false;
				}
			-->
			</script>
			<br />пользователей : <a href="#" onclick="return switchOnliners();">' . $onlineUsers['users'] . '</a>
			<div id="onlineusernames" style="display: none; margin: 0 0 0 0; padding: 0 0 0 0;">
			' . implode(', ', $onlineUsers['names']) . '
			</div>
		';
	}
	if ($onlineUsers['guests'])
		echo '<div>гостей : ' . $onlineUsers['guests'] . '</div>';
	if ($onlineUsers['users'])
		echo '<div>всего : ' . ($onlineUsers['guests'] + $onlineUsers['users']) . '</div>';

	echo '
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" id="c28" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
                <br />
	';
}
//*/
?>

