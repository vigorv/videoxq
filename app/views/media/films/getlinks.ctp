<?php
header('Content-Type: text/html; charset=utf-8');
if (isset($_POST['id']))
	$id = intval($_POST['id']);
else
	$id = 0;

if (empty($_SESSION['Auth']))
{
	$msg = __('Available only to registered users', true);
}
if (empty($id))
{
	$msg = __('No details about the movie. Search the', true) . ' <a href="/media">' . __('catalogu', true) . '</a>';
}
//ПРОВЕРЯЕМ ПОКАЗАНА ЛИ БЫЛА РЕКЛАМА
if (!empty($id) && (!empty($_SESSION['Auth'])) && (isset($_SESSION['lastIds'])))
{
	$lastIds = $_SESSION['lastIds'];
	if (empty($lastIds[$id]))//ЗАПОЛНЯЕТСЯ СКРИПТОМ ПОКАЗА РЕКЛАМЫ
	{
		$id = 0;
		$msg = __('', true) . ' <a href="/media">'  . __('cataloga', true) . '</a>';
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