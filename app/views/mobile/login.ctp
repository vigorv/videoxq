<?if (isset($error)):?>
<li>
    <?=$error;?>
</li>
<?endif;?>

<form action="/mobile/login" id="loginform" class="form" method="post" style="">
    <input type="hidden" value="1" name="remember_me"/>
    <li>
        
        <input type="text" placeholder="<?= __('E-mail', true); ?>" tabindex="2"  name="e-mail" value=""
               />
    </li>
    <li>
        <input type="password" placeholder="<?= __('Password', true); ?>" tabindex="3"  name="password" value="" onblur="window.scroll(0,1)"/>
    </li>
    <p>
        <input id="submit_button" type="submit"  tabindex="2" id="search-submit-hidden" name="submit" value="<?= __('Auth', true); ?>"/>
    </p>
</form>
<?
/*
<li>
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
</li>
*/