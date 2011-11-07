<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<?php if ($session->check('Message.flash'))$session->flash();?>
<div class="im_in">
<form name="msg_checks" method="POST">
<?php
if (sizeof($messages) == 0)
{
    echo "<p style='padding-top: 15px; text-align:center;'>У Вас нет исходящих сообщений</p>";
}
for ($i=0;$i < sizeof($messages);$i++)
{
$messages[$i]["Pmsg"]["title"] = substr($messages[$i]["Pmsg"]["title"], 0, 50);
$messages[$i]["Pmsg"]["message"] = substr($messages[$i]["Pmsg"]["message"], 0, 70);
printf('
<div class="im_in_border">
<div class="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div class="im_in_login_time">
<div class="im_in_login"><a href="#">%s</a></div>
<div class="im_in_time">%s</div>
</div>
<div class="im_in_theme_text">
<div class="im_in_theme">%s</div>
<div class="im_in_short_text"><a href="/out_full/" id="%s">%s</a></div>
<div class="im_in_check_box">
<input type="checkbox" name="check" value="%s" />
</div>
</div>
</div>', $messages[$i]["Pmsg"]["fromusername"], $messages[$i]["Pm"]["pmid"], $messages[$i]["Pmsg"]["title"],$messages[$i]["Pm"]["pmid"], $messages[$i]["Pmsg"]["message"],$messages[$i]["Pm"]["pmid"]);
}
?>
</form>
</div>
<?php include "navigation_page.ctp"; ?>
<script language="javascript">
    subact='<?=$sub_act;?>';
    $('#im_menu_act').fadeIn();
   if ($('#flashMessage').length > 0 ){
       var wp = $('#flashMessage').parent().width();
       var wm = $('#flashMessage').width();
       var xm = (wp/2 - wm/2) - 25 ;
       $('#flashMessage').css('left', xm+'px').show();
       $('#flashMessage').fadeOut(8000);
   }    
</script>
<?=(!$isAjax)? '</div>':'';?>  