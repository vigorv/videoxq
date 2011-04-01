<?php

if (count($block_last_comments))
{
	echo'<h3>' . __('Last comments', true) . '</h3>';
	$lastTitle = '';
	foreach ($block_last_comments as $post)
	{
		if ($lastTitle == $post['Film']['title' . $langFix])
			continue;
		$lastTitle = $post['Film']['title' . $langFix];

	    echo '<p><a href="/media/view/' . $post['Film']['id'] . '">'
	         . h($post['Film']['title' . $langFix] ? $post['Film']['title' . $langFix] : '(' . __('no title', true) . ')') . '</a><br />'
	         . $app->timeAgoInWords($post[0]['created']) . '</p>';
	}
}

//*
if (isset($onlineUsers))
{
	echo '<b>' . __('Now Online', true) . ':</b>';
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
			<br />' . __('usera', true) . ' : <a href="#" onclick="return switchOnliners();">' . $onlineUsers['users'] . '</a>
			<div id="onlineusernames" style="display: none; margin: 0 0 0 0; padding: 0 0 0 0;">
			' . implode(', ', $onlineUsers['names']) . '
			</div>
		';
	}
	if ($onlineUsers['guests'])
		echo '<div>' . __('guesta', true) . ' : ' . $onlineUsers['guests'] . '</div>';
	if ($onlineUsers['users'])
		echo '<div>' . __('total', true) . ' : ' . ($onlineUsers['guests'] + $onlineUsers['users']) . '</div>';
}
//*/
?>
<br /><br />

