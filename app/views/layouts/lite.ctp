<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
echo $html->css('common');
echo $javascript->link(array('jquery', 'scripts', 'validation'));
echo $scripts_for_layout;
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='yandex-verification' content='41f90ac754cf4471' />
<meta name="verify-v1" content="Q+iq7OY8RadE9126YoJFPl1cnjLTMbHmU//RrR0TTks=" />
<meta name="keywords" content="видео, фильмы, сериалы, скачать бесплатно, webstream, videoxq.com, counter-strike, онлайн игры" />
<meta name="description" content="фильмы, видео, сериалы бесплатно для вебстрима. сервера counter strike, онлайн игры <?php if (isset($metaDescription)) echo htmlspecialchars($metaDescription); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo Configure::read('App.siteName'); ?>" href="http://videoxq.com/rss.xml" />
<title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
</head>
<body>
<?php
	echo $content_for_layout;
	echo $cakeDebug;
	echo $BlockBanner->getTail();
?>
</body>
</html>