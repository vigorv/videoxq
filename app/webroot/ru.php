<?php

$ref = '/';
if (!empty($_SERVER["HTTP_REFERER"]))
{
	$ref = $_SERVER["HTTP_REFERER"];
}

ini_set('session.name', 'portalxqsession');
session_start();
$lang = explode('.', basename($_SERVER['PHP_SELF']));
$_SESSION["language"] = $lang[0];

header('location: ' . $ref);
