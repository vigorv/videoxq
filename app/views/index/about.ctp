<?php
    $javascript->link('jquery.fancybox-1.0.0', false);
    $javascript->link('jquery.pngFix', false);
    $script = "$(function() {
       $('a[rel=fancybox]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
   });";
    $javascript->codeBlock($script, array('inline' => false));
    $script = "$(function() {
       $('a[rel=posters]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
   });";
   $javascript->codeBlock($script, array('inline' => false));
   $html->css('fancy', null, array(), false);

?>
<div class="viewright"></div><ul id="menu">
    <li ><a href="/media">Видео</a></li>
    <li class="active"><strong><a href="/index/about">О нас</a></strong></li>
    <li ><a href="/people">Люди</a></li>
    <li ><a href="/basket">Загрузки</a></li>
</ul>
<div class="contentColumns">
<div id="cColumn_left" class="contentColumn_19p">
    <h3 class="small_item_box_pretitle">Underground</h3>
    <div id="Undeground_fresh" class="small_item_box">
         <h4>Свежее:</h4>
         <div class="ud_small_item">
             <a href="/img/about/andaluz.jpg" rel="fancybox" onclick="return stopdivx();"><img height="100px" width="160px" src="/img/about/andaluz.jpg"/><br/></a>
             <label>Андалузский щенок</label>
         </div>
         <div class="ud_small_item">
              <a href="/img/about/underground.jpg" rel="fancybox" onclick="return stopdivx();"><img height="100px" width="160px"  src="/img/about/underground.jpg"/><br/></a>
             <label>Андеграунд</label>
         </div>
    </div>
    <div id="Undeground_authors" class="small_item_box"  >
        <h4>Авторы:</h4>
        <div class="ud_small_item">
              <a href="/img/about/emir_kusturica.jpg" rel="fancybox" onclick="return stopdivx();"><img height="100px" width="80px" src="/img/about/emir_kusturica.jpg"/><br/></a>
              <a href="#"><label>Эмир Кустурица</label></a>
        </div>
        <div class="ud_small_item">
              <a href="/img/about/luis_bunuel.jpg" rel="fancybox" onclick="return stopdivx();"><img height="100px" width="80px" src="/img/about/luis_bunuel.jpg"/><br/></a>
              <a href="#"><label>Луис Бунюэль</label></a>
        </div>




    </div>


</div>
<div id="cColumn_main" class="contentColumn_59p">
    <div class="news_items">
        <div id="news_ID" class="news_item">
            <div class="news_header">
                <a href="#" class="news_title">День Победы</a>
                <span class="news_date">09.05.2011</span>
                <div class="news_header_r">
                    <a href="#" class="news_author">маршал Жуков</a>
                </div>
            </div>
            <div class="news_content">
                <img class="news_content_img"  height="120px" src="/img/about/9may.jpg">
                В апреле 1945 года советские войска вплотную подошли к Берлину. Немецкие войска занимали оборону вдоль западных берегов рек Одер и Нейсе. На подступах к Берлину и в самом городе была сосредоточена группировка войск, имевшая в своём составе 62 дивизии (в том числе 48 пехотных, 4 танковые и 10 моторизованных), 37 отдельных пехотных полков и около 100 отдельных пехотных батальонов, а также значительное количество артиллерийских частей и подразделений. Эта группировка насчитывала около миллиона человек, 1 500 танков, 10 400 орудий и миномётов, 3 300 боевых самолётов...
                <a href="#">Читать далее...</a>
            </div>
        </div>
         <div id="news_ID" class="news_item">
            <div class="news_header">
                <a href="#" class="news_title">Чемпионат по баскетболу В Бердске</a>
                <span class="news_date">25.05.2011</span>
                <div class="news_header_r">
                    <a href="#" class="news_author">Майкл Джордан</a>
                </div>
            </div>
            <div class="news_content">
                <img class="news_content_img" height="100px" width="160px" src="/img/about/basket_arena.jpg">
                Чтобы добиться хороших результатов в спорте, бойцовскими качествами необходимо овладевать с детства. Да и спортивные навыки тоже, как правило, закладываются в юном возрасте. В Бердске очень внимательно относятся к резерву будущих чемпионов – в городе успешно действуют пять детско-юношеских спортивных школ, которые...
                <a href="#">Читать далее...</a>
            </div>
        </div>




    </div>

<div class="pages">
<h3>1 &nbsp; <a href="/media/index/direction:desc/action:index/page:2">2</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:3">3</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:4">4</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:5">5</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:6">6</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:7">7</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:8">8</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:9">9</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:2" title="следующая">&gt;</a> &nbsp; <a href="/media/index/direction:desc/action:index/page:18" title="прокрутка вперед">&gt;&gt;</a> &nbsp; ... &nbsp; <a href="/media/index/direction:desc/action:index/page:766" title="последняя">766</a></h3></div>
</div>

<div id="cColumn_right" class="contentColumn_19p" style="text-align:right;">
    <h3 class="small_item_box_pretitle">Наша деятельность</h3>
    <div id="Undeground_fresh" class="small_item_box">
         <h4>Фотографии:</h4>
         <div class="ud_small_item">
             <a href="/img/about/atoll.jpg" rel="fancybox" onclick="return stopdivx();"><img height="100px" width="160px" src="/img/about/atoll.jpg"/></a><br/>
             <label>Переезд датацентра</label>
         </div>
         <div class="ud_small_item">
             <a href="/img/about/white.jpg" rel="fancybox" onclick="return stopdivx();"><img  height="100px" width="160px" src="/img/about/white.jpg"/></a><br/>
             <label>Сахар из песка</label>
         </div>
    </div>
     <div id="Undeground_fresh" class="small_item_box">
         <h4>Видеозаписи:</h4>
         <div class="ud_small_item">
             <a href="#" ><img height="100px" width="160px" src="#"/></a><br/>
             <label>Название 1</label>
         </div>
         <div class="ud_small_item">
             <a href="#"  ><img height="100px" width="160px" src="#"/></a><br/>
             <label>Название 2</label>
         </div>
    </div>
    <div id="Undeground_fresh" class="small_item_box">
         <h4>Онлайн-трансляции:</h4>

	<script>
		function addVideo(path) {
			document.getElementById("ipad").href=path;
			document.getElementById("flowplayerdiv").style.display="";
			$f("ipad", "/js/flowplayer/flowplayer-3.2.5.swf",
								{plugins: {
									h264streaming: {
										url: "/js/flowplayer/flowplayer.pseudostreaming-3.2.5.swf"
												 }
	                             },
								clip: {
									provider: "h264streaming",
									autoPlay: true,
									scaling: "fit",
									autoBuffering: true,
									scrubber: true
								},
								canvas: {
									// remove default canvas gradient
									backgroundGradient: "none",
									backgroundColor: "#000000"
								}
					}
						).ipad();
			return false;
		}
	</script>
	<script type="text/javascript" src="/js/flowplayer/flowplayer-3.2.4.min.js"></script>
	<script type="text/javascript" src="/js/flowplayer/flowplayer.ipad-3.2.1.js"></script>


    <div class="ud_small_item">
						<div id="flowplayerdiv">
<!--
    <a href="#" style="display:block;width:160px;height:100px" onclick="return addVideo('http://92.63.196.82:82/d/Dobrunya_Nikitich_I_Zmey_Gorunych/404/Dobrunya_Nikitich_I_Zmey_Gorunych_404.mp4');" id="ipad">
-->
    <a href="#" style="display:block;width:160px;height:100px" onclick="return addVideo('http://92.63.196.52:80/video1.mp4');" id="ipad">
    	<img height="100" width="160"  src="/img/about/basket_motion.jpg"/>
    </a>
    					</div>
						<!--
						<div id="flowplayerdiv" style="display: none"><center>
						<h4><a href="#" onclick="document.getElementById(\'flowplayerdiv\').style.display=\'none\'; return false;">выключить проигрыватель</a></h4>
						-->

             <label>1/8 финала</label>
         </div>
    </div>




    </div>


</div>



</div>
</div>
