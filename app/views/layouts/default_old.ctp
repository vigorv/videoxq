<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
//echo $html->css('style');
//echo $html->css('vusic');
//echo $html->css('cross');
echo $html->css('common');
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
<meta name="keywords" content="видео, фильмы, сериалы, скачать бесплатно, webstream, videoxq.com, counter-strike, онлайн игры <?php if (isset($metaKeywords)) echo $metaKeywords; ?>" />
<meta name="description" content="фильмы, видео, сериалы бесплатно для вебстрима. сервера counter strike, онлайн игры <?php if (isset($metaDescription)) echo $metaDescription; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo Configure::read('App.siteName'); ?>" href="http://videoxq.com/rss.xml" />
<title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
</head>
<body>
<div id="wrap">
<script type="text/javascript">
<?php
		static $cnt = 0;
		$js = '
			if (window.XMLHttpRequest) {
				http_request' . $cnt . ' = new XMLHttpRequest();
//				http_request' . $cnt . '.overrideMimeType("text/xml");
			} else if (window.ActiveXObject) { // IE
				http_request' . $cnt . ' = new ActiveXObject("Microsoft.XMLHTTP");
			}

			if (http_request' . $cnt . ' != null)
			{
				r = document.referrer.replace(/http:\/\//, "");
				r = r.replace(/\//g, "-2F-");
				r = r.replace(/:/g, "-3A-");

				l = document.location.href.replace(/http:\/\//, "");
				l = l.replace(/\//g, "-2F-");
				l = l.replace(/:/g, "-3A-");

				http_request' . $cnt . '.open("GET", "/roadmaps/add/"+r+"/"+l);
				http_request' . $cnt . '.send(null);
			}

		';
		//ВРЕМЕННО ЦЕПОЧКИ ПЕРЕХОДОВ НЕ СОХРАНЯЕМ echo $js;
?>
</script>
<?php
/*
if (empty($authUserGroups) || (!empty($authUser['username']) && ($authUser['username'] != 'polar')))
{
	//ВЫРЕЗАЕМ РОКЕТ БЛОК
	foreach ($blockContent as $bi => $b)
	{
		if (is_array($b) && count($b) > 0)
		{
			foreach($b as $key => $value)
			{
				if (strpos($value['title'], 'rocket') !== false)
				{
					unset($blockContent[$bi][$key]);
				}
			}
		}
	}
}
//*/

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


echo $BlockBanner->getBanner('header');

if (!empty($blockContent['header']))
  echo $this->element('blocks', array('blockArray' => $blockContent['header']));
//pr($this);
if ($session->check('Message.flash'))
    $session->flash();

if ($session->check('Message.auth'))
    $session->flash('auth');
?>
<?php
if (!empty($blockContent['top']))
  echo $this->element('blocks', array('blockArray' => $blockContent['top']));

echo $content_for_layout;
?>
<?php
if (!empty($blockContent['fixed']))
  echo $this->element('blocks', array('blockArray' => $blockContent['fixed']));
?>
<?php
if ((!empty($blockContent['right'])) && ($this->params['controller']=='media') && ($this->params['action']!='view'))
  echo $this->element('blocks', array('blockArray' => $blockContent['right']));
?>
<div class="footer">
	<div class="copy" width="100%" align="center">
		<br />
		<span>© «<a href="http://www.videoxq.com">videoxq.com</a>», 2007-<?php echo date('Y');?></span><br />
		<span><a href="mailto:support@videoxq.com">email: support@videoxq.com</a></span><br />
		<span><a href="mailto:reklama@videoxq.com">реклама на сайте@videoxq.com</a></span><br />
		<!-- <span><a href="/media/sitemap">Карта сайта</a> (2,5 Мб)</span><br /> -->
		<span><a href="/forum/forumdisplay.php?f=4">Форум техподдержки</a></span>
	</div>
<!--
		<ul style="margin-top:30px;">
			<?if(0){?><li class="homepage"><a href="#">Сделать стартовой</a></li><?}?>
			<li>
			</li>
		</ul>
-->
<?php
if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
{
}
else
{
	echo $BlockBanner->getBanner('bottom');
?>
<!--
	<script type="text/javascript">
	var begun_auto_pad = 150066373;
	var begun_block_id = 150068489;
	</script>
	<script src="http://autocontext.begun.ru/autocontext2.js" type="text/javascript"></script>
-->
<?php
}
?>
	<div id="liveinternet">
			<!--LiveInternet counter--><script type="text/javascript"><!--
			document.write("<a href='http://www.liveinternet.ru/click' "+
			"target=_blank><img src='http://counter.yadro.ru/hit?t14.6;r"+
			escape(document.referrer)+((typeof(screen)=="undefined")?"":
			";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
			screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
			";"+Math.random()+
			"' alt='' title='LiveInternet: показано число просмотров за 24"+
			" часа, посетителей за 24 часа и за сегодня' "+
			"border=0 width=88 height=31><\/a>")//--></script>
			<!--/LiveInternet-->
	</div>


<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<script type="text/javascript">
try { var yaCounter153982 = new Ya.Metrika(153982); } catch(e){}
</script>
<noscript><div style="position: absolute;"><img src="//mc.yandex.ru/watch/153982" alt="" /></div></noscript>
<!-- /Yandex.Metrika -->
</div>
<?php
if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
{
}
else
{
?>
<?php
}
?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7621822-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php echo $cakeDebug;
echo $BlockBanner->getTail();
?>
</body>
</html>