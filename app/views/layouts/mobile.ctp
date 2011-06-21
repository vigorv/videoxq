<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>
<title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<style type="text/css">
/* <![CDATA[ */
a:link,a:visited,a:hover {color:#0033CC;text-decoration:none}
/* ]]> */
</style>
<script type="text/javascript">
/* <![CDATA[ */
if(typeof ytm == "undefined") var ytm = {};
ytm.startTime = new Date().getTime();
ytm.iref = {};
ytm.nextiref_ = 1;
/* ]]> */
</script>
</head>
<body style="color:#333;font-size:13px;padding-left:3px; font-family:sans-serif;margin:0;background-color:#fff" >

<img src="http://Logo" alt="videoxqLogo" width="58" height="20" style="border:0;margin:0px;" />
<div id="Top_menu">
    <a href="main">Главная</a>
    <a href="news">Новости</a>
    <a href="account">Войти</a>
</div>

<form id="searchForm" action="/mobile/search" method='get' style="padding:5px 0; margin:0 5px;">
<input name="lang" type="hidden" value="RU" />
<input name="client" type="hidden" value="" />
<input accesskey="*" name="search" type="text" size="12" maxlength="100" style="color:#333;padding:0;font-family:sans-serif;width:65%" value="" />
<input type="submit" name="submit" value="Поиск" style="padding:0;color:black;margin-top:2px;font-size:100%" />
</form>

<hr size="1" noshade size=1 color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;" />

<div id="content">
    <? echo $content_for_layout;?>
</div>


<div style="border-top:1px solid #999;font-size:80%;background:#EEE;">
<br/>
<a href="obr">Отзывы</a><br/>
<a href="help">Справка</a><br/>
Язык:<a href="/lang/">Русский</a><br/>
<a href="/terms">Условия использования и политика конфиденциальности</a><br/>
<a href="">Полная версия</a>
<br/>
</div>
<div style="border-top:1px solid #999;font-size:80%;background:#EEE;text-align:center">
<br/>
<b>Мобильная версия</b> 
<div dir="ltr">&copy;IT-DELUXE.LTD</div>
</div>
</body>
</html>