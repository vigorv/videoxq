<script type="text/javascript">
<!--
//<?php echo serialize($rocketInfo); ?>

	var flipOn = <?php
		if (!isset($rocketInfo['flipOn']))
		{
			echo 'false';
		}
		else
		{
			if ($rocketInfo['flipOn'] == 'false')
				echo 'false';
			else
				echo 'true';
		}
	?>;
	var mouseDown = false;
	var topDown = false;

	var rocketOffset;
	var rocketHeight = <?php echo (!isset($rocketInfo['rocketHeight'])) ? 200 : $rocketInfo['rocketHeight']; ?>;
	var rocketWidth = <?php echo (!isset($rocketInfo['rocketWidth'])) ? 550 : $rocketInfo['rocketWidth']; ?>;
	var rocketPage = '<?php echo (empty($rocketInfo['rocketPage'])) ? 'favorites' : $rocketInfo['rocketPage']; ?>';
	var rocketChatActual;
	var rocketChatStyle;
	var mouseX;
	var mouseY;
	var rocketChatColor = '<?php echo (empty($rocketInfo['rocketChatColor'])) ? '#000000' : $rocketInfo['rocketChatColor']; ?>';
	var rocketChatBold = '<?php echo (empty($rocketInfo['rocketChatBold'])) ? 'normal' : $rocketInfo['rocketChatBold']; ?>';
	var rocketChatItalic = '<?php echo (empty($rocketInfo['rocketChatItalic'])) ? '' : $rocketInfo['rocketChatItalic']; ?>';
	var rocketChatUnder = '<?php echo (empty($rocketInfo['rocketChatUnder'])) ? '' : $rocketInfo['rocketChatUnder']; ?>';
	var actualchat = '<?php echo (empty($rocketInfo['actualchat'])) ? '0' : $rocketInfo['rocketChatUnder']; ?>';
-->
</script>
<style>
	@media screen {
	  #flipup {
			display: none;
			position: fixed; z-index: 10;
			top: <?php echo (!isset($rocketInfo['rocketTop'])) ? '30' : $rocketInfo['rocketTop']; ?>%;
			left: <?php echo (!isset($rocketInfo['rocketLeft'])) ? '-535px' : $rocketInfo['rocketLeft'].'px'; ?>;
			width: <?php echo (!isset($rocketInfo['rocketWidth'])) ? '550px' : $rocketInfo['rocketWidth'].'px'; ?>;
			color: white; background: #555555; }
	}
	#flipup #innerDiv { margin: 0 0 5px 0; height: <?php echo (empty($rocketInfo['rocketHeight'])) ? 200 : $rocketInfo['rocketHeight']; ?>px; overflow: auto; }
	#flipup #rocketcontent { padding-left: 10px; background-color: #555555; }
	#flipup textarea { height: 100px; width : 99%; margin: 0 0 0 0; border-width: 0px; }
	#flipup h3 { margin-bottom: 0px; font-weight: bold; }
	#favformdiv { border: 1px solid black; background-color: #888888; position: absolute; display: none; margin-left:5px; margin-right:15px; padding: 3px 5px 3px 5px; z-index: 20; left: 0px; }
	#favformdiv h4 { color: red; font-size: 14px; }
	#favformsubmit {  }
	#favformtitle { border: 1px solid; width: 250px; }
	.favlinkdiv { width: 190px; float: left; padding:2px 3px 2px 3px; margin:2px 3px 2px 3px; background: grey; overflow: hidden; }
	#flipup hr { margin: 10px 0 5px 0; color: grey; }
	#flipup a { color: white; }
	#flipup a:hover { color: red; text-decoration: none; }

	#chatlinestable { color: gray; }
	#chatlinestable a { color: black; }

	#chatstream, #chatmemo { background: #dddddd; color: black; }
	#smiliediv { width: 99%; overflow: auto; background: #dddddd; }
	#smile { float: left; margin-right: 5px; }

	#blockicon { float: right; height: 100%; width: 13px; }
	.blockborder { background-color: #555555; }
	#imgheight { height: 300px; width: 13px; border: 0; margin: 0 0 0 0; padding: 0 0 0 0; }
	#rocketcurrentpage { background: url(/img/block/r_border.gif) right repeat-y; clear:left; }

	#rocketmenu { margin-bottom:2px; }
	#rocketmenu div { float: left; margin: 2px 3px 2px -5px; font: 12px bold; color: white; }
	#rocketmenu div:hover { background: #353535; }
	#rocketmenu a { padding: 2px 5px 2px 5px; color: white; text-decoration: underline; display: block; }
	#rocketmenu a:hover { color: #87b66a; }

	#flipup table.rocketcontent { margin-left: 5px; padding-right: 7px; }
	#flipup td.blockicon { background: url(/img/block/r_border.gif) right repeat-y; }
	#loading { width: 100%; position: absolute; top: 50%; }

</style>
<div id="flipup">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="blockborder" width="100%"><img id="topmove" src="/img/block/t_border.gif" width="100%" height="13" /></td>
		<td><img id="resize" src="/img/block/rt_corner.gif" width="13" height="13" /></td>
	</tr>

	<tr valign="top">
		<td>
			<table class="rocketcontent" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td>
				<h3>Ракетный пульт <a href="#" onclick="return rocketFavoriteForm();"><img src="/img/icons/fav.gif" width="16" valign="top" alt="добавить в фавнутые" title="добавить в фавнутые" /></a></h3>
				<div id="favformdiv">
<?php
	if (!empty($authUser['userid']))
	{
?>
					<table cellspacing="0" cellpadding="3" border="0">
					<form name="favform" onsubmit="return rocketAddFavorite();">
					<tr><td>Название для страницы</td><td align="center"><a href="#" alt="закрыть" title="закрыть" onclick="return rocketFavoriteFormToggle();"><img src="/img/block/close.png" width="15" alt="закрыть" title="закрыть" /></a></td></tr>
					<tr>
					<td>
						<input type="hidden" name="id" value="" />
						<input id="favformtitle" type="text" name="title" alt="Название для страницы" title="Название для страницы" />
					</td><td>
						<input id="favformsubmit" type="image" alt="Название для страницы" title="Название для страницы" src="/img/login.gif" width="26" />
					</td>
					</form>
					</tr>
					</table>
<?php
	}
	else
	{
		echo'<h4>Только зарегистрированные пользователи могут "фавнуть" страницу</h4>';
	}
?>
				</div>
				<div id="rocketmenu">
					<div><a href="#" alt="" title="" onclick="rocketFavorites(); saveRocket(); return false; " <?php echo ($rocketInfo['rocketPage'] == 'favorites') ? 'rel="current"' : ''; ?>>Фавнутые</a></div>
					<div><a href="#" alt="" title="" onclick="rocketChat(); saveRocket(); return false; " <?php echo ($rocketInfo['rocketPage'] == 'chat') ? 'rel="current"' : ''; ?>>Чат</a></div>
					<div><a href="#" alt="" title="" onclick="rocketNews(); saveRocket(); return false; " <?php echo ($rocketInfo['rocketPage'] == 'news') ? 'rel="current"' : ''; ?>>Новости</a></div>
					<div><a href="/forum" alt="" title="">Форум</a></div>
				</div>
				<br /><hr />
			</td></tr>
			<tr><td id="rocketpagecontent">
				<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
				<tr><td height="100%">
					<div id="innerDiv">
					</div>
				</td></tr></table>
			</td></tr>
			</table>
		</td>
		<td class="blockicon">
			<div id="blockicon">
				<map name="arrowmap">
					<area shape="rect" coords="0,25,14,60" href="#" onclick="return doFlip();" />
				</map>
				<img usemap="#arrowmap" src="/img/block/arrows.gif" align="top" width="13"  border="0" />
				<img id="imgheight" alt="" title="" border="0" align="right" src="/img/block/r_border.gif" />
			</div>
		</td>
	</tr>
	<tr>
		<td class="blockborder" width="100%"><img src="/img/block/b_border.gif" width="100%" height="14" /></td>
		<td><img src="/img/block/rb_corner.gif" width="13" height="14" alt="" title="" /></td>
	</tr>
	</table>
</div>
<?php
echo $javascript->link('rocket');
