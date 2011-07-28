<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
        <meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
        <?= $html->css('iphone/mobile'); ?>
        <?= $html->css('iphone/iblack'); ?>
        <?= $html->css('iphone/skins/tango/skin.css'); ?>
        <?= $javascript->link(array('mobile/webkit', 'mobile/jquery', 'mobile/jquery.jcarousel.min', 'mobile/iscroll.min')); ?>

        <link rel="icon" type="image/png" href=""/>
        <link rel="apple-touch-icon" href="" />
        <meta name="apple-mobile-web-app-capable" content="yes" />


        <script type="text/javascript">
            $(document).ready(function() {
               
                document.addEventListener('touchmove', function(e){ e.preventDefault(); });
                myScroll = new iScroll('Scroller');


            });

         
        </script>

    </head>
    <body>
        <div id="header">

            <a id="logo-title" href="/mobile/films"> VideoXQ </a>
            <a id="back_button"  href="#" onclick="backToHome(); return false">&laquo;</a>
        </div>
        <div id="iscroll">
            <div  id="Scroller">
                <div id="top_bars">
                <div class="bar" style="text-align: center;">
                    <form action="/mobile/search" id="searchform" method="get">
                        <input type="text" placeholder="Search film by title…" tabindex="1" id="search-input" name="s"/>
                        <input type="hidden" tabindex="2" id="search-submit-hidden" name="submit"/>
                    </form>
                </div>
                <div class="bar">
                    <a href="/mobile/films" class="button"><?= __('Films', true); ?></a>
                    <a href="/mobile/profile" class="button"><?= __('Profile', true); ?></a>
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

