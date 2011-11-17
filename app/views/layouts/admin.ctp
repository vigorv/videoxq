<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title_for_layout;?></title>
<link rel="shortcut icon" href="<?php echo $this->webroot . 'favicon.ico';?>" type="image/x-icon" />
<?php
echo $html->css('cake.generic');
echo $javascript->link(array('jquery', 'scripts', 'validation'));
echo $scripts_for_layout;
?>

<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="/js/mce.js"></script>
<!-- /tinyMCE -->
</head>
<body>
<div id="container">
<table width="100%" border="0">
<tr>
<td width="15%">
<div id="left">
<?php
echo $html->link('Статистика поиска', array('action'=>'search_logs', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Оплата VIP', array('action'=>'index', 'controller' => 'pays', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Баннеры', array('action'=>'banners', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('GeoIpBase', array('action'=>'geo', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Memcache', array('action'=>'memcache', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Заявки на фильмы', array('action'=>'index', 'controller' => 'feedbacks', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Управление фильмами', array('action'=>'index', 'controller' => 'media', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Поисковые слова', array('action'=>'index', 'controller' => 'search_words', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('каталог OZON', array('action'=>'ozon', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Голосования', array('action'=>'index', 'controller' => 'polls', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Тестирование', 'http://' . $_SERVER['HTTP_HOST'] . '/sitest.php') . '<br>';
echo '<br>';
echo $html->link('Пользователи', array('action'=>'index', 'controller' => 'users', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Группы', array('action'=>'index', 'controller' => 'groups', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Права доступа', array('action'=>'acl', 'controller' => 'admin', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Права доступа(контроллеры)', array('action'=>'acl_controllers', 'controller' => 'admin', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Страницы', array('action'=>'index', 'controller' => 'pages', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Блоки', array('action'=>'index', 'controller' => 'blocks', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Направления (категории)', array('action'=>'index', 'controller' => 'directions', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Новости', array('action'=>'index', 'controller' => 'news', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('События', array('action'=>'index', 'controller' => 'events', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('FAQ', array('action'=>'index', 'controller' => 'faq_categories', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Мета-теги', array('action'=>'index', 'controller' => 'meta_tags', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('На сайт', '/', array('style'=>'color: red;')) . '<br>';
echo '<br>';
echo $html->link('В начало', '/admin', array('style'=>'color: red;')) . '<br>';
echo '<br>';echo '<br>';
echo $html->link('Выход', '/users/logout', array('style'=>'color: red;'));
?>
</div>
</td>
<td>
<div id="content" style="overflow: auto;">
<?php
if ($session->check('Message.flash'))
    $session->flash();

if ($session->check('Message.auth'))
    $session->flash('auth');
?>
<?php
echo $content_for_layout;
?>
</div>
</td>
</tr>
</table>
    <div id="footer">
    </div>
</div>
<?php echo $cakeDebug?>
</body>
</html>

