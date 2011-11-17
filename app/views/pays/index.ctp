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
<div class="pay_text_border">
<?php __('Here you can pay for VIP access'); ?> <?php echo $payDesc[$perWeek];?> <?php __('or'); ?> <?php echo $payDesc[$perMonth];?>.
<?php __('You can pay via an electronic cash service ROBOxchange in any convenient electronic payment system, via SMS or e-card'); ?>.
<p><?php __('Please note that the payment systems charge a fee for the transfer of funds'); ?>.</p>
</div>
<p>
<style>#block_sms, #block_robox, #block_paypal, #block_assist {visibility: hidden;position:absolute; width:800px; padding-top:50px;}</style>
<script>function show(a)
{
 var obj=document.getElementById('block_'+a);
 var obj2=document.getElementById('block_sms'); 
 var obj3=document.getElementById('block_robox');
 var obj4=document.getElementById('block_paypal'); 
 var obj5=document.getElementById('block_assist');
     
 if (a != 'default')
 {
  obj2.style.visibility='hidden';
  obj3.style.visibility='hidden';
  obj4.style.visibility='hidden';
  obj5.style.visibility='hidden';
  obj.style.visibility='visible';
  }
}
</script>
<?php 
echo '<script>


</script>';
?>
<p>Выберите способ оплаты </p>
<select name="vubor_oplatu" style="width: 300px;" onchange="javascript:show(this.value);return false;">
  <option value="default">&nbsp;</option>
  <option value="sms">SMS Coin</option>
  <option value="robox">Robox</option>
  <option value="paypal">PayPal</option>
  <option value="assist">Assist</option>
</select>
<div id="block_sms">
<img src="/img/smscoin.png" class="img_logo" />
<div class="pay_border">
<form name="sms">
<p><input type="radio" name="choice" value="/pays/sms/<?php echo $smsPerWeek;?>" /><?php echo $smsPayDesc[$smsPerWeek];?> (<?php echo $smsPerWeek;?> у.е.)</p>
<p><input type="radio" name="choice" value="/pays/sms/<?php echo $smsPerMonth;?>" /><?php echo $smsPayDesc[$smsPerMonth];?> (<?php echo $smsPerMonth;?> у.е.)</p>
</div>
<div class="pay_button" onclick="go(document.sms.choice)"><p>Оплатить</p></div>
</form>
</div>
<div id="block_robox">
<img src="/img/robox.gif" class="img_logo" />
<div class="pay_border">
<form name="erbx">
<p><input type="radio" name="choice" value="/pays/erbx/<?php echo $erbxPerWeek;?>" /><?php echo $payDesc[$erbxPerWeek];?> (<?php echo $erbxPerWeek;?> RUR)</p>
<p><input type="radio" name="choice" value="/pays/erbx/<?php echo $erbxPerMonth;?>" /><?php echo $payDesc[$erbxPerMonth];?> (<?php echo $erbxPerMonth;?> RUR)</p>
</div>
<div class="pay_button" onclick="go(document.erbx.choice)"><p>Оплатить</p></div>
</form>
</div>
<div id="block_paypal">
<img src="/img/paypal.png" class="img_logo" />
<div class="pay_border">
<form name="paypal">
<p><input type="radio" name="choice" value="/pays/paypal/<?php echo $paypalPerWeek;?>" /><?php echo $paypalPayDesc[$paypalPerWeek];?> (<?php echo $paypalPerWeek;?> <?php echo Configure::read('paypal.currency');?>)</p>
<p><input type="radio" name="choice" value="/pays/paypal/<?php echo $paypalPerMonth;?>" /><?php echo $paypalPayDesc[$paypalPerMonth];?> (<?php echo $paypalPerMonth;?> <?php echo Configure::read('paypal.currency');?>)</p>
</div>
<div class="pay_button" onclick="go(document.paypal.choice)"><p>Оплатить</p></div>
</form>
</div>
<div id="block_assist">
<img src="/img/assist.png" class="img_logo" />
<div class="pay_border">
<form name="assist">
<p><input type="radio" name="choice" value="/pays/assist/<?php echo $assistPerWeek;?>" /><?php echo $assistPayDesc[$assistPerWeek];?> (<?php echo $assistPerWeek;?> RUR)</p>
<p><input type="radio" name="choice" value="/pays/assist/<?php echo $assistPerMonth;?>" /><?php echo $assistPayDesc[$assistPerMonth];?> (<?php echo $assistPerMonth;?> RUR)</p>
</div>
<div class="pay_button" onclick="go(document.assist.choice)"><p>Оплатить</p></div>
</form>
</div>
<script>function go(a)
{
  for (var i=0; i < a.length; i++)
    if (a[i].checked) return window.location.href = a[i].value;

  return null;
}</script>
<div class="pay_text_border" style="margin-top: 300px;">
<?php __("assist description1"); ?>

<?php __("assist description2"); ?>

<?php __("assist description3"); ?>

<?php __("You may"); ?> <a title="<?php __('check the authenticity of the certificate');?>" target="_blank" href="https://sealinfo.thawte.com/thawtesplash?form_file=fdf/thawtesplash.fdf&dn=WWW.ASSIST.RU&lang=en"><?php __('check the authenticity of the certificate');?></a> <?php __("of Thawte server"); ?>.
</p>
<p>
<?php __('When paying by credit card order a refund is made on the card with which payment was made.'); ?>
</p>
</div>
<div id="card_logos">
<img alt="<?php __('logos payment systems');?>" title="<?php __('logos payment systems');?>" src="/img/mps_logos.png" /></td>
</div>
<p style="float: right;"><?php __('On V.I.P. access, write to the'); ?> <a href="mailto:vip@videoxq.com">vip@videoxq.com</a></p>

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
<p><h3><?php __('Benefits V.I.P.');?>:</h3></p>
<div class="pay_text_border">
<p>
1) <?php __('No ads');?>
<?php
//<br />2) Закачка файлов с наших серверов в 3 потока
//<br />3) Для пользователей сети HOSTEL дает возможность качать без взымания денег за траффик.
?>
</p>
</div>
<p><h3><?php __('How to become a V.I.P.');?>:</h3></p>
<div class="pay_text_border">
<p>1) <a href="/users/register"><?php __('Register');?></a> <?php __('on our website');?>.</p>
<p>2) <a href="/users/login"><?php __('Sign In'); ?></a>, <?php __('using your login and password');?>.</p>
<p>3) <?php __('Go to the'); ?> <a href="/pays"><?php __('payment page'); ?></a> V.I.P. <?php __('accessa');?> (<?php __('see link at the top right corner of the site');?>)</p>
<p>4) <?php __('Choose the amount of payment to any available for the payment of the payment system'); ?></p>
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
</div>
<p><h3><?php __('Support');?>:</h3></p>
<div class="pay_text_border"><p><?php __('On V.I.P. access, write to the'); ?> <a href="mailto:vip@videoxq.com">vip@videoxq.com</a></p>

<p><span style="color: red">***</span> <?php __('Price for V.I.P. access is fixed and displayed for payment.');?>
&nbsp;<?php __('The tenure of V.I.P. depends on the size of the payment.');?>
&nbsp;<?php __('To extend V.I.P. - The payment procedure must be repeated.');?>
</p>
</div>
<div style="margin: 0 auto; width: 550px;">
<img alt="MasterCard SecureCode" src="/img/MasterCard_SecureCode.JPG" />
<img alt="Verfied by VISA" src="/img/Verfied_by_VISA.JPG" style="padding-left: 250px;" />
</div>
</div>
