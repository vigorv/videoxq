<?php //выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
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
    if (mb_strlen($messages[$i]["Pmsg"]["title"]) > 20)
    {
$messages[$i]["Pmsg"]["title"] = mb_substr($messages[$i]["Pmsg"]["title"], 0, 20)." ...";
    }
if (mb_strlen($messages[$i]["Pmsg"]["message"]) > 30)
    {
$messages[$i]["Pmsg"]["message"] = mb_substr($messages[$i]["Pmsg"]["message"], 0, 30)." ...";
    }
echo '<div class="im_in_border" id="'.$messages[$i]["Pm"]["pmid"].'">';
?>
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

<input name="js" type="hidden" value="no" id="js" />
<input name="button" type="submit" value="Отправить" id="send" /> 
</form>
</div>
 <?php
 //изменение цвета граунда при новом сообщении
echo "<script>var newmsg = [];
$(document).ready(function(){
 $('.im_in_border').ready(function() 
    { 
"; 
for ($i=0;$i < sizeof($messages);$i++)
 {
    if(!empty($new_msg_id[$i]["pm"]["pmid"]))
    {
    
    echo "$('#".$new_msg_id[$i]["pm"]["pmid"]."').css('background', '#E1E8F2');";
}
}
echo "
});
    
 });</script>";
?>
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
            //вывод нужных иконок, включая стрелок влево, вправо.
            echo '
            <script>
$(document).ready(function() {Visibility(["refresh", "number_6", "number_9", "number_12", "number_24","left", "right"]);});
</script>
<a href="'.$href.'"'.$class.'>'.$n.'</a>';
        }
    ?>
    </div>
</div>
</div>
<?php
}
//если нет навигации.
else
{
    echo '<script>
$(document).ready(function() {Visibility(["refresh", "number_6", "number_9", "number_12", "number_24"]);});
</script>';
}
?>
<script language="javascript">

$("#send").ready(function (){
      $.ajax({
         type: "POST",
         url: "/maina/im/check/",
         cache: false,
         success: function(response){
             if(response != 0){
             $("#resp").text(response).show().fadeIn(1500);
             $(".new_message").show().fadeIn(1500);
             }
             }
          });
          return false;                                                        
    }
);
//проверка сообщений каждые 45 секунд
setInterval(Check_time, 45000);
function Check_time(){
      $.ajax({
         type: "POST",
         url: "/maina/im/check/",
         cache: false,
         success: function(response){
             if(response != 0){
             $("#resp").text(response).show().fadeIn(1500);
             $(".new_message").show().fadeIn(1500);
             }
             }
          });
          return false;                                                        
    }
//по умолчанию конверт скрыт
$(".new_message").hide();
subact='<?=$sub_act;?>';
saveOptionNoAction('Profile.im_subact', subact);
$('#im_menu_act').fadeIn();
$('#in_btn').addClass("current");
    
centerAndFadeFlashMessage();

$('.im_pagination_href a, .im_in_short_text a').click(
function(event){
    event.preventDefault();
    var link = $(this).attr("href");
    $(this).parent().parent().find("a").removeClass("current");
    $(this).addClass("current");

    if (link!='#') {
        $('#ins_ajax').fadeOut(555, function(){
//            $(this).showAjaxLoader();
//            $(this).load(link,'',function(){});
                container = $(this);
                container.showAjaxLoader();
                if(xhr!=null){ xhr.abort();}
                xhr = $.ajax({
                    url : link,
                    type: "POST",
                    success : function(responseText) {
                        container.html(responseText);
                    }
                });
        });
    }
    return false;
});

</script>
</div>
<?=(!$isAjax)? '</div>':'';?>
