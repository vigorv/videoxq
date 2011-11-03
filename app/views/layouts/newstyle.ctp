<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php
        echo $html->css('common');
        echo $html->css('main');
        echo $html->css('themes/' . $theme_id . '/main');
        //echo $javascript->link(array('jquery.fancybox-1.3.4/jquery-1.4.3.min', 'scripts', 'validation'));
        echo $javascript->link(array('jquery-1.6.4.min', 'scripts', 'validation'));
        echo $javascript->link(array('jquery.form', 'scripts', 'validation'));
        echo $scripts_for_layout;
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        if (empty($metaExpires)) {
            //определяем последнюю пятницу
            $dayOfWeek = date('w');
            $difDay = $dayOfWeek - 5;
            if ($difDay < 0) {
                $difDay += 7;
            }
            $lastFri = mktime(20, 0, 0, date('m'), date('d') - $difDay, date('Y'));
            $metaExpires = date('r', $lastFri);
        }
        //echo '<meta http-equiv="expires" content="' . $metaExpires . '" />';
        if (empty($metaRobots)) {
            $metaRobots = 'INDEX, FOLLOW';
        }
        ?>
        <meta name="Robots" content="<?php echo $metaRobots; ?>" />
        <meta name='yandex-verification' content='41f90ac754cf4471' />
        <meta name="verify-v1" content="Q+iq7OY8RadE9126YoJFPl1cnjLTMbHmU//RrR0TTks=" />
        <meta name="keywords" content="видео, фильмы, сериалы, скачать фильм, webstream, вебстрим, videoxq.com <?php //if (isset($metaKeywords)) echo $metaKeywords;                   ?>" />
        <meta name="description" content="самый большой каталог бесплатных  видео фильмов и сериалов <?php //if (isset($metaDescription)) echo $metaDescription;                    ?>" />
        <link rel="alternate" type="application/rss+xml" title='<?php echo Configure::read('App.siteName'); ?>' href="http://videoxq.com/rss.xml" />
        <title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
        <script langauge="javascript">
            var histAPI=!!(window.history && history.pushState);
        function xLoad(elem){
            var link = $(elem).attr("href");
            if(!histAPI)
                history.pushState({}, '', link);
            else
                location.hash=link;
            if (link!='#') {
                $('.Frame_Content').load(link,'ajax',function(){});
                return false;
            }
        }
    function xNoLoad(elem){
        var link = $(elem).attr("href");
        if (link!='#') {
            $.ajax(link,'ajax',function(){});
            return false;
        }
    }

    function switchOn(obj)
    {
    	$('.currentTvIcon').removeClass('currentTvIcon');
    	$(obj).find('img').addClass('currentTvIcon');
    	return false;
    }

    function saveOption(name, value)
    {
        $.post("/maina/saveoption", {optionName: name, optionValue: value}, function(data) {
            if(data == "ok")
            {
            	$('.currentSubMenu').click();
            }
        });
        return false;
    }
    

        </script>
<style>
    #flashMessage, #authMessage {
    background: none repeat scroll 0 0 #fff;
    border: solid 1px #aaa;
    color: red;
    font-size: large;
    margin: 0 auto;
    padding: 20px 50px;
    position: absolute;
    z-index: 777;
    }
</style>

  </head>
    <body>
        <div id="wrap">
            <div id="block_top">
                <? if (isset($blocks_top))
                    echo $this->element($blocks_top) ?>
            </div>
            <div id="block_middle">
                <div id="block_right">
                    <? if (isset($blocks_right))
                        echo $this->element($blocks_right); ?>
                </div>
                <div id="block_mid">
                    <div id="block_m_top">
                        <?
                        if (isset($blocks_m_top))
                            echo $this->element($blocks_m_top);
                        ?>
                    </div>
                    <div id="main_inner">
                        <div id="block_main">
                        <div class="Frame">
		<div class="Frame_Top">
			<div class="Frame_LeftTop">
			</div>
			<div class="Frame_RightTop">
			</div>
			<div class="ClearClass">
			</div>
		</div>
		<div class="Frame_Left">
			<div class="Frame_LeftGradient">
			</div>
			<div class="Frame_Right">
				<div class="Frame_RightGradient">
				</div>
				<div class="Frame_Content">
                                    
					<?= $content_for_layout; ?>
                </div>
			</div>
		</div>
		<div class="Frame_Bottom">
			<div class="Frame_LeftBottom">
			</div>
			<div class="Frame_RightBottom">
			</div>
			<div class="ClearClass">
			</div>
		</div>
        <div class="tv_label"></div>
	</div>
                        </div>

                    </div>
                    <div id="navigation_tv">
            <?php $tvIcons->AllIcons();$tvIcons->IconsShow(array("left", "refresh", "number_6", "number_9", "number_12", "number_24", "vid_list","vid_eskiz","right"));
            //<a href="#"><img src="/img/main/refresh.png" id="icon_refresh" alt="Обновить" /></a>
            //<a href="#" onclick="switchOn(this); return saveOption('Profile.itemsView', 'list');><img src="/img/main/list.png" class="icon_list" alt="Вид отображения: Список" /></a>
           // <a href="#" onclick="switchOn(this); return saveOption('Profile.itemsView', 'eskiz');"><img src="/img/main/eskiz.png" class="icon_eskiz" alt="Вид отображения: Эскизом" /></a>
            //<a href="#"><img src="/img/main/right_arrow.png" id="icon_r_arrow" alt="Листать вправо" /></a>?>
            </div>
                </div>
            </div>

            <div class="footer">
                <div class="copy" width="100%" align="center">
                    <br />
                    <span>© «<a href="http://www.videoxq.com">videoxq.com</a>», 2007-<?php echo date('Y'); ?></span><br />
                    <span><a href="mailto:support@videoxq.com">email: support@videoxq.com</a></span><br />
                    <span><a href="/pages/reklama<?php echo $langFix; ?>"><?php __("Advertisement"); ?></a> | <a href="/pages/kontaktyi<?php echo $langFix; ?>"><?php __("Contacts"); ?></a> | <a href="/pages/nashi-partneryi<?php echo $langFix; ?>"><?php __("Partners"); ?></a></span>
                </div>
                <!--LiveInternet counter-->
                <script type="text/javascript"><!--
            document.write("<a href='http://www.liveinternet.ru/click' "+
                "target=_blank><img vspace='5' hspace='8' src='//counter.yadro.ru/hit?t14.5;r"+
                escape(document.referrer)+((typeof(screen)=="undefined")?"":
                ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                ";"+Math.random()+
                "' alt='' title='LiveInternet: показано число просмотров за 24"+
                " часа, посетителей за 24 часа и за сегодня' "+
                "border='0' width='88' height='31'><\/a>")
            //-->
                </script>
                <!--/LiveInternet-->

                <script type="text/javascript"><!--
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-16449141-2']);
            _gaq.push(['_setDomainName', '.videoxq.com']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
            //-->
                </script>

                <!-- Yandex.Metrika -->
                <script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
                <div style="display:none;"><script type="text/javascript">
            try { var yaCounter1094491 = new Ya.Metrika(1094491);
                yaCounter1094491.clickmap();
                yaCounter1094491.trackLinks({external: true});
            } catch(e){}
                    </script></div>
                <noscript><div style="position:absolute"><img src="//mc.yandex.ru/watch/1094491" alt=""
                                                              /></div></noscript>
                <!-- /Yandex.Metrika -->
            </div>
            <script type="text/javascript">(function(){var j=38057,f=false,b=document,c=b.documentElement,e=window;function g(){var a="";a+="rt="+(new Date).getTime()%1E7*100+Math.round(Math.random()*99);a+=b.referrer?"&r="+escape(b.referrer):"";return a}function h(){var a=b.getElementsByTagName("head")[0];if(a)return a;for(a=c.firstChild;a&&a.nodeName.toLowerCase()=="#text";)a=a.nextSibling;if(a&&a.nodeName.toLowerCase()!="#text")return a;a=b.createElement("head");c.appendChild(a);return a}function i(){var a=b.createElement("script");a.setAttribute("type","text/javascript");a.setAttribute("src","http://c.luxup.ru/t/lb"+j+".js?"+g());typeof a!="undefined"&&h().appendChild(a)}function d(){if(!f){f=true;i()}};if(b.addEventListener)b.addEventListener("DOMContentLoaded",d,false);else if(b.attachEvent){c.doScroll&&e==e.top&&function(){try{c.doScroll("left")}catch(a){setTimeout(arguments.callee,0);return}d()}();b.attachEvent("onreadystatechange",function(){b.readyState==="complete"&&d()})}else e.onload=d})();</script>
            <?php
            echo $cakeDebug;
            ?>

    </body>
</html>