<?php
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

        if ( document.photo_users.fio.value.length <= 5)
        {
                alert ( "Вы не заполнили поле 'ФИО' или оно слишком короткое." );
                valid = false;

        }
        
}
--> 
</script>
<div id="wrap_photo">
<div id="levo_photo">
<b><p>Свадьбы</p></b>
<p><a rel="fancybox" href="../img/photograph/pics/1.jpg"><img src="../img/photograph/pics/1_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/2.jpg"><img src="../img/photograph/pics/2_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/3.jpg"><img src="../img/photograph/pics/3_small.jpg" alt="" id="pics_photo" /></a></p>
<b><p>Похороны</p></b>
<p><a rel="fancybox" href="../img/photograph/pics/1.jpg"><img src="../img/photograph/pics/1_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/2.jpg"><img src="../img/photograph/pics/2_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/3.jpg"><img src="../img/photograph/pics/3_small.jpg" alt="" id="pics_photo" /></a></p>
</div>
<div id="main_photo">
<center><img src="../img/photograph/header.jpg" /></center>
<p><h2 style="text-align:center;">Заказ фотографа</h2></p>
<div id="text_photo">
<p align="justify">
<?php if (isset($gopa)) {
echo '
<p>Вы ввели не корректный проверочный код повторите попытку снова.</p>
<p><h2 style="text-align:center;">Оформить заявку</h2></p>
<form method="post" action="/photo_orders/users" name="photo_users" onsubmit="return validate_form ()">
<div id="levo_input">
<p>Представьтесь: <i id="photo_red">*</i></p>
<p>Тип фотосессии: <i id="photo_red">*</i></p>
<p>Дата фотосъемки: <i id="photo_red">*</i></p>
<p>Место фотосъемки: <i id="photo_red">*</i></p>
<p>Длительность: </p>
<p>Контактный телефон: <i id="photo_red">*</i></p>
<p>E-mail: </p>
<p>Проверочный код: <i id="photo_red">*</i></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>Что-то хотите добавить: </p>
</div>
<div id="right_input">
<p><input type="text" name="fio" size="50" value="ФИО" /></p>
<p><select size="1" name="tip"></p>
<option selected value="t0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Выберите тип: &nbsp;&nbsp;&nbsp;&nbsp;</option>
<option value="Пьянка">Пьянка</option>
<option value="Крокодил Гена">Крокодил Гена</option>
<option value="Шапокляк">Шапокляк</option>
<option value="Крыса Лариса">Крыса Лариса</option>
</select>
<p><input type="text" name="date" id="fromuserdate" /></p>
<p><input type="text" name="place" value="Адрес" size="50" /></p>
<p><input type="text" name="duration" size="2" maxlength="2" /> ч.</p>
<p><input type="text" name="phone" /></p>
<p><input type="text" name="mail" /></p>
<p><input type="text" name="captcha" /></p>
<p><img src="/users/captcha" /></p>
<p><textarea name="note" style="width:330px;height:100px;"></textarea></p>
<p><i id="photo_red">*</i> - Обязательные поля для заполнения.</p>
<p align="center"><input type="submit" name="button" value="Оформить" /></p>
</div>
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
<div id="feedback_photo">
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
</div>
</div>
';}
else
{   
    header("Location: /photo_orders/?ok=1");
    
} ?></p>
</div>
</div>
<div id="pravo_photo">
<b><p>Пьянки</p></b>
<p><a rel="fancybox" href="../img/photograph/pics/1.jpg"><img src="../img/photograph/pics/1_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/2.jpg"><img src="../img/photograph/pics/2_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/3.jpg"><img src="../img/photograph/pics/3_small.jpg" alt="" id="pics_photo" /></a></p>
<b><p>Праздники</p></b>
<p><a rel="fancybox" href="../img/photograph/pics/1.jpg"><img src="../img/photograph/pics/1_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/2.jpg"><img src="../img/photograph/pics/2_small.jpg" alt="" id="pics_photo" /></a></p>
<p><a rel="fancybox" href="../img/photograph/pics/3.jpg"><img src="../img/photograph/pics/3_small.jpg" alt="" id="pics_photo" /></a></p>
</div>
</div>