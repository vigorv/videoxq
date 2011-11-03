<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<?php if ($session->check('Message.flash'))$session->flash();?>
<div id="im_in">
<div id="im_in_border">
<div id="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time">
<div id="im_in_login"><a href="#">Семен</a></div>
<div id="im_in_time">31 октября в 10:20</div>
</div>
<div id="im_in_theme_text">
<div id="im_in_theme">Привет</div>
<div id="im_in_short_text"><a href="#">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы ...</a></div>
<div id="im_in_check_box">
<input type="checkbox" name="check" value="1" />
</div>
</div>
</div>
<div id="im_in_border">
<div id="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time">
<div id="im_in_login"><a href="#">Семен</a></div>
<div id="im_in_time">31 октября в 10:20</div>
</div>
<div id="im_in_theme_text">
<div id="im_in_theme">Привет</div>
<div id="im_in_short_text"><a href="#">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы ...</a></div>
<div id="im_in_check_box">
<input type="checkbox" name="check" value="1" />
</div>
</div>
</div>
<div id="im_in_border">
<div id="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time">
<div id="im_in_login"><a href="#">Семен</a></div>
<div id="im_in_time">31 октября в 10:20</div>
</div>
<div id="im_in_theme_text">
<div id="im_in_theme">Привет</div>
<div id="im_in_short_text"><a href="#">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы ...</a></div>
<div id="im_in_check_box">
<input type="checkbox" name="check" value="1" />
</div>
</div>
</div>
<div id="im_in_border">
<div id="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time">
<div id="im_in_login"><a href="#">Семен</a></div>
<div id="im_in_time">31 октября в 10:20</div>
</div>
<div id="im_in_theme_text">
<div id="im_in_theme">Привет</div>
<div id="im_in_short_text"><a href="#">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы ...</a></div>
<div id="im_in_check_box">
<input type="checkbox" name="check" value="1" />
</div>
</div>
</div>
<div id="im_in_border">
<div id="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time">
<div id="im_in_login"><a href="#">Семен</a></div>
<div id="im_in_time">31 октября в 10:20</div>
</div>
<div id="im_in_theme_text">
<div id="im_in_theme">Привет</div>
<div id="im_in_short_text"><a href="#">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы ...</a></div>
<div id="im_in_check_box">
<input type="checkbox" name="check" value="1" />
</div>
</div>
</div>
<div id="im_in_border">
<div id="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time">
<div id="im_in_login"><a href="#">Семен</a></div>
<div id="im_in_time">31 октября в 10:20</div>
</div>
<div id="im_in_theme_text">
<div id="im_in_theme">Привет</div>
<div id="im_in_short_text"><a href="#">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы ...</a></div>
<div id="im_in_check_box">
<input type="checkbox" name="check" value="1" />
</div>
</div>
</div>
</div>
<div id="im_in_navigation">
<div id="im_in_navigation_href"><a href="#"> 1</a><a href="#"> 2</a><a href="#"> 3</a><a href="#"> 4</a><a href="#"> 5</a>
</div>
</div>
<script langauge="javascript">
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
