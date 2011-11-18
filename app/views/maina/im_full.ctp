<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<a id="msgdel" href="/maina/im/del/msgid:<?=(!empty($message[0]['Pm']['pmid']))? $message[0]['Pm']['pmid'] : '0'?>">Удалить сообщение</a>
<?php if ($session->check('Message.flash'))$session->flash();?>
<?php 
$tousernames = '';
foreach(unserialize($message[0]['Pmsg']['touserarray']) as $val){
    $tousernames = (!$tousernames)? '' : $tousernames.', ';
    $tousernames.= join(', ', $val);
}

?>
<div class="im_in_full">
<div class="im_in_login_full">
    <a href="#" style="text-decoration: none;"><?=(!empty($message[0]['Pmsg']['fromusername']))? $message[0]['Pmsg']['fromusername'] : ''?></a>
</div>
<div class="im_in_time_avatar_full">
<div class="im_in_avatar_full"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
<div class="im_in_time_full"> <?=gmdate('d.n.Y h:i', $message[0]["Pmsg"]["dateline"]);?></div>
</div>
</div>
<div class="im_in_short_text_full"><?=(!empty($message[0]['Pmsg']['message']))? $message[0]['Pmsg']['message'] : ''?></div>
<div class="im_in_full_forma">
<textarea cols="83" rows="10" wrap="virtual" style="border: 1px solid #888;position: relative;" name='<?php (!empty($message[0]['Pmsg']['fromusername']))? $message[0]['Pmsg']['fromusername'] : ''?>'>
</textarea>
<p><input type="submit" name="but" value="Отправить" class="im_in_but" /></p>
</div>
<script>
$(document).ready(function() {Visibility("null");});
</script>
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
   
   
    $('#msgdel').click(
    function(event){
        event.preventDefault();
        if (!confirm('Вы точно хотите удалить это сообщение?')) {return false;}
        var link = $(this).attr("href");
        $(this).parent().parent().find("a").removeClass("current");
        $(this).addClass("current");
        
        if (link!='#') {
            $('#ins_ajax').fadeOut(555, function(){
                $(this).html('<img id="ajax_loader_icon" src="/img/ajax-loader.gif">');
                x = x + ($('#ajax_loader_icon').width())/2;
                y = y + ($('#ajax_loader_icon').height())/2;
                $('#ajax_loader_icon').attr("style","display: block; position: absolute; left: "+x+"px; top:"+y+"px");
                $(this).fadeIn(555);
                $(this).load(link,'ajax',function(){});
            });
        }
        return false;
    });   
</script>
<?=(!$isAjax)? '</div>':'';?>  