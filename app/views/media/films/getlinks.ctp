<?php
header('Content-Type: text/html; charset=utf-8');
if (isset($_POST['id']))
	$id = intval($_POST['id']);
else
	$id = 0;

if (empty($_SESSION['Auth']))
{
	$msg = 'Доступно только для зарегистрированных пользователей';
}
if (empty($id))
{
	$msg = 'Нет данных о фильме. Воспользуйтесь поиском по <a href="/media">каталогу</a>';
}
//ПРОВЕРЯЕМ ПОКАЗАНА ЛИ БЫЛА РЕКЛАМА
if (!empty($id) && (!empty($_SESSION['Auth'])) && (isset($_SESSION['lastIds'])))
{
	$lastIds = $_SESSION['lastIds'];
	if (empty($lastIds[$id]))//ЗАПОЛНЯЕТСЯ СКРИПТОМ ПОКАЗА РЕКЛАМЫ
	{
		$id = 0;
		$msg = 'Получить ссылки можно только после просмотра страницы с описанием фильма из <a href="/media">каталога</a>';
	}
}
else
	$id = 0;

if (!empty($id) && (isset($_SESSION['lastLinks'])))
{
	$lastLinks = $_SESSION['lastLinks'];
	if (!empty($lastLinks[$id]))
	{
		echo $lastLinks[$id];
	}
}
else
{
	echo $msg;
}