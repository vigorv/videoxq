<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
//echo $html->css('style');
//echo $html->css('vusic');
//echo $html->css('cross');
echo $html->css('common');
echo $html->css('styles');
echo $javascript->link(array('jquery', 'scripts', 'validation'));
echo $scripts_for_layout;
//echo $javascript->link('lib');
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	if (empty($metaExpires))
	{
		//определяем последнюю пятницу
		$dayOfWeek = date('w');
		$difDay = $dayOfWeek - 5;
		if ($difDay < 0)
		{
			$difDay += 7;
		}
		$lastFri = mktime(20, 0, 0, date('m'), date('d') - $difDay, date('Y'));
		$metaExpires = date('r', $lastFri);
	}
	//echo '<meta http-equiv="expires" content="' . $metaExpires . '" />';

	if (empty($metaRobots))
	{
		$metaRobots = 'INDEX, FOLLOW';
	}
?>
<meta name="Robots" content="<?php echo $metaRobots; ?>" />
<meta name='yandex-verification' content='41f90ac754cf4471' />
<meta name="verify-v1" content="Q+iq7OY8RadE9126YoJFPl1cnjLTMbHmU//RrR0TTks=" />
<meta name="keywords" content="видео, фильмы, сериалы, скачать фильм, webstream, вебстрим, videoxq.com <?php //if (isset($metaKeywords)) echo $metaKeywords; ?>" />
<meta name="description" content="самый большой каталог бесплатных  видео фильмов и сериалов <?php //if (isset($metaDescription)) echo $metaDescription; ?>" />
<link rel="alternate" type="application/rss+xml" title='<?php echo Configure::read('App.siteName'); ?>' href="http://videoxq.com/rss.xml" />
<title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
</head>
<body>
    <div class="top">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" height="173">
        <tbody>
          <tr>
            <td width="304" valign="top">
              <img alt="" src="/i/cinema.jpg" />
            </td>
            <td valign="middle">
Каталог фильмов
			</td>
            <td width="250" valign="top">
              <img alt="" src="/i/cinema2.jpg" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="hor_grad"> </div>
<?php
if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
{
	//ДЛЯ ВИПОВ БЛОКИ С БАННЕРАМИ ВЫРЕЗАЕМ
	foreach ($blockContent as $bi => $b)
	{
		if (is_array($b) && count($b) > 0)
		{
			foreach($b as $key => $value)
			{
				if (strpos($value['title'], 'banner') !== false)
				{
					unset($blockContent[$bi][$key]);
				}
			}
		}
	}
}

//echo $BlockBanner->getBanner('header');

?>
    <div class="right_menu">
      <table cellpadding="0" cellspacing="0" border="0" height="30">
        <tbody>
          <tr>
            <td>
              <img src="/i/grad3.gif" alt="" width="26" height="32" />
            </td>
            <td class="right_menu_text">
<?php
if ($authUser['userid'] == 0)
{
?>
	<script type="text/javascript">
	<!--
		function toggleLogin()
		{
			$("#logindiv").slideToggle("fast");
			return false;
		}
	-->
	</script>
	<a href="/users/register">регистрация</a>
	|
    <a href="/users/restore">забыл пароль?</a>
    |
    <a href="/users/login" onclick="return toggleLogin();">войти</a>
<?php
}
else
{
    if (!empty($payInfo['Pay']))
    {
    	if ($payInfo["Pay"]["findate"] > time())
    		$payInfo = '<a title="оплачен по ' . date('d.m.y H:i', $payInfo["Pay"]["findate"]) . '" href="/pays">V.I.P. доступ</a>';
    }
    else
    {
    	if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
    		$payInfo = ' | <a title="бессрочный" href="/pays">V.I.P. доступ</a>';
    	else
    		$payInfo = ' | <a href="/pays">купить V.I.P.</a>';
    }
?>
			Привет, <a href="<?= $app->getUserProfileUrl($authUser['userid']) ?>"><?= $authUser['username'] ?></a>
            <?php
            if($pms>0)
            {
            	//echo '<a href="' . $app->getUserPMUrl($authUser['userid']) . '"><img src="/img/mail.gif"></a>';
            }
            echo $payInfo;?>
             | <a href="/users/logout">Выйти</a>
<?php
}
?>
    		</td>
          </tr>
        </tbody>
      </table>
    </div>
      <!-- Главное меню -->
      <div id="PageSelector">
        <ul>
<?php
	$menuItems = array(
		'/media' => 'Видео',
		'/people' => 'Люди',
		'/forum' => 'Форум',
		'/pages/faq' => 'FAQ',
	);
	foreach ($menuItems as $key => $value)
	{
		$current = '';
		$href = 'href="' . $key . '"';
		if (eregi('^' . $key . '$', $here))
		{
			$current = ' id="current"';
			$href = 'noref="noref"';
		}
		echo'
          <li ' . $current . '>
            <!-- <img style="float: left;" src="/i/folder.jpg">-->
            <a ' . $href . '>' . $value . '</a>
          </li>
		';
	}
?>
        </ul>
      </div>
      <!-- /Главное меню -->
    <div id="CatalogPage" width="100%">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tbody>
            <tr>
              <td valign="top" style="padding:5px;">
<?php
if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
{
	//ДЛЯ ВИПОВ БЛОКИ С БАННЕРАМИ ВЫРЕЗАЕМ
/*
	echo'<pre>';
	print_r($blockContent);
	echo'</pre>';
//*/
	foreach ($blockContent as $bi => $b)
	{
		if (is_array($b) && count($b) > 0)
		{
			foreach($b as $key => $value)
			{
				if (strpos($value['title'], 'banner') !== false)
				{
					unset($blockContent[$bi][$key]);
				}
			}
		}
	}
}

//BLOCK LEFT
if (!empty($blockContent['left']))
  echo $this->element('blocks', array('blockArray' => $blockContent['left']));
?>
              </td>
              <td width="100%" valign="top" style="padding:5px 2px 0 2px;">
<?php
$placeNamePrefix = '';
if ($isWS)
	$placeNamePrefix = 'WS';

$placeName = $placeNamePrefix . 'header1';
echo $BlockBanner->getBanner($placeName);

?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1"> </td>
                      <td>
<?php

if ($session->check('Message.flash'))
    $session->flash();

if ($session->check('Message.auth'))
    $session->flash('auth');

$placeName = $placeNamePrefix . 'header2';
echo $BlockBanner->getBanner($placeName);
echo $content_for_layout;
$placeName = $placeNamePrefix . 'bottom1';
echo $BlockBanner->getBanner($placeName);
?>
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
<?php
$placeName = $placeNamePrefix . 'bottom2';
echo $BlockBanner->getBanner($placeName);
?>
              </td>
              <td valign="top" style="padding:5px;">
<?php
//BLOCK RIGHT

if (!empty($blockContent['right']))
  echo $this->element('blocks', array('blockArray' => $blockContent['right']));
?>
              </td>
            </tr>
          </tbody>
        </table>
	  </div>

<!--LiveInternet counter-->
<script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img vspace='5' hspace='8' src='//counter.yadro.ru/hit?t14.5;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//-->
</script>
<!--/LiveInternet-->

<script type="text/javascript"><!--
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16449141-2']);
  _gaq.push(['_setDomainName', '.videoxq.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
//-->
</script>
<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<div style="display:none;"><script type="text/javascript">
try { var yaCounter1094491 = new Ya.Metrika(1094491); } catch(e){}
</script></div>
<noscript><div style="position:absolute;"><img src="//mc.yandex.ru/watch/1094491" alt="" /></div></noscript>
<!-- /Yandex.Metrika -->
<br />
<div style="z-index: 3; position: absolute;">
        <table cellspacing="0" cellpadding="0" width="100%" border="0">
          <tbody>
            <tr height="32px">
              <td width="70%" class="bottom_data" nowrap="">
                <div>
                	&nbsp;ООО "Патент Медиа"
                	|
					<a href="/pages/reklama">Реклама</a>
					|
					<a href="/pages/kontaktyi">Контакты</a>
					|
					<a href="/pages/nashi-partneryi">Партнеры</a>
                </div>
              </td>
              <td class="bottom_block">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bottom">
        <div class="hor_grad2"> </div>
      </div>

	<div style="position: absolute; z-index: 999; top: 0px; left: 0px; display: none" id="JHRControllerLoaderBox">
      <img src="/i/progbar.gif" border="0" alt="" />
    </div>


<?php
echo $BlockBanner->getTail();
echo $cakeDebug;
?>
	</body>
</html>
<?php
