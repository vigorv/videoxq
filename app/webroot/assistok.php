<?php

if (!empty($_GET['Order_IDP']))
{
	$id = intval($_GET['Order_IDP']);
	header('location: /pays/assistok/' . $id);
}

header('location: /pays');
exit;
