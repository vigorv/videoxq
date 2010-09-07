<?php

if (!empty($_GET['Order_IDP']))
{
	$id = intval($_GET['Order_IDP']);
	header('location: /pays/assistno/' . $id);
}

header('location: /pays');
exit;
