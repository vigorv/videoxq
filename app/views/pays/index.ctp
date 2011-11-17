<div id="page_pay">
<img src="/img/vip_dostyp.png" />
<?php
if ($authUser['userid'] > 0)
{
    $geoPlace = '<h4>' . __('Your geographical location', true);
    if (!empty($geoInfo['Geoip']['region_id']))
    {
    	$geoPlace .= ' - ' . implode(' ', array($geoInfo['city'], $geoInfo['region'])) . '. ';;
    }
    else
    {
    	$geoPlace .= ' ' . __('not identified', true) . '. ';
    }

    if (!empty($authUser['userid']))
    {
    	$adminLink = '<a href="/media/geoerr">' . __("contact administrator", true) . '</a>';
    }
    else
    {
    	$adminLink = __("contact administrator", true);
    }
    echo $geoPlace . '<br />' . __("If your geographical location is incorrect", true) . ', ' . $adminLink . '.</h4>
    <p>' . __('Depends on this is available for download by you of certain films', true) . '</p>';

    if (!$authUser["agree"])
    {
    	echo __('WARNING! Pay for the service V.I.P. Access can be by taking a', true) . ' <a target="_blank" href="/pages/agreement">' . __('user agreement', true) . '</a>.';
    	echo'<p><center><a href="/pays/agree" onclick=\'return confirm("' . __("Are You sure?", true) . '");\'>' . __('I accept the agreement', true) . '</a></center></p>';
    	$summ = -1;
    }

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
<div style="border: 1px solid #FF8000;padding:10px;">
<?php __('Here you can pay for VIP access'); ?> <?php echo $payDesc[$perWeek];?> <?php __('or'); ?> <?php echo $payDesc[$perMonth];?>.
<?php __('You can pay via an electronic cash service ROBOxchange in any convenient electronic payment system, via SMS or e-card'); ?><br />
<?php __('Please note that the payment systems charge a fee for the transfer of funds'); ?>.
</div>
<p>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<p>Выберите способ оплаты </p>
<ul><b><?php __('Pay/extend access via SMS'); ?>:</b>
<form name="oplata" method="POST" action="#">

</form>
	<li><a href="/pays/sms/<?php echo $smsPerWeek;?>"><?php echo $smsPayDesc[$smsPerWeek];?></a> (<?php echo $smsPerWeek;?> у.е.)</li>
	<li><a href="/pays/sms/<?php echo $smsPerMonth;?>"><?php echo $smsPayDesc[$smsPerMonth];?></a> (<?php echo $smsPerMonth;?> у.е.)</li>
</ul>
<td>
<div>
	<img src="/img/smscoin.gif" hspace="10" width="90" alt="SMScoin" title="SMScoin" />
</div>
</td></tr>
<tr><td>
<ul><b><?php __('Pay/extend access by electronic money or e-cards'); ?>:</b>
	<li><a href="/pays/erbx/<?php echo $erbxPerWeek;?>"><?php echo $payDesc[$erbxPerWeek];?></a> (<?php echo $erbxPerWeek;?> RUR)</li>
	<li><a href="/pays/erbx/<?php echo $erbxPerMonth;?>"><?php echo $payDesc[$erbxPerMonth];?></a> (<?php echo $erbxPerMonth;?> RUR)</li>
</ul>
</td>
<td>
<div>
	<img src="/img/robox.gif" hspace="10" width="40" alt="Robox" title="Robox" />
</div>
</td></tr>
<tr><td>
<ul><b><?php __('Pay/extend access through the service');?> PayPal:</b>
	<li><a href="/pays/paypal/<?php echo $paypalPerWeek;?>"><?php echo $paypalPayDesc[$paypalPerWeek];?></a> (<?php echo $paypalPerWeek;?> <?php echo Configure::read('paypal.currency');?>)</li>
	<li><a href="/pays/paypal/<?php echo $paypalPerMonth;?>"><?php echo $paypalPayDesc[$paypalPerMonth];?></a> (<?php echo $paypalPerMonth;?> <?php echo Configure::read('paypal.currency');?>)</li>
</ul>
<td>
<div>
	<img src="/img/paypal_logo.gif" hspace="10" vspace="2" height="50" alt="Assist" title="PayPal" />
</div>
</td></tr>
<tr><td>
<ul><b><?php __('Pay/extend access through the service');?> Assist:</b>
	<li><a href="/pays/assist/<?php echo $assistPerWeek;?>"><?php echo $assistPayDesc[$assistPerWeek];?></a> (<?php echo $assistPerWeek;?> RUR)</li>
	<li><a href="/pays/assist/<?php echo $assistPerMonth;?>"><?php echo $assistPayDesc[$assistPerMonth];?></a> (<?php echo $assistPerMonth;?> RUR)</li>
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
<?php __("assist description1"); ?>

<?php __("assist description2"); ?>

<?php __("assist description3"); ?>

<?php __("You may"); ?> <a title="<?php __('check the authenticity of the certificate');?>" target="_blank" href="https://sealinfo.thawte.com/thawtesplash?form_file=fdf/thawtesplash.fdf&dn=WWW.ASSIST.RU&lang=en"><?php __('check the authenticity of the certificate');?></a> <?php __("of Thawte server"); ?>.
	</p>
	<p>
<?php __('When paying by credit card order a refund is made on the card with which payment was made.'); ?>
	</p>
	<table width="100%" border="0">
	<tr align="center">
		<td><img width="265" alt="<?php __('logos payment systems');?>" title="<?php __('logos payment systems');?>" src="/img/mps_logos.png" /></td>
	</tr>
	</table>
</td></tr>

</table>

<p><?php __('On V.I.P. access, write to the'); ?> <a href="mailto:vip@videoxq.com">vip@videoxq.com</a></p>

<?php
	if (!empty($lst))
	{
		echo'<ul><b>' . __('Last payments', true) . ':</b>';
		foreach ($lst as $l)
		{
			switch ($l['Pay']['paysystem'])
			{
				case _PAY_PAYPAL_:
					$valute = Configure::read('paypal.currency');
				break;
				case _PAY_SMSCOIN_:
					$valute = 'у.е.';
				break;
				case _PAY_ASSIST_:
					$valute = 'RUR';
				break;
				default:
					$valute = 'WMR';
			}

			echo '<li>№ ' . $l["Pay"]['id'] . ' ' . __('date', true) . ' ' . date('d.m.y H:i', $l["Pay"]['paydate']) . ' (' . $l["Pay"]['summ'] . ' ' . $valute . ')';
			if ($l["Pay"]['findate'] > time()) echo ' - ' . __('Paid by', true) . ' ' . date('d.m.y H:i', $l["Pay"]['findate']) . '</li>';
		}
		echo '</ul>';
	}
}
else
{
?>
<p>
<?php __('You chose to pay V.I.P. access'); ?> <?php echo $payDesc[$summ];?> <?php __('amount'); ?> <?php echo $summ;?> WMR
</p>
<p>
<a href="<?php echo $url;?>"><?php __('Pay'); ?></a>
</p>
<?php
}

}

//<h3 style="color: red;">Внимание, сервис на стадии запуска. Будет доступен в ближайшее время.</h3>
?>
<h3><?php __('Benefits V.I.P.');?>:</h3>
<p>
1) <?php __('No ads');?>
<?php
//<br />2) Закачка файлов с наших серверов в 3 потока
//<br />3) Для пользователей сети HOSTEL дает возможность качать без взымания денег за траффик.
?>
</p>
<h3><?php __('How to become a V.I.P.');?>:</h3>
<p>1) <a href="/users/register"><?php __('Register');?></a> <?php __('on our website');?>.
<br />2) <a href="/users/login"><?php __('Sign In'); ?></a>, <?php __('using your login and password');?>.
<br />3) <?php __('Go to the'); ?> <a href="/pays"><?php __('payment page'); ?></a> V.I.P. <?php __('accessa');?> (<?php __('see link at the top right corner of the site');?>)
<br />4) <?php __('Choose the amount of payment to any available for the payment of the payment system'); ?>
 <?php
//	Configure::read('costPerDay') . ' WMR(' . Configure::read('descPerDay') . '), ' .
	echo $payDesc[$perWeek] . ' ' . __('or', true) . ' ' . $payDesc[$perMonth] . '.';
?>
<br />5) <?php __('You can pay:');?>
<br />&nbsp; &nbsp; - <?php __('via service');?> SMScoin по <b>SMS</b>. <span style="color: red">***</span>
<br />&nbsp; &nbsp; - <?php __('via e-cash service ROBOxchange'); ?> (<b>WebMoney</b>, <b>Яндекс-деньги</b> <?php __('etc.');?>) <span style="color: red">***</span>
<br />&nbsp; &nbsp; - <?php __('via service');?> PayPal.
<br />&nbsp; &nbsp; - <?php __('via service');?> Assist. <span style="color: red">***</span>
<br />6) <?php __('Follow instructions of the payment system');?>
</p>
<h3><?php __('Support');?>:</h3>
<p><?php __('On V.I.P. access, write to the'); ?> <a href="mailto:vip@videoxq.com">vip@videoxq.com</a></p>

<p><span style="color: red">***</span> <?php __('Price for V.I.P. access is fixed and displayed for payment.');?>
<br /><?php __('The tenure of V.I.P. depends on the size of the payment.');?>
<br /><?php __('To extend V.I.P. - The payment procedure must be repeated.');?>
</p>
	<table width="100%" cellpadding="20" border="0">
	<tr align="center">
		<td width="50%"><img width="120" alt="MasterCard SecureCode" src="/img/MasterCard_SecureCode.JPG" /></td>
		<td><img width="116" alt="Verfied by VISA" src="/img/Verfied_by_VISA.JPG" /></td>
	</tr>
	</table>
</div>
