<?

?>
<style>
.current{
        
}    
</style>
<div id="im_menu">
<ul id="im_menu_nav">
    <li><a href="/maina/im/in" style="border-left: 1px solid #74ADE7;"<?=($sub_act == 'in')? ' class="current"':''?>>Входящие</a></li>
    <li><a href="/maina/im/out"<?=($sub_act == 'out')? ' class="current"':''?>>Исходящие</a></li>
    <li><a href="/maina/im/new"<?=($sub_act == 'new')? ' class="current"':''?>>Написать сообщение</a></li>
</ul>
<ul id="im_menu_act">   
    <li style="float:right; margin-right: 1px;"><a href="/maina/im/" id="clear">Очистить</a></li>
    <li style="float:right;border-left: 1px solid #74ADE7;"><a href="/maina/im/" id="del">Удалить выбранные</a></li>
</ul>
</div>

<script langauge="javascript">
var subact='#';
var x = ($('div.Frame').width())/2;
var y =  280;
jQuery(document).ready(function() {
    $('#im_menu_nav a').click(
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
    
    
    $('#clear').click(
        function(event){
            event.preventDefault();
            var link = $(this).attr("href");
            if (subact=='in' || subact == 'out'){
                $('#ins_ajax').fadeOut(555, function(){
                    $(this).html('<img id="ajax_loader_icon" src="/img/ajax-loader.gif">');
                    x = x + ($('#ajax_loader_icon').width())/2;
                    y = y + ($('#ajax_loader_icon').height())/2;
                    $('#ajax_loader_icon').attr("style","display: block; position: absolute; left: "+x+"px; top:"+y+"px");
                    $(this).fadeIn(555);
                    link = link + '/' + subact +'clear';
                    
                    $(this).load(link,'ajax',function(){});
                });
            }
            return false;
        });
    $('#del').click(
        function(event){
            event.preventDefault();
            var link = $(this).attr("href");
            if (subact=='in' || subact == 'out'){
                $('#ins_ajax').fadeOut(555, function(){
                    $(this).html('<img id="ajax_loader_icon" src="/img/ajax-loader.gif">');
                    x = x + ($('#ajax_loader_icon').width())/2;
                    y = y + ($('#ajax_loader_icon').height())/2;
                    $('#ajax_loader_icon').attr("style","display: block; position: absolute; left: "+x+"px; top:"+y+"px");
                    $(this).fadeIn(555);
                    link = link + '/' + subact +'del';
                    
                    $(this).load(link,'ajax',function(){});
                });
            }
            return false;
        });


});
</script>