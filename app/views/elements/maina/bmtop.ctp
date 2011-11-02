<?
$m_a_s= 'class="menu_active"';
$m_a_d='style="display:block;"';
$parent_menu;

?>
<div id="Snow_menu">
    <ul>
        <li class="menu"><a <?if($parent_menu==0) echo $m_a_s;?>  href="#">Моя страница</a>
            <ul class="submenu"<?if($parent_menu==0) echo $m_a_d;?>>
                <li><a href="/<?= $controller; ?>/profile"><img class="info_image" src="/img/main/<?=$theme_id;?>/profile.png" alt="profile"/>Профиль</a></li>
                <li><a href="/<?= $controller; ?>/im"><img class="info_image" src="/img/main/<?=$theme_id;?>/email.png" alt="messages"/>Сообщения</a></li>
                <li><a href="/<?= $controller; ?>/friends"><img class="info_image" src="/img/main/<?=$theme_id;?>/friends_group.png" alt="Friends"/>Друзья</a></li>
                <li><a href="/<?= $controller; ?>/userhistory"><img class="info_image" src="/img/main/<?=$theme_id;?>/history.png" alt="History"/>История</a></li>
                <li><a href="/<?= $controller; ?>/favorites"><img class="info_image" src="/img/main/<?= $theme_id; ?>/favorite.png" alt="Favorite"/>Любимые</a></li>
                <li><a href="/<?= $controller; ?>/userrequest"><img class="info_image" src="/img/main/<?=$theme_id;?>/request.png" alt="request"/>Заявки</a></li>
                <li><a href="/<?= $controller; ?>/wishlist"><img class="info_image" src="/img/main/<?=$theme_id;?>/wishlist.png" alt="wishlist"/>Желания</a></li>
            </ul>
        </li>
        <li class="menu"><a <?if($parent_menu==1) echo $m_a_s;?> href="#">Фильмы</a>
            <ul class="submenu" <?if($parent_menu==1) echo $m_a_d;?>>
                <li><a href="/<?= $controller; ?>/filmlist"><img class="info_image" src="/img/main/<?=$theme_id;?>/films.png" alt="FilmList"/>Все Фильмы</a></li>
            </ul>
        </li>
        <li class="menu">
            <a   <?if($parent_menu==2) echo $m_a_s;?> href="#">Комюнити</a>
            <ul class="submenu"<?if($parent_menu==2) echo $m_a_d;?>>
                <li><a href="/<?= $controller; ?>/userlist"><img class="info_image" src="/img/main/<?=$theme_id;?>/community.png" alt="Users"/>Пользователи</a></li>

            </ul>
    </ul>
</div>

<script langauge="javascript">
jQuery(document).ready(function() {

    $("#Snow_menu ul li a").click(
    function(event){
        event.preventDefault();
        var client_par = $(this);
        var link = $(this).attr("href");
        if (link!='#') {
            //currentTVLink = link;
            $('.currentSubMenu').removeClass('currentSubMenu');
            $(this).addClass('currentSubMenu');
            
                var x = ($('div.Frame').width())/2;
                var y =  280;
                //alert (x);alert (y);

            $('.Frame_Content').fadeOut(555, function(){
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

            return false;
        }
        var par = $(this).parent();

        if (client_par.hasClass('inactive')) return;
        if (client_par.hasClass('menu_active')){
            //par.find('ul').first().hide('slow',
            //function(){client_par.removeClass('menu_active');
            //});
        } else{
            var cpar=$('.menu_active').first();

            cpar.removeClass('menu_active');
            client_par.addClass('menu_active');
            cpar.parent().find('ul').first().fadeOut('slow',
            function(){
                par.find('ul').first().fadeIn();//;('slow').show();
            });
        }
    });

});
</script>