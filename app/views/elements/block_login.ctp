<?php
if (empty($authUser['userid']))
{
?>
	<div id="logindiv">
                <table border="0" cellspacing="0" cellpadding="0" width="219">
                  <tbody>
                    <tr>
                      <td class="border1"> </td>
                      <td>
<form id="UserLoginForm" method="post" action="/users/login" class="userLogin">
<fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset>
    <p><label for="UserUsername">Вы</label><br />
    <input tabindex="1" name="data[User][username]" type="text" maxlength="100" id="UserUsername" class="textInput" /></p>
    <p><label for="UserPassword">Ваш пароль</label><br />
    <input tabindex="2" type="password" name="data[User][password]" id="UserPassword" class="textInput" /></p>
    <input type="hidden" name="data[User][remember_me]" value="1" id="UserRememberMe" />
    <input type="hidden" name="redirect" value="<?php $redirectUrl = explode('=', $_SERVER['QUERY_STRING']); if (count($redirectUrl) > 1) echo '/' . $redirectUrl[1]; ?>" id="loginRedirect" />
    <p><input type="image" class="button" alt="Войти" src="/img/login.gif"></p>
</form>
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
		</div>

<?php
}
?>
                <table border="0" cellspacing="0" cellpadding="0" width="260">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" id="c21" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1"> </td>
                      <td>
<?php
//if ($block_media_genres['allowDownload'])
{
	echo '
    <p>Всего в базе <strong>' . $app->pluralForm($filmStats['count'], array('фильм', 'фильма', 'фильмов')) . '</strong>
	<br>общей продолжительностью <strong>' . $app->timeFormat($filmStats['size']) . '</strong>
    </p>
	';
}
?>
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" id="c22" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
			<br />
<?php
$placeNamePrefix = '';
if ($isWS)
	$placeNamePrefix = 'WS';

$placeName = $placeNamePrefix . 'right1';
echo $BlockBanner->getBanner($placeName);
