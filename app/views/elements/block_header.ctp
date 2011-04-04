<div class="top">
<?php
if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
{
?><?php
}
else
{
	echo '<div style="float: left; width: 470px; margin-top: 5px; margin-left: 0;">';
	echo $BlockBanner->getBanner('menu');
	echo '</div>';
}
?>
    <div class="userbarWrapper"><div class="userbar">
    <div id="flags">
<?php
$langFix = '';
if (Configure::read('Config.language') == _RUS_)
{
	echo '<img title="Русский" src="/img/rus_a.gif" width="20" height="15" />';
	echo '<br /><a title="English" href="/' . _ENG_ . '.php"><img title="English" src="/img/eng.jpg" width="16" height="11" /></a>';
}
else
{
	$langFix = '_' . _ENG_;
	echo '<a title="Русский" href="/' . _RUS_ . '.php"><img title="Русский" src="/img/rus.gif" width="16" height="11" /></a>';
	echo '<br /><img title="English" src="/img/eng_a.gif" width="20" height="15" />';
}
?>
</div>
<?php
if ($authUser['userid'] == 0)
{
    ?>
<form id="UserLoginForm" method="post" action="/users/login" class="userLogin">
<fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset>
    <p><label for="UserUsername"><?php __("Login"); ?></label> (<a href="/users/register"><?php __("Registration"); ?></a> | <a href="/pays">V.I.P.</a>):<br>
    <input tabindex="1" name="data[User][username]" type="text" maxlength="100" id="UserUsername" class="textInput" /></p>
    <p><label for="UserPassword"><?php __("Password"); ?> (<a href="/users/restore"><?php __("Forgot password"); ?></a>):</label><br><input tabindex="2" type="password" name="data[User][password]" id="UserPassword" class="textInput" /></p>
    <input type="hidden" name="data[User][remember_me]" value="1" id="UserRememberMe" />
    <input type="hidden" name="redirect" value="<?php $redirectUrl = explode('=', $_SERVER['QUERY_STRING']); if (count($redirectUrl) > 1) echo '/' . $redirectUrl[1]; ?>" id="loginRedirect" />
    <p><input type="image" class="button" alt="Войти" src="/img/login.gif"></p>
</form>
<?php
}
else
{
    if (!empty($payInfo['Pay']))
    {
    	if ($payInfo["Pay"]["findate"] > time())
    		$payInfo = '<a title="' . __("Paid by", true) . ' ' . date('d.m.y H:i', $payInfo["Pay"]["findate"]) . '" href="/pays">' . __("V.I.P.", true) . '</a>';
    }
    else
    {
    	if (isset($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
    		$payInfo = ' | <a title="' . __("unlimited", true) . '" href="/pays">' . __("V.I.P.", true) . '</a>';
    	else
    		$payInfo = ' | <a href="/pays">' . __("Buy V.I.P.", true) . '</a>';
    }
?>
    	<div class="welcome">
			<?php __("Hi"); ?>, <a href="<?= $app->getUserProfileUrl($authUser['userid']) ?>"><?= $authUser['username'] ?></a>
            <?php
            if($pms>0)
            {
            	//echo '<a href="' . $app->getUserPMUrl($authUser['userid']) . '"><img src="/img/mail.gif"></a>';
            }
            echo $payInfo;?>
            <br /><a href="/users/logout"><?php __("Log out"); ?></a>
        </div>
<?php
}
?>
    </div>
    </div>

</div>
