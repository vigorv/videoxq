<?php
/*
<!--title_begin-->
	Малая Москва (2008 DVDRip)
<!--title_end-->
<!--img_begin-->
	http://rumedia.ws/uploads/posts/2009-04/thumbs/1238914375_0404abbd34343.jpg
<!--img_end-->
<!--link_begin-->
	http://rumedia.ws/index.php?newsid=1614
<!--link_end-->
<!--desc_begin-->
	К отставному русскому военному офицеру приезжает дочка. Отец, рассказывая о своей жене, вспоминает ежедневную жизнь в \"Малой Москве\" в шестидесятых годах XX века. Тогда молодой капитан вместе ...
<!--desc_end-->
$echo=file_get_contents('http://rumedia.ws/rnn54.php');
$echo=iconv('windows-1251','utf-8',$echo);
$matches = array();
preg_match_all('/-->([^<]+)<!--/', $echo, $matches);
if (isset($matches[1]) && !empty($matches[1]))
{
	$echo = '<center>
		<a title="' . htmlspecialchars($matches[1][3]). '" target="_blank" href="' . $matches[1][2] . '">
		<img src="' . $matches[1][1] . '" /><br />' . $matches[1][0] . '
		</a></center>
	';
	echo $echo;
}
//*/

?>

<ul>Администрирование переходов по страницам
<li><a href="/roadmaps/reset">Сбросить связи</a> (<a href="/roadmaps/reset/1">Сбросить связи и переходы</a>)</li>
<li><a href="/roadmaps/link">Восстановить связи</a></li>
<li><a href="/roadmaps/alist">Классификатор (алиасы)</a></li>
<li><a href="/roadmaps/graph">Переходы</a></li>
</ul>