<div id="user_panel">
</div>

<div id="showdown_menu">
    <ul>
        <li><a href="/maina/profile"><?php __('Profile'); ?></a></li>
        <li><a href="#">Фильмотека<img src="" alt="&#8659;"/></a>
            <ul>
                <li><a href="#">Избранное <img src="" alt="&#8658;"/></a>
                    <ul>
                        <li><a class="inactive" href="/maina/FilmList">Все фильмы</a></li>
                        <li><a class="inactive" href="/maina/tags">Метки</a>
                    </ul>
                </li>
                <li><a  href="/maina/userhistory">История скаченного</a></li>
                <li><a class="inactive"  href="/maina/request">Заявки</a></li>
                <li><a class="inactive" href="/maina/wishlist">Хочу посмотреть</a></li>
            </ul>
        </li>
        <li><a class="inactive" href="/maina/backstage">Закулисы</a>
        </li>
        <li><a class="inactive" href="/maina/friends">Друзья</a>
        </li>
    </ul>



</div>

<script langauge="javascript">

    $("#showdown_menu ul li  a").click(
    function(event){
        event.preventDefault();
        var client_par = $(this);
        var link = $(this).attr("href");
        if (link!='#') {
            $('.Frame_Content').load(link,'ajax',function(){});
            return false;
        }
        var par = $(this).parent();
        if (client_par.hasClass('inactive')) return;
        if (client_par.hasClass('menu_active')){
            par.find('ul').first().slideUp('slow',
            function(){client_par.removeClass('menu_active');
            });
        } else{
            client_par.addClass('menu_active');
            par.find('ul').first().slideDown('slow').show();
        }

    });

    $("#showdown_menu ul li a img").hover(
    function(){
        var client_par = $(this).parent();
        if (client_par.hasClass('inactive')) return;
        var par=client_par.parent();
        if( !(client_par.hasClass('menu_active'))){
            client_par.addClass('menu_active');
            par.find('ul').first().slideDown('slow').show();
        }
    },
    null);


    $("#showdown_menu ul li").hover(
    function(){},
    function(){
        var par=$(this);
        var client_par = $(this).find('a').first();
        if (client_par.hasClass('inactive')) return;
        par.find('ul').first().slideUp('slow',function(){
            client_par.removeClass('menu_active');
        });
    });



</script>