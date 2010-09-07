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
			$playSwitch = '<a href="/media/lite/' . $id . '/playon" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: red; color: white;">Смотреть online "' . $film["title"] . '"</a><br /><br />';
		break;
		case 'playon':
			$playSwitch = '<a href="/media/lite/' . $id . '/playoff" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: green; color: white;">Выключить online-проигрыватель</a><br /><br />';
		break;
	}

	$calls = 0;	$divx = ''; $redirect = '';
	$sec = 20;
	if ($authUser['userid'])
	{
		$sec = intval($sec / 2);
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
       	<h5>Вы пытаетесь получить ссылки для:</h5>
       	<h3>' . $film["title"] . '</h3>
		' . $playSwitch . $divx . '
       	<div id="directlinksdiv">
       	  	<script type="text/javascript">
       		<!--
       		document.write(\'<h4>Чтобы получить ссылки подождите '.$sec.' секунд. Осталось <b><span id="redirectSec'.$calls.'">'.$sec.'</span></b> секунд...</h4>\');
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
         			<h4>Чтобы получить ссылки, включите поддержку Javascript в вашем браузере и обновите страницу</h4>
         		</noscript>
       		</div>
			<div id="linksdiv" style="display:none">
		';

       	$msg = '';
		if (empty($id))
		{
			$id = intval($_SERVER['QUERY_STRING']);
			if (empty($id))
				$msg = 'Чтобы получить ссылки, воспользуйтесь <a href="/media">поиском по каталогу</a>';
			else
				$msg = 'Данные сессии сброшены. Попробуйте получить ссылки через эту <a href="/media/view/' . $id . '">страницу</a>';
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
				$msg = 'Получить ссылки можно только после просмотра страницы с описанием <a href="/media/view/' . $id . '">фильма из каталога</a>';
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
		echo '<br /><h3>Чтобы не ждать - <a href="/pays">купи VIP</a></h3>';
	}
?>
<br /><h3>Если у вас возникли проблемы с получением ссылок, пишите <a href="http://www.videoxq.com/forum/showthread.php?t=6">на форум</a></h3>
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
