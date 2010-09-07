<?php
echo $html->link('Пользователи', array('action'=>'index', 'controller' => 'users', Configure::read('Routing.admin') => true)) . '<br>';
echo $html->link('Группы', array('action'=>'index', 'controller' => 'groups', Configure::read('Routing.admin') => true)) . '<br>';
echo $html->link('Страницы', array('action'=>'index', 'controller' => 'site_pages', Configure::read('Routing.admin') => true)) . '<br>';
echo $html->link('Новости', array('action'=>'index', 'controller' => 'events', Configure::read('Routing.admin') => true)) . '<br>';
?>
