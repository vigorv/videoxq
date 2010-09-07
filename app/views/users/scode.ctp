<?php
header( 'Content-type: application/xml');
header( 'Expires: Mon, 26 Jul 1970 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
/*
echo '<?xml version="1.0" encoding="windows-1251" ?>';

if (!empty($captcha))
{
	echo '<code name="code">' . md5($captcha . '1234567890') . '</code>';
}
else
{
	echo '<code></code>';
}
//*/

if (!empty($captcha))
{
	echo md5($captcha . '1234567890');
}
