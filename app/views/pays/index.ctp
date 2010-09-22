<div style="margin-left: 20px">
<h2>V.I.P. доступ</h2>
<?php
if ($authUser['userid'] > 0)
{
    $geoPlace = '<h4>Ваше местоположение';
    if (!empty($geoInfo['Geoip']['region_id']))
    {
    	$geoPlace .= ' - ' . implode(' ', array($geoInfo['city'], $geoInfo['region'])) . '. ';;
    }
    else
    {
    	$geoPlace .= ' не определено. ';
    }

    if (!empty($authUser['userid']))
    {
    	$adminLink = '<a href="/media/geoerr">свяжитесь с администратором</a>';
    }
    else
    {
    	$adminLink = 'свяжитесь с администратором';
    }
    echo $geoPlace . '<br />По ошибкам определения вашего географического местоположения, ' . $adminLink . '.</h4>
    <p>От этого зависит доступность для скачивания вами определенной группы фильмов</p>';

}
if ($summ < 0)
{
//echo'<h2>Здравствуйте'. ((empty($authUser["userid"])) ? '' : ', ' . $authUser["username"]) . '.</h2>';
?>
<?php

}
else
{

//echo'<h2>Здравствуйте'. ((empty($authUser["userid"])) ? '' : ', ' . $authUser["username"]) . '.</h2>';
if ($summ == 0)
{
?>
<p>
Здесь вы можете оплатить V.I.P. доступ <?php echo $payDesc[$perWeek];?> или <?php echo $payDesc[$perMonth];?>.
Прием оплаты осуществляется через электронную кассу сервиса ROBOxchange в любой удобной для вас системе электронных платежей, по SMS или по электронной карте<br />
Обратите внимание, что платежные системы взимают комиссию за перевод средств.
</p><p>
<table cellspacing="0" cellpadding="0" border="0">
<tr><td>
<ul><b>Оплатить/продлить доступ по SMS:</b>
	<li><a href="/pays/sms/<?php echo $smsPerWeek;?>"><?php echo $smsPayDesc[$smsPerWeek];?></a> (<?php echo $smsPerWeek;?> у.е.)</li>
	<li><a href="/pays/sms/<?php echo $smsPerMonth;?>"><?php echo $smsPayDesc[$smsPerMonth];?></a> (<?php echo $smsPerMonth;?> у.е.)</li>
</ul>
<td>
<div>
	<img src="/img/smscoin.gif" hspace="10" width="90" alt="SMScoin" title="SMScoin" />
</div>
</td></tr>
<tr><td>
<ul><b>Оплатить/продлить доступ электронными деньгами:</b>
	<li><a href="/pays/index/<?php echo $perWeek;?>"><?php echo $payDesc[$perWeek];?></a> (<?php echo $perWeek;?> WMR)</li>
	<li><a href="/pays/index/<?php echo $perMonth;?>"><?php echo $payDesc[$perMonth];?></a> (<?php echo $perMonth;?> WMR)</li>
</ul>
</td>
<td>
<div>
	<img src="/img/robox.gif" hspace="10" width="40" alt="Robox" title="Robox" />
</div>
</td></tr>
<tr><td>
<ul><b>Оплатить/продлить доступ с помощью сервиса Assist:</b>
	<li><a href="/pays/assist/<?php echo $assistPerWeek;?>"><?php echo $assistPayDesc[$assistPerWeek];?></a> (<?php echo $assistPerWeek;?> руб.)</li>
	<li><a href="/pays/assist/<?php echo $assistPerMonth;?>"><?php echo $assistPayDesc[$assistPerMonth];?></a> (<?php echo $assistPerMonth;?> руб.)</li>
</ul>
<td>
<div>
	<img src="/img/assist_logo.gif" hspace="10" vspace="2" width="156" alt="Assist" title="Assist" />
	<table width="100%"><tr valign="middle">
	<td><img src="/img/beeline.jpg" hspace="10" vspace="2" width="50" alt="Beeline" title="Beeline" /></td>
	<td><img src="/img/paycash.jpg" hspace="10" width="50" alt="PayCash" title="PayCash" /></td>
	<td><img src="/img/webmoney.jpg" hspace="10" vspace="2" height="50" alt="Webmoney" title="Webmoney" /></td>
	</tr></table>
</div>
</td></tr>
<tr><td colspan="2">
	<p>
Оплата по банковским картам через систему электронных платежей Assist производится путем переадресации на сайт <a target="_blank" title="assist.ru" href="http://www.assist.ru">www.assist.ru</a>. В системе ASSIST безопасность платежей обеспечивается использованием SSL протокола для передачи конфиденциальной информации от Пользователя на сервер системы ASSIST для дальнейшей обработки. Дальнейшая передача информации осуществляется по закрытым банковским сетям высшей степени защиты. Сбор и обработка полученных конфиденциальных данных Пользователя (реквизиты карты, регистрационные данные и т.д.) производится в процессинговом центре. Таким образом, никто, в том числе администрация VideoXQ, не может получить персональные и банковские данные клиента, включая информацию о его покупках, сделанных в других магазинах. Для защиты информации от несанкционированного доступа на этапе передачи от клиента на сервер системы ASSIST используется протокол SSL 3.0, сертификат сервера (128 bit) выдан компанией Thawte - признанным центром выдачи цифровых сертификатов. Вы можете <a title="проверить подлинность сертификата" target="_blank" href="https://sealinfo.thawte.com/thawtesplash?form_file=fdf/thawtesplash.fdf&dn=WWW.ASSIST.RU&lang=en">проверить подлинность сертификата</a> сервера.
	</p>
	<table width="100%" border="0">
	<tr align="center">
		<td><img width="265" alt="логотипы платежных систем" src="/img/mps_logos.png" /></td>
	</tr>
	</table>
</td></tr>

</table>

<p>По всем вопросам, касательно V.I.P. доступа, пишите на <a href="mailto:vip@videoxq.com">vip@videoxq.com</a></p>

<?php
	if (!empty($lst))
	{
		echo'<ul><b>Последние платежи:</b>';
		foreach ($lst as $l)
		{
			switch ($l['Pay']['paysystem'])
			{
				case _PAY_SMSCOIN_:
					$valute = 'у.е.';
				break;
				case _PAY_ASSIST_:
					$valute = 'RUR';
				break;
				default:
					$valute = 'WMR';
			}

			echo '<li>№ ' . $l["Pay"]['id'] . ' от ' . date('d.m.y H:i', $l["Pay"]['paydate']) . ' (' . $l["Pay"]['summ'] . ' ' . $valute . ')';
			if ($l["Pay"]['findate'] > time()) echo ' - доступ оплачен по ' . date('d.m.y H:i', $l["Pay"]['findate']) . '</li>';
		}
		echo '</ul>';
	}
}
else
{
?>
<p>
Вы выбрали оплату V.I.P. доступа <?php echo $payDesc[$summ];?> в размере <?php echo $summ;?> WMR
</p>
<p>
<a href="<?php echo $url;?>">Оплатить</a>
</p>
<?php
}

}

//<h3 style="color: red;">Внимание, сервис на стадии запуска. Будет доступен в ближайшее время.</h3>
?>
<h3>Преимущества V.I.P.:</h3>
<p>
1) Отсутствие рекламы
<?php
//<br />2) Закачка файлов с наших серверов в 3 потока
//<br />3) Для пользователей сети HOSTEL дает возможность качать без взымания денег за траффик.
?>
</p>
<h3>Как стать V.I.P.:</h3>
<p>1) <a href="/users/register">Зарегистрироваться</a> на нашем сайте.
<br />2) <a href="/users/login">Войти</a> под своими логином и паролем.
<br />3) Перейти на <a href="/pays">страницу оплаты</a> V.I.P. доступа (ссылка в правом верхнем углу сайта)
<br />4) Выбрать размер оплаты в любой, доступной для оплаты платежной системе
<?php
//	Configure::read('costPerDay') . ' WMR(' . Configure::read('descPerDay') . '), ' .
	echo $payDesc[$perWeek] . ' или ' . $payDesc[$perMonth] . '.';
?>
<br />5) Прием оплаты осуществляется через:
<br />&nbsp; &nbsp; - электронную кассу сервиса ROBOxchange (<b>WebMoney</b>, <b>Яндекс-деньги</b> и т.п.) <span style="color: red">***</span>
<br />&nbsp; &nbsp; - сервис SMScoin по <b>SMS</b>. <span style="color: red">***</span>
<br />&nbsp; &nbsp; - сервис Assist. <span style="color: red">***</span>
<br />6) Cледовать инструкциям платежной системы.
</p>
<h3>Поддержка:</h3>
<p>По всем вопросам, касательно V.I.P. доступа, пишите на <a href="mailto:vip@videoxq.com">vip@videoxq.com</a></p>

<p><span style="color: red">***</span> Цена за V.I.P. доступ фиксирована и отображена при оплате.
<br />Срок пребывания в V.I.P. зависит от размера оплаты.
<br />Чтобы продлить V.I.P. - процедуру оплаты нужно повторить.
</p>
	<table width="100%" cellpadding="20" border="0">
	<tr align="center">
		<td width="50%"><img width="120" alt="MasterCard SecureCode" src="/img/MasterCard_SecureCode.JPG" /></td>
		<td><img width="116" alt="Verfied by VISA" src="/img/Verfied_by_VISA.JPG" /></td>
	</tr>
	</table>
</div>
