<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<div id="im_in_full">
<div id="im_in_avatar_full"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div id="im_in_login_time_full">
<div id="im_in_login_full"><a href="#">Семен</a></div>
<div id="im_in_time_full">31 октября в 10:20</div>
<div id="im_in_theme_full">Тема: Привет</div>
</div>
<div id="im_in_short_text_full">Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфы 
Знафывфыв фыв фывфы в фыв фывфывфывфыв фыв фыв фывфыЗнафывфыв фыв фывфы в фыв 
фывфывфывфыв фыв фыв фывфы</div>
<div id="im_in_full_forma">
<textarea cols="83" rows="10" style="border: 1px solid #888;">
</textarea>
<p><input type="submit" name="but" value="Ответить" id="im_in_but" />
</div>
</div>
<script langauge="javascript">
jQuery(document).ready(function(){
    var subact='<?=$sub_act;?>';
});
</script>
<?=(!$isAjax)? '</div>':'';?>  