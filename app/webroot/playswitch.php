<?php
	$prms = explode('-', $_SERVER['QUERY_STRING']);

	$id = intval($prms[0]);
	$switchParam = 'playoff';
	if (!empty($prms[1]) && in_array($prms[1], array('playon', 'playoff')))
	{
		$switchParam = $prms[1];
	}
	setcookie('playSwitch', $switchParam, time() + 60*60*24*30, '/');
	$url = '/media';
	if (!empty($id)) $url .= '/view/' . $id . '#divx';
	header('location: ' . $url);

