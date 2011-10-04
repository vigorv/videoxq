<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html manifest="vxq.manifest"  xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
        <meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
        <?= $html->css('iphone/mobile'); ?>
        <?= $html->css('iphone/iblack'); ?>
        <?= $html->css('iphone/skins/tango/skin.css'); ?>
        <?= $javascript->link(array('mobile/webkit', 'mobile/jquery', 'mobile/iscroll.min')); ?>
        <?
        /*
        if ($android_webkitt) {
            echo $html->css('mobile/android_webkit.css');
            echo $javascript->link(array('mobile/android_webkit'));
        }
         */
        ?>
        <link rel="icon" type="image/png" href=""/>
        <link rel="apple-touch-icon" href="" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <script type="text/javascript">
<? if (!$android_webkit): ?>
                    document.addEventListener('touchmove', function(e){ e.preventDefault(); });
<? endif; ?>
                var myPager = new snowPager();
                $(document).ready(function() {
                    var sc_roll = $('#iscroll'); 
                    sc_roll.css('height',(parseInt($('#sizer').height())-45)+'px');    
                    if (window.navigator.standalone) {
                        sc_roll.css('padding-bottom', 0);
                    }
                    window.onorientationchange=updateOrientation;
                    $(window).resize(function() {
                        sc_roll.css('height',(parseInt($('#sizer').height())-45)+'px');    
                        setTimeout(function() { window.scrollTo(0, 1); }, 100);
                    });
                    $('#back_button').click(function(){ myPager.backToHome();  return false;});                
                });
        </script>
<? if (Configure::read('debug')): ?>
            <style type="text/css">
                div#header,div#iscroll { position:relative;margin-top:0}            
                body { background-color:white;}
                
            </style>
<? endif; ?>

    </head>
    <body onload="setTimeout(function() { window.scrollTo(0, 1); }, 100); <? if (!$android_webkit): ?>myScroll = new iScroll('Scroller');<? endif; ?>">
        <div id="sizer" style="position:absolute;height:100%;width:100%;z-index:0"></div>
        <div id="header">
            <a id="logo-title" href="#" onclick="window.scroll(0,1); $('#iscroll').scrollTop(0);return false;"> VideoXQ </a>
            <a id="back_button"  href="#" onclick="return false">&nbsp;&laquo;&nbsp;</a>
        </div>
        <div id="iscroll" >
            <div  id="Scroller">                
                <div id="top_bars" >
                    <div class="bar" style="text-align: center; vertical-align: bottom;">
                        <form action="/mobile/search" id="searchform" method="get">
                            <input  type="text" style="clear:left;" placeholder="<?=__('Search film by title',true);?>…" tabindex="1" id="search-input" onblur="window.scrollTo(0,1)" name="s"/>
                            <input type="hidden" tabindex="2" id="search-submit-hidden" name="submit" value="<?=__('Search',true);?>"/>
                        </form>
                    </div>
                    <div class="bar">
                        <a href="/mobile/genres" class="button"><?= __('Genres', true); ?></a>
                        <a href="/mobile/profile" class="button"><?= __('Profile', true); ?></a>
                        <div style="float:right">
                            <a href="/ru.php"><img src="/img/rus.gif" alt="ru"/></a>&nbsp;&nbsp;&nbsp;
                            <a href="/en.php"><img src="/img/eng.jpg" alt="ru"/></a>&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <ul id="home" title="VideoXQ" selected="true">
                    <? if ($session->check('Message.auth')): ?>
                        <li style="color:red"><?php $session->flash('auth'); ?></li>
                    <? endif; ?>
<?= $content_for_layout; ?>
                </ul>
                <ul id="nextScreen" >

                </ul>
            </div>
        </div>   
    </body>
</html>

