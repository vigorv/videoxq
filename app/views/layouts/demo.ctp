<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name='yandex-verification' content='41f90ac754cf4471' />
<meta name="verify-v1" content="Q+iq7OY8RadE9126YoJFPl1cnjLTMbHmU//RrR0TTks=" />
<meta name="keywords" content="видео, фильмы, сериалы, скачать бесплатно, webstream, videoxq.com, counter-strike, онлайн игры" />
<meta name="description" content="фильмы, видео, сериалы бесплатно для вебстрима. сервера counter strike, онлайн игры" />
<title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
<?php echo $html->css('demo');
echo $javascript->link('jquery');
echo $javascript->link('scripts');
echo $scripts_for_layout;
?>
</head>

<body>
<div class="wrapper">
    <div class="vusic"></div>
    <ul class="inner">
        <li class="forum"><a href="<?= Configure::read('App.forumPath') ?>">Форум videoxq.com</a></li>
        <li class="media"><a href="/media">Медиа-каталог</a></li>
    </ul>
</div>
</body>
<?php echo $cakeDebug; ?>
</html>