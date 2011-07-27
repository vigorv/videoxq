<div class="contentCol">
<h2><?php __("Auth"); ?></h2>
<?php
//$html->css('style', null, array(), false);
echo $form->create('User', array('action' => 'login' , 'class' => 'reg'));
?>
<p><label for="UserUsername"><?php __("Your Login"); ?><em class="required">*</em> :</label><br>
<?php //echo $form->error('username'); ?>
<?php echo $form->text('username', array('class' => 'textInput')); ?>
</p>
<p><label for="UserPassword"><?php __("Your Password"); ?><em class="required">*</em> :</label><br>
<?php //echo $form->error('password'); ?>
<?php echo $form->password('password', array('class' => 'textInput')); ?>
<input type="hidden" name="data[User][remember_me]" value="1" id="UserRememberMe" />
</p>
<br>
<?php
echo $form->end(__("Sign In", true));
?>
<p><a href=/users/restore><?php __("Forgot password"); ?>?</a></p>
</div>
<?php
/*
<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
    Также Вы можете войти используя:
    <a href="https://loginza.ru/api/widget?token_url=<?= Router::url($this->here, true); ?>" class="loginza">
        <img src="http://loginza.ru/img/providers/yandex.png" alt="Yandex" title="Yandex">
        <img src="http://loginza.ru/img/providers/google.png" alt="Google" title="Google Accounts">
        <img src="http://loginza.ru/img/providers/vkontakte.png" alt="Вконтакте" title="Вконтакте">
        <img src="http://loginza.ru/img/providers/mailru.png" alt="Mail.ru" title="Mail.ru">
        <img src="http://loginza.ru/img/providers/twitter.png" alt="Twitter" title="Twitter">
        <img src="http://loginza.ru/img/providers/loginza.png" alt="Loginza" title="Loginza">
        <img src="http://loginza.ru/img/providers/myopenid.png" alt="MyOpenID" title="MyOpenID">
        <img src="http://loginza.ru/img/providers/openid.png" alt="OpenID" title="OpenID">
        <img src="http://loginza.ru/img/providers/webmoney.png" alt="WebMoney" title="WebMoney">
    </a>
*/