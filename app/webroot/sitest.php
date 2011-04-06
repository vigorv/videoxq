<?php
if (empty($_SERVER['HTTP_REFERER']))
{
	$_SERVER['HTTP_REFERER'] = '';
}

$rInfo = parse_url($_SERVER['HTTP_REFERER']);
$hInfo = parse_url('http://' . $_SERVER['HTTP_HOST']);
if (($rInfo['host'] <> $hInfo['host']) || empty($rInfo['host']))
{
	header('HTTP/1.1 403 Forbidden');
	die('Forbidden. Contact <a href="http://videoxq.com">site</a> administrator');
}

header('Expires: ' . date('r', time() - 60*60*24));

function isPass($flag)
{
	if ($flag)
	{
		return '<font color="green">PASS</font>';
	}
	else
	{
		return '<font color="red">FAIL</font>';
	}
}

$error = false;
ob_start();

$ch = curl_init("http://" . $_SERVER['HTTP_HOST'] . "/users/login");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));//ОБХОДИМ ПРОБЛЕМУ С NGINX

//ПРОБУЕМ АВТОРИЗОВАТЬСЯ ПОД УЧЕТНОЙ ЗАПИСЬЮ ПОЛЬЗОВАТЕЛЯ spark (ДОЛЖНА ПРИСУТСТВОВАТЬ В БАЗЕ)
$data = 'data[User][username]=spark&data[User][password]=spark&data[User][remember_me]=1';
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$cookieFile = 'cookie.txt';
curl_setopt ($ch, CURLOPT_COOKIEFILE, "cookie.txt"); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
$content = curl_exec($ch);
$matches = array();
preg_match_all('/<title>(.*)<\/title>/', $content, $matches);
curl_close($ch);

//РАЗБОР COOKIES ПОСЛЕ АВТОРИЗАЦИИ
$cookieFile = $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/' . $cookieFile;
$strCookies = '';
echo'<p>Cookie test ';
if (file_exists($cookieFile))
{
	$cookies = file_get_contents($cookieFile);
	unlink($cookieFile);
	$cookies = explode("\n", $cookies);
	foreach ($cookies as $cookie)
	{
		$cook = explode("\t", $cookie);
		if (isset($cook[5]))
		{
			$strCookies .= $cook[5] . '=' . $cook[6] . ';';
		}
	}
	echo isPass(true);
}
else
{
	echo isPass(false);
	$error = true;
}
echo'</p>';

function curlGetContent($url, $strCookies)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_COOKIE, $strCookies); //Устанавливаем нужные куки в необходимом формате
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//БЕЗ ВЫВОДА В STDOUT
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));//ОБХОДИМ ПРОБЛЕМУ С NGINX
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

//ТЕСТ АВТОРИЗАЦИИ
$content = curlGetContent("http://" . $_SERVER['HTTP_HOST'] . "/media", $strCookies);
echo'<p>Authentication test ';
if (strpos($content, 'users/logout'))//ОПРЕДЕЛЯЕМ ПО НАЛИЧИЮ ССЫЛКИ "Выход"
{
	echo isPass(true);
}
else
{
	$error = true;
	echo isPass(false);
	//ОТПРАВКА УВЕДОМЛЕНИЯ НА ПОЧТУ АДМИНУ 79137769218@sms.mtslife.ru
}
echo'</p>';

//	тест ленты RSS
$content = curlGetContent("http://" . $_SERVER['HTTP_HOST'] . "/media/rss", $strCookies);
$matches = array();
preg_match_all('/(.*?)<title>(.*?)<\/title>(.*?)/', $content, $matches, PREG_SET_ORDER);
		$title = $matches[1][2];
$matches = array();
preg_match_all('/(.*?)<link>(.*?)<\/link>(.*?)/', $content, $matches, PREG_SET_ORDER);
		$url = explode('/', $matches[1][2]);
		$id = intval($url[count($url) - 1]);
echo'<p>RSS test ';
if ($id > 0)
{
	echo isPass(true) . ' (filmId=' . $id .') ' . $title;
}
else
{
	$error = true;
	echo isPass(false);
	//ОТПРАВКА УВЕДОМЛЕНИЯ НА ПОЧТУ АДМИНУ
}
echo'</p>';

//	тест отображения ссылок на скачивание
if ($id > 0)
{
	$content = curlGetContent("http://" . $_SERVER['HTTP_HOST'] . "/media/view/" . $id, $strCookies);
	$content = curlGetContent("http://" . $_SERVER['HTTP_HOST'] . "/media/lite/" . $id, $strCookies);
	$matches = array();
//ИЩЕМ ССЫЛКУ НА ФИЛЬМ
	preg_match_all('/(.*?)href=(.*?)\/(.*?)">\\3<\/(.*?)/', $content, $matches, PREG_SET_ORDER);

	echo'<p>Direct links visibility test ';
	if (!empty($matches[0][3]))
	{
		echo isPass(true) . ' (' . $matches[0][3] . ')';
		$lnk = $matches[0][2] . '/' . $matches[0][3];
	}
	else
	{
		$error = true;
		echo isPass(false) . ' (возможно, нет лицензии)';
		//ОТПРАВКА УВЕДОМЛЕНИЯ НА ПОЧТУ АДМИНУ
	}
	echo'</p>';
}

//тест скачивания
if (isset($lnk))
{
	clearstatcache();

	$lnk = parse_url($lnk);
	$host = $lnk["path"];
	$lnk = explode('/', $host);
	$host = $lnk[2];
	$ans = false;
	unset($lnk[0]);
	unset($lnk[1]);
	unset($lnk[2]);
	$lnk = '/' . implode('/', $lnk);
	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	echo'<p>Download test ';
	if (!$fp) {
	    echo "$errstr ($errno)<br />\n";
	} else {
	    $out = "GET " . $lnk . " HTTP/1.1\r\n";
	    $out .= "Host: " . $host . "\r\n";
	    $out .= "Connection: Close\r\n\r\n";

	    fwrite($fp, $out);
		$ans = fgets($fp, 128);
	}

	if (!preg_match('/http(.*?)200/i', $ans))
	{
		echo isPass(false) . ' (can`t download ' . $lnk . ' from ' . $host . ') ' . $ans;
	}
	else
	{
		echo isPass(true) . ' (reading from ' . $host . $lnk . ')';
	}
	echo '</p>';
    fclose($fp);
}

//ТЕСТ НАЛИЧИЯ КЭША БЛОКОВ
echo'<p>Block Cache test ';
if (function_exists('memcache_connect'))
{
	//$memcache = new Memcache;
	$memcache_obj = memcache_connect('localhost', 11211);
	if ($memcache_obj)
	{
		echo isPass(true);
	}
	else
	{
		echo isPass(false) . ' can not connect memcached service on localhost:11211';
	}
	echo'</p>';
}
else
{
	$error = true;
	echo isPass(false) . ' (memcache functions not exists. Test can`t be pass)';
}

//ТЕСТ НА ОТПРАВКУ EMAIL
echo'<p>videoxq send Email test ';

echo'
	<table><tr><td>
	<form target="emailframe" id="testemailform" name="testemailform" method="post" action="/sendemail.php">
		To: *<br /><input type="text" name="email" /><br />
		Subject: *<br /><input type="text" name="subj" /><br />
		Text: *<br /><textarea name="body"></textarea><br />
		<br /><input type="submit" name="send" />
	</form>

	</td><td>
		<iframe id="emailframe" name="emailframe" width="400" height="50" frameborder="0" marginwidth="0" marginheight="0"></iframe>
	</td></tr></table>
';

echo'</p>';

//ТЕСТ НА ПОДКЛЮЧЕНИЕ К СФИНКСУ
include_once($_SERVER['DOCUMENT_ROOT'] . '/../vendors/sphinxapi.php');
$sphinx = new SphinxClient();
$sphinx->SetServer('localhost', 3312);
$sphinxNsk = $sphinx->_Connect();
echo'<p>videoxq Sphinx connecting test ';
if ($sphinxNsk)
{
	echo isPass(true) . $sphinx->GetLastWarning();
	fclose($sphinxNsk);
}
else
{
	$error = true;
	echo isPass(false);
	//ОТПРАВКА УВЕДОМЛЕНИЯ НА ПОЧТУ АДМИНУ
}
echo'</p>';

$content = curlGetContent("http://" . $_SERVER['HTTP_HOST'] . "/media/index/search:" . str_replace(' ', '+', $title), $strCookies);
	echo'<p>Search engine test ';
	if (!empty($content) && strpos($content, '/view/' . $id))
	{
		echo isPass(true) . ' (searching for "' . $title . '")';
	}
	else
	{
		$error = true;
		echo isPass(false);
		//ОТПРАВКА УВЕДОМЛЕНИЯ НА ПОЧТУ АДМИНУ
	}
	echo'</p>';

$content = ob_get_contents();
ob_end_clean();
?>
<html>
<head>
<title><?php if ($error) echo 'Error - videoxq.com self-test detect errors'; else echo 'Ok - videoxq.com works properly'; ?></title>
</head>
<body onload="window.setTimeout('window.location.reload()', 60*15000)">
<h3><a href="http://videoxq.com/admin">videoxq.com</a> self-test script (<?php echo date('d.m.y H:i:s'); ?>)</h3>
<?php
	echo $content;
	phpinfo();
?>
</body>
</html>