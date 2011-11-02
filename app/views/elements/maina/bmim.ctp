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
    <li style="float:right; margin-right: 1px;"><a href="#" id="clear">Очистить</a></li>
    <li style="float:right;border-left: 1px solid #74ADE7;"><a href="#" id="del">Удалить выбранные</a></li>
</ul>
</div>

<script langauge="javascript">
jQuery(document).ready(function() {
    var subact='<?=$sub_act;?>';
    $('#im_menu_nav a').click(
    function(event){
        event.preventDefault();
        var link = $(this).attr("href");
        $(this).parent().parent().find("a").removeClass("current");
        $(this).addClass("current");
        
        if (link!='#') {
                var x = ($('div.Frame').width())/2;
                var y =  280;
            $('#ins_ajax').fadeOut(555, function(){
                /*
                var x = ($('.Frame_Content').width())/2;
                var y =  ($('.Frame_Content').height())/2;
                */

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
        alert(subact);
    });
});
</script>