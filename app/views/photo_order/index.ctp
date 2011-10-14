<?php
header('Content-type: text/html; charset=utf-8');
$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $javascript->link('jquery.pngFix', false);
    $javascript->link('calendar', false);
    $javascript->link('calendar-en', false);
    $javascript->link('calendar-setup', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);
    $html->css('calendar-blue' , null, array(), false);
?>
<script type="text/javascript">
<!--
$(document).ready(function() {
    $('a[rel=fancybox]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
});

function validate_form ()
{
	valid = true;
        /*
        if ( document.photo_users.fio.value.length <= 5)
        {
                alert ( "Вы не заполнили поле 'ФИО' или оно слишком короткое." );
                valid = false;

        }
        if ( document.photo_users.type.value == "t0" )
        {
                alert ( "Пожалуйста заполните поле 'Тип фотосъемки'." );
                valid = false;
        }
        if ( document.photo_users.date.value == "")
        {
                alert ( "Пожалуйста заполните поле 'Дата фотосъемки'." );
                valid = false;
        }
        if ( document.photo_users.place.value.length <= 10)
        {
                alert ( "Вы не заполнили поле 'Место съемки' или оно слишком короткое." );
                valid = false;
        }*/
        if ( document.photo_users.phone.value.length <= 5)
        {
                alert ( "Вы не заполнили поле 'Контактный телефон' или оно слишком короткое." );
                valid = false;
        }
        if ( document.photo_users.mail.value.length <= 5)
        {
                alert ( "Вы не заполнили поле 'E-mail' или оно слишком короткое." );
                valid = false;
        }
        if ( document.photo_users.captcha.value.length <= 3)
        {
                alert ( "Вы не заполнили поле 'Проверочный код' или оно слишком короткое." );
                valid = false;
        }
        if ( document.photo_users.note.value.length <= 3)
        {
                alert ( "Вы не заполнили поле 'Примечания' или оно слишком короткое." );
                valid = false;
        }
        return valid;
}
--> 
</script>
<div id="wrap_photo">
<div id="verx_photo">
<a rel="fancybox" href="/img/photograph/pics/1.jpg"><img src="/img/photograph/pics/1_small.jpg" alt="" id="pics_photo" /></a>
<a rel="fancybox" href="/img/photograph/pics/2.jpg"><img src="/img/photograph/pics/2_small.jpg" alt="" id="pics_photo" /></a>
<a rel="fancybox" href="/img/photograph/pics/3.jpg"><img src="/img/photograph/pics/3_small.jpg" alt="" id="pics_photo" /></a>
<a rel="fancybox" href="/img/photograph/pics/4.jpg"><img src="/img/photograph/pics/4_small.jpg" alt="" id="pics_photo" /></a>
<a rel="fancybox" href="/img/photograph/pics/5.jpg"><img src="/img/photograph/pics/5_small.jpg" alt="" id="pics_photo" /></a>
<a rel="fancybox" href="/img/photograph/pics/6.jpg"><img src="/img/photograph/pics/6_small.jpg" alt="" id="pics_photo" /></a>
</div>
<p style="padding-left: 10px;font-size:13px;"><b>Наши телефоны: &nbsp;</b> 911, 8-800-2000-600 (Пётр)</p>
<div id="levo_photo">
<?php if (isset($_GET['ok'])) {echo "<h2>Спасибо за Вашу заявку. Мы свяжемся с Вами в ближайшее время</h2>";}?>
<form method="post" action="/photo_orders/users" name="photo_users" onsubmit="return validate_form ()">
<div id="levo_input">
<p>Представьтесь: </p>
<p>Тип фотосессии: </p>
<p>Дата фотосъемки: </p>
<p>Место фотосъемки: </p>
<p>Длительность: </p>
<p>Контак. телефон: <i id="photo_red">*</i></p>
<p>E-mail: <i id="photo_red">*</i></p>
<p>Проверочный код: <i id="photo_red">*</i></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>Примечания: <i id="photo_red">*</i></p>
</div>
<div id="right_input">
<p><input type="text" name="fio" size="35" value="" /></p>
<p><select size="1" name="tip" style="width: 140px;"></p>
<option selected value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Выберите тип: &nbsp;&nbsp;&nbsp;&nbsp;</option>
<option value="Пьянка">Пьянка</option>
<option value="Крокодил Гена">Крокодил Гена</option>
<option value="Шапокляк">Шапокляк</option>
<option value="Крыса Лариса">Крыса Лариса</option>
</select>
<p><input type="text" name="date" id="fromuserdate" size="35" /></p>
<p><input type="text" name="place" value="" size="35" /></p>
<p><input type="text" name="duration" size="2" maxlength="2" /> ч.</p>
<p><input type="text" name="phone" size="35" /></p>
<p><input type="text" name="mail" size="35" /></p>
<p><input type="text" name="captcha" size="35" /></p>
<p><img src="/users/captcha" /></p>
</div>
<p align="right" style="padding-right: 20px;"><textarea name="note" style="width:370px;height:100px;"></textarea></p>
<p><i id="photo_red">*</i> - Обязательные поля для заполнения.</p>
<p align="center"><input type="submit" name="button" value="Оформить" /></p>
</form>
<script type="text/javascript">
Calendar.setup({
      inputField     :    "fromuserdate",     // id of the input field
      ifFormat       :    "%Y-%m-%d",      // format of the input field
      align          :    "Br",           // alignment
      timeFormat     :    "24",
      showsTime      :    true,
      singleClick    :    true
    });
</script>
</div>
<div id="main_photo">
<p><h2 style="text-align:center; font-size:2.5em;">Заказ фотографа</h2></p>
<div id="text_photo">
<p align="justify">
ЖОПАЖО ПАЖОПАЖОП АЖОПАЖОП АЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖ ОПАЖОПАЖ ОПАЖОПА ЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖО ПАЖОПАЖОПАЖОПА
ЖОПАЖО ПАЖОПАЖО  ПАЖОПАЖОП АЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖ ОПАЖОПАЖО ПАЖОПА
ЖОПАЖО ПАЖОПАЖОПАЖ ПАЖОПАЖО ПАЖОПАЖОПАЖОПА
ЖОПАЖО ПАЖОПАЖОПАЖОПАЖОПАЖОПАЖО ПАЖОПАЖОПА</p>
<p align="justify">
ЖОПАЖО ПАЖОПАЖОП АЖОПАЖОП АЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖ ОПАЖОПАЖ ОПАЖОПА ЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПА ОПАЖОПАЖОПАЖОПАЖ ОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖО  ПАЖОПАЖОПАЖ ОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖ ОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖ ПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖО ПАЖОПАЖОП АЖОПАЖОП АЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖ ОПАЖОПАЖ ОПАЖОПА ЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОП АЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖО  ПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖ ОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖ ПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖОПАЖО ПАЖОПАЖОПАЖОПАЖОПА</p>
<p align="justify">
ЖОПАЖО ПАЖОПАЖОП АЖОПАЖОП АЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖ ОПАЖОПАЖ ОПАЖОПА ЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖО  ПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖ ОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖ ПАЖОПАЖОПАЖОПАЖОПАЖОПА
ЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПАЖОПА</p>
</script>
<!--</a><div id="feedback_photo">
<p><h2 style="text-align:center;">Связаться с нами</h2></p>
<br />
<div id="levo_input">
<p>E-mail: </p>
<p>Проверочный код: </p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>Текст сообщения: </p>
</div>
<div id="right_input">
<form method="post" action="/photo_orders/feedback">
<p><input type="text" name="email"/></p>
<p><input type="text" name="captcha" /></p>
<p><img src="/users/captcha" /></p>
<p>&nbsp;<textarea name="text" style="width:330px;height:100px;"></textarea></p>
<p align="center"><input type="submit" name="button" value="Связаться" /></p>
</form>
</div>-->
</div>
</div>
</div>
<!--</a>-->
</div>
</div>