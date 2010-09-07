<?php
	if (empty($httpError))
		$httpError = 404;

	$url	= (empty($_GET['url']) ? '' : strtr($_GET['url'], array('/' => '|', ':' => '!')));
	$ip		= (empty($_GET['ip']) ? '' : $_GET['ip']);

	header('location: /pages/error' . $httpError . '/url:' . $url . '/ip:' . $ip);