<div id="content">
<?php
if (!empty($action))
{
	echo'<h2>Ваше сообщение отправлено. Спасибо, что сообщили об ошибке.</h2>';
}
else
{
?>
<h3>Уважаемый пользователь, <?php echo $authUser['username'];?>!</h3>
Если сайт неверно определил ваше географическое местоположение, пожалуйста, сообщите об этом администратору.
От этого зависит доступность для скачивания вами определенной группы фильмов
<h2>Сообщить об ошибке</h2>
<?php
//$html->css('style', null, array(), false);
echo '<form action="/media/geoerr/send" class="reg" method="post">';
$site = Configure::read('App.siteUrl');
$geoPlace = '';
if (!empty($geoInfo['Geoip']['region_id']))
{
	$geoPlace .= implode(' ', array($geoInfo['city'], $geoInfo['region']));
}
else
{
	$geoPlace .= 'не определено';
}
echo $form->textarea('msg', array('class' => 'textInput', 'value' => "Здравствуйте!\nСайт {$site} определил мое географическое местоположение как '{$geoPlace}'.\nНа самом деле я нахожусь в другом городе/регионе.\n\n[укажите в каком]")); ?></p>
<br>
<?php
echo $form->end('Сообщить об ошибке');
}
?>
</div>
