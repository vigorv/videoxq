<?php
header('Content-Type: text/html; charset=utf-8');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
if (isset($_POST['id']))
	$id = intval($_POST['id']);
else
	$id = 0;

$msg = '';
if (empty($id))
{
	$id = intval($_SERVER['QUERY_STRING']);
	if (empty($id))
		$msg = 'Чтобы получить ссылки, воспользуйтесь <a href="/media/search">поиском по каталогу</a>';
	else
		$msg = 'Данные сессии сброшены. Попробуйте получить ссылки через эту <a href="/media/view/' . $id . '">страницу</a>';
}
if (empty($_SESSION['Auth']))
{
	$msg = 'Доступно только для зарегистрированных пользователей';
}
//ПРОВЕРЯЕМ ПОКАЗАНА ЛИ БЫЛА РЕКЛАМА
if (!empty($id) && (!empty($_SESSION['Auth'])) && (isset($_SESSION['lastIds'])))
{
	$lastIds = $_SESSION['lastIds'];
	if (empty($lastIds[$id]))//ЗАПОЛНЯЕТСЯ СКРИПТОМ ПОКАЗА РЕКЛАМЫ
	{
		$msg = 'Получить ссылки можно только после просмотра страницы с описанием <a href="/media/view/' . $id . '">фильма из каталога</a>';
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

echo $msg;
