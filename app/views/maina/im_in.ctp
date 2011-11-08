<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<?php if ($session->check('Message.flash'))$session->flash();?>
<div class="im_in">
<form name="msg_checks" method="POST">
<?php
if (sizeof($messages) == 0)
{
    echo "<p style='padding-top: 15px; text-align:center;'>У Вас нет входящих сообщений</p>";
}
for ($i=0;$i < sizeof($messages);$i++)
{
$messages[$i]["Pmsg"]["title"] = mb_substr($messages[$i]["Pmsg"]["title"], 0, 50);
$messages[$i]["Pmsg"]["message"] = mb_substr($messages[$i]["Pmsg"]["message"], 0, 70);
?>
<div class="im_in_border">
<div class="im_in_avatar"><img src="http://videoxq.com/forum/image.php?u=113534&dateline=1317973359&type=thumb" />
</div>
<div class="im_in_login_time">
<div class="im_in_login"><a href="#"><?=$messages[$i]["Pmsg"]["fromusername"];?></a></div>
<div class="im_in_time"><?=gmdate('d.n.Y h:i', $messages[$i]["Pmsg"]["dateline"]);?></div>
</div>
<div class="im_in_theme_text">
<div class="im_in_theme"><?=$messages[$i]["Pmsg"]["title"]?></div>
<div class="im_in_short_text"><a href="/maina/im/full/msgid:<?=$messages[$i]["Pm"]["pmid"]?>" id="<?=$messages[$i]["Pm"]["pmid"]?>"><?=$messages[$i]["Pmsg"]["message"]?></a></div>
<div class="im_in_check_box">
<input type="checkbox" name="check" value="<?=$messages[$i]["Pm"]["pmid"]?>" />
</div>
</div>
</div>
<?php
}
?>
    
    
</form>
</div>
<?php
if (!empty($im_pagination) && $im_pagination['page_count']>1){
?>
<div class="im_pagination">
<div class="im_nav_img">
    <div class="im_pagination_href">
    <?php
        for($n=1; $n<=$im_pagination['page_count']; $n++){
            //если текущая страница, то соотвественно выделим ее ссылку в 
            //пагинации
            if ($im_pagination['page'] == $n){
                $href = '#';
                $class = ' class="current"';
            }
            else{
                $href = '/maina/im/'.$sub_act.'/page:'.$n;
                $class = '';
            }

            echo '<a href="'.$href.'"'.$class.'>'.$n.'</a>';
        }
    ?>
    </div>
</div>
<?php
}
?>
<script language="javascript">
    subact='<?=$sub_act;?>';
    $('#im_menu_act').fadeIn();
    $('#in_btn').addClass("current");
    
   if ($('#flashMessage').length > 0 ){
       var wp = $('#flashMessage').parent().width();
       var wm = $('#flashMessage').width();
       var xm = (wp/2 - wm/2) - 25 ;
       $('#flashMessage').css('left', xm+'px').show();
       $('#flashMessage').fadeOut(8000);
   }

    $('.im_pagination_href a, .im_in_short_text a').click(
    function(event){
        event.preventDefault();
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
