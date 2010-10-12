<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
<td width="125">
<?php
echo $BlockBanner->getBanner('antonina_left');
?>
</td>
<td>
<center>
<?php
	switch ($param)
	{
		case 'playoff':
			$playSwitch = '<a href="/media/lite/' . $id . '/playon" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: red; color: white;">' . __('Watch online', true) . ' "' . $film["title" . $langFix] . '"</a><br /><br />';
		break;
		case 'playon':
			$playSwitch = '<a href="/media/lite/' . $id . '/playoff" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: green; color: white;">' . __('Turn off online-player', true) . '</a><br /><br />';
		break;
	}

	$calls = 0;	$divx = ''; $redirect = '';
	$sec = 5;
	if ($authUser['userid'])
	{
		//$sec = intval($sec / 2);
	}

	if (!empty($id) && (isset($_SESSION['lastDivx'])))
	{
		$lastDivx = $_SESSION['lastDivx'];
		if (!empty($lastDivx[$id]))
		{
			$divx .= $lastDivx[$id];
			if (!empty($param) && ($param == 'playoff'))
			{
				$divx = '';
			}
		}
	}

	if (empty($lastDivx[$id]))
	{
		$playSwitch = '';
	}

	if (empty($divx))
	{
		$redirect .= $BlockBanner->getBanner('antonina_center');
	}

       	$redirect .= '
       	<br />
       	<h5>' . __('You are trying to get links for', true) . ':</h5>
       	<h3>' . $film["title" . $langFix] . '</h3>
		' . $playSwitch . $divx . '
       	<div id="directlinksdiv">
       	  	<script type="text/javascript">
       		<!--
       		document.write(\'<h4>' . __('To obtain the link wait for', true) . ' '.$sec.' ' . __('seconds', true) . '. ' . __('Left', true) . ' <b><span id="redirectSec'.$calls.'">'.$sec.'</span></b> ' . __('seconds', true) . '...</h4>\');
       		    	secCnt = '.$sec.';
       	';

       	if (isset($_SESSION["lastIds"]))
               	$lastIds = $_SESSION["lastIds"];
       	else
               	$lastIds = array();
		if ($id > 0)
       		$lastIds[$id] = $id;
      	$_SESSION["lastIds"] = $lastIds;

  		$redirect.='
  	    	function countDown(calls)
  		{
  			secSpan = document.getElementById("redirectSec"+calls);
  			secSpan.innerHTML = secCnt;
  			if (secCnt <= 0)
  			{
  		';
//					var html = $.ajax({
//						type: "POST",
//						cache: false
//					});
//					$("#directlinksdiv").fadeTo("slow", 0);
//					window.setTimeout(\'$("#directlinksdiv").load("/media/vasilina", {id:' . $id . '}, function(html){ $(this).fadeTo("slow", 1);});\', 500);
//					$("#directlinksdiv").load("/media/vasilina", {id:' . $id . '}, function(html){ });
		$redirect .= '
				src = document.getElementById("linksdiv");
				dst = document.getElementById("directlinksdiv");
				dst.innerHTML = src.innerHTML;
  			}
  			else
  				window.setTimeout("countDown("+calls+");", 1000);
  			secCnt = secCnt - 1;
  	    	}
  		';

       	$redirect.='
       			countDown('.$calls.');
         		-->
         		</script>

         		<noscript>
         			<h4>' . __('To obtain the link, enable Javascript in your browser and reload this page', true) . '</h4>
         		</noscript>
       		</div>
			<div id="linksdiv" style="display:none">
		';

       	$msg = '';
		if (empty($id))
		{
			$id = intval($_SERVER['QUERY_STRING']);
			if (empty($id))
				$msg = __('To obtain the link, use', true) . ' <a href="/media">' . __('search for catalog', true) . '</a>';
			else
				$msg = __('Session is reset. Try to get links through this', true) . ' <a href="/media/view/' . $id . '">' . __('page', true) . '</a>';
		}

		if (empty($_SESSION['Auth']))
		{
			//$msg = '<br />Ссылки доступны только для зарегистрированных пользователей';
		}

		//ПРОВЕРЯЕМ ПОКАЗАНА ЛИ БЫЛА РЕКЛАМА
		if (!empty($id) && (isset($_SESSION['lastIds'])))
		{
			$lastIds = $_SESSION['lastIds'];
			if (empty($lastIds[$id]))//ЗАПОЛНЯЕТСЯ СКРИПТОМ ПОКАЗА РЕКЛАМЫ
			{
				$msg = __('Get links only after viewing the page with a description', true) . ' <a href="/media/view/' . $id . '">' . __('of the movie', true) . '</a>';
			}
		}
		else
			$id = 0;

		if (/*!empty($id) && */(isset($_SESSION['lastLinks'])))
		{
			$lastLinks = $_SESSION['lastLinks'];
			if (!empty($lastLinks[$id]))
			{
				if (!empty($param) && ($param == 'playoff'))
				{
					$lastLinks[$id] = preg_replace('/(.*)?<a href="#" onclick="return getdivx[^a>](.*)?/', '', $lastLinks[$id]);
				}
				$redirect .= $lastLinks[$id];
			}
		}

		$redirect .= $msg;

       	$redirect .= '
			</div>
       	';
	echo $redirect;
/*
	if (empty($authUser['userid']))
	{
		echo '<br /><h3>Хочешь получать ссылки в два раза быстрее? <a href="/users/register">Зарегистрируйся</a></h3>';
	}
*/
	if (!empty($authUserGroups) || !in_array(Configure::read('VIPgroupId'), $authUserGroups))
	{
		echo '<br /><h3>' . __('Don`t want to wait?', true) . ' - <a href="/pays">' . __('Buy VIP', true) . '</a></h3>';
	}
?>
<br /><h3><?php __('If you have any problems with obtaining references, write'); ?> <a href="http://www.videoxq.com/forum/showthread.php?t=6"><?php __('to the forum'); ?></a></h3>
<?php
echo $BlockBanner->getBanner('antonina_bottom');
?>
</center>
</td>
<td width="125">
<?php
echo $BlockBanner->getBanner('antonina_right');
?>
</td>
</tr>
</table>
