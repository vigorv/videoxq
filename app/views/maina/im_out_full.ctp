<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<?php if ($session->check('Message.flash'))$session->flash();?>
<div id="im_in_full">
<form name="msg_answer" method="POST">
<?php
if (sizeof($messages) == 0)
{
    echo "<p style='padding-top: 15px; text-align:center;'>У Вас нет исходящих сообщений</p>";
}
printf('
<div class="im_in_border">
<div class="im_in_avatar_full"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div class="im_in_login_time_full">
<div class="im_in_login_full"><a href="#">%s</a></div>
<div class="im_in_time_full">%s</div>
<div class="im_in_theme_full">Тема: %s</div>
</div>
<div class="im_in_short_text_full">%s</div>
<div class="im_in_full_forma">
<textarea cols="83" rows="10" style="border: 1px solid #888;" name="$messages[$i]["Pmsg"]["fromusername"]">
</textarea>
<p><input type="submit" name="but" value="Отправить" class="im_in_but" />
</div>
</div>
</div>', $messages[$i]["Pmsg"]["fromusername"], $messages[$i]["Pm"]["pmid"], $messages[$i]["Pmsg"]["title"], $messages[$i]["Pmsg"]["message"],$messages[$i]["Pm"]["pmid"]);
?>
</form>
</div>
<script language="javascript">
    subact='<?=$sub_act;?>';
    $('#im_menu_act').fadeOut();

   if ($('#flashMessage').length > 0 ){
       var wp = $('#flashMessage').parent().width();
       var wm = $('#flashMessage').width();
       var xm = (wp/2 - wm/2) - 25 ;
       $('#flashMessage').css('left', xm+'px').show();
       $('#flashMessage').fadeOut(8000);
   }
</script>
<?=(!$isAjax)? '</div>':'';?>  