<div class="top">
	<div style="position: absolute; left:0px; width: 470px; margin-top: 5px; margin-left: 0;">
<?php

if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
{
?>
    <h1><img src="/img/vusic.gif" alt="">VUSIC</h1>
<?php
}
else
{
	echo $BlockBanner->getBanner('menu');
}
?>
	</div>

    <div class="userbarWrapper"><div class="userbar">
    <?php if ($authUser['userid'] == 0):
    ?>
<form id="UserLoginForm" method="post" action="/users/login" class="userLogin">
<fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset>
    <p><label for="UserUsername">Вы</label> (<a href="/users/register">регистрация</a>, <a href="/pays">V.I.P.</a>):<br>
    <input tabindex="1" name="data[User][username]" type="text" maxlength="100" id="UserUsername" class="textInput" /></p>
    <p><label for="UserPassword">Ваш пароль (<a href="/users/restore">забыли?</a>):</label><br><input tabindex="2" type="password" name="data[User][password]" id="UserPassword" class="textInput" /></p>
    <input type="hidden" name="data[User][remember_me]" value="1" id="UserRememberMe" />
    <input type="hidden" name="redirect" value="<?php $redirectUrl = explode('=', $_SERVER['QUERY_STRING']); if (count($redirectUrl) > 1) echo '/' . $redirectUrl[1]; ?>" id="loginRedirect" />
    <p><input type="image" class="button" alt="Войти" src="/img/login.gif"></p>
</form>
    <?php else:
    $rnd = mt_rand(1000, 10000);
    ?>
        <img src="/img/vusic/40.jpg" alt="">
        <!--
        <div class="userRating"><em><?= $rnd ?></em><span><?= $rnd ?></span></div>
        -->
<?php
    if (!empty($payInfo['Pay']))
    {
    	if ($payInfo["Pay"]["findate"] > time())
    		$payInfo = 'V.I.P. доступ оплачен по ' . date('d.m.y H:i', $payInfo["Pay"]["findate"]) . ' <a href="/pays">Подробнее</a>';
    }
    else
    {
    	if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
    		$payInfo = 'V.I.P. доступ. <a href="/pays">Подробнее</a>';
    	else
    		$payInfo = 'у Вас нет V.I.P. привилегий. <a href="/pays">Купить</a>';
    }
?>
    	<div class="welcome">
			Вы вошли как <a href="<?= $app->getUserProfileUrl($authUser['userid']) ?>"><?= $authUser['username'] ?></a> | <a href="/users/logout">Выйти</a><br />
            <?php
            if($pms>0)
            {
            	//echo '<a href="' . $app->getUserPMUrl($authUser['userid']) . '"><img src="/img/mail.gif"></a>';
            }
            echo $payInfo;?>
        </div>
    <?php endif; ?>
    </div>
    </div>

</div>
<!--
<div style=" width: 100%;margin-bottom:10px">
<script type="text/javascript">
var begun_auto_pad = 150066373;
var begun_block_id = 150068489;
</script>
<script src="http://autocontext.begun.ru/autocontext2.js" type="text/javascript"></script>
</div>
-->