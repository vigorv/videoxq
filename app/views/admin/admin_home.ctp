<?php
echo $html->link('Статистика поиска', array('action'=>'search_logs', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Оплата VIP', array('action'=>'index', 'controller' => 'pays', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Баннеры', array('action'=>'banners', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('GeoIpBase', array('action'=>'geo', 'controller' => 'utils', Configure::read('Routing.admin') => true)) . '<br>';
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
echo $html->link('Новости', array('action'=>'index', 'controller' => 'events', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('FAQ', array('action'=>'index', 'controller' => 'faq_categories', Configure::read('Routing.admin') => true)) . '<br>';
echo '<br>';
echo $html->link('Redirects', array('action'=>'index', 'controller' => 'redirects', Configure::read('Routing.admin') => true)) . '<br>';

echo '<h3>Host</h3>';
echo '<p>' . $_SERVER["HTTP_HOST"] . '</p>';
echo '<h3>REMOTE_ADDR</h3>';
echo '<p>' . (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "empty") . '</p>';
echo '<h3>X-Forwarded-For</h3>';
echo '<p>' . (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : "empty") . '</p>';
echo '<h3>X-Real-Ip</h3>';
echo '<p>' . (isset($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] . ' (' . gethostbyaddr($_SERVER["HTTP_X_REAL_IP"]) . ')'  : "empty") . '</p>';
?>
