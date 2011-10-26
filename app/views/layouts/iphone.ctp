<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html manifest="vxq.manifest"  xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
        <meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
        <?= $html->css('iphone/mobile'); ?>
        <?= $html->css('iphone/iblack'); ?>
        <?= $html->css('iphone/skins/tango/skin.css'); ?>
        <?= $javascript->link(array('mobile/webkit', 'mobile/jquery')); ?>
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
    //document.addEventListener('touchmove', function(e){ e.preventDefault(); });
<? endif; ?>
    var myPager = new snowPager();
    myPager.link = window.location;
    $(document).ready(function() {
        $.ajaxSetup({ timeout: 10000 });
        //var sc_roll = $('#iscroll'); 
        //sc_roll.css('height',(parseInt($('#sizer').height())-43)+'px');    
        if (window.navigator.standalone) {
            //sc_roll.css('padding-bottom', 0);
        }
        window.onorientationchange=updateOrientation;
        $(window).resize(function() {
            //sc_roll.css('height',(parseInt($('#sizer').height())-43)+'px');    
            //setTimeout(myScroll.refresh(),1000);
            //   setTimeout(function() { window.scrollTo(0, 1); }, 1000);            
        });
        //$('#back_button').click(function(){ myPager.backToHome();  return false;});                
    });
        </script>
        <? if (Configure::read('debug')): ?>
        <style type="text/css">
            body { background-color:white;}
        </style>
        <? endif; ?>

    </head>
    <body onload="<? if ($action!='films'): ?>
            setTimeout(function() { window.scrollTo(0, 1); }, 100); 
         <? endif; ?>">
        <div id="sizer" style="position:absolute;height:100%;width:100%;z-index:0"></div>
        <div id="header">
            <a id="logo-title" href="/mobile" <?php /*
            onclick="window.scroll(0,1); $('#iscroll').scrollTop(0);return false;"
           */ ?>> VideoXQ </a>

            <a id="back_button"  href="" onclick="return false">&nbsp;&laquo;&nbsp;</a>
            <div style="right:0; position:absolute; top:0; font-size:25px; line-height: 41px; font-weight:900;">
                <? /*
                  <a id="r_button" style="color:white;text-decoration: none; " href="" onclick="
                  //myScroll.refresh();
                  setTimeout(function() { window.location.reload();}, 100); return false">&real;</a>&nbsp;
                  <a id="r_button" style="color:white;text-decoration: none; " href="" onclick="myScroll.refresh();setTimeout(function() { window.scrollTo(0, 1); }, 100); return false">&uarr;</a>&nbsp;
                 * 
                 */ ?>

                <? //                <a id="up_button" style="color:white;text-decoration: none;"  href="" onclick=" myScroll.refresh();setTimeout(function() { window.scrollTo(0, 1); }, 100);return false">&uarr;</a>&nbsp;
                ?>
            </div>

        </div>
        <div id="iscroll" >
            <div  id="Scroller">                                
                <div id="top_bars" >
                      <?php if (!isset($hide_search_bar)): ?>
                      <div class="bar" style="text-align: center; vertical-align: bottom;">
                      <form action="/mobile/search" id="searchform" method="get">
                      <input  type="search" style="clear:left;" placeholder="<?= __('Search film by title', true); ?>…" tabindex="1" id="search-input"  name="s"/><br/>
                      <input type="submit" tabindex="2"  style="font-size:1.5em; margin:4px auto;" id="search-submit-hidden" name="submit" value="<?= __('Search', true); ?>"/>
                      </form>
                      </div>
                      <?php endif; ?>

                    <div class="bar">
                        <a href="/mobile/genres" class="button"><?= __('Genres', true); ?></a>
                        <?
                        /* <a href="/mobile/profile" class="button"><?= __('Profile', true); ?></a>                         
                        <div style="float:right">
                            <a style="text-decoration:none" href="/ru.php">&nbsp;<img src="/img/rus.gif" alt="ru"/>&nbsp;</a>&nbsp;
                            <a style="text-decoration:none" href="/en.php">&nbsp;<img src="/img/eng.jpg" alt="ru"/>&nbsp;</a>&nbsp;
                        </div>*/
                            ?>
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
                <div id="footer">
                    <br/>
                    <a style="font-size:26px" href="/mobile/ver?id=1" ><?=__("Click for full version of site",true);?></a><br/>
                    <br/>
                    <span><a href="mailto:support@videoxq.com">email: support@videoxq.com</a></span><br />           
                </div>
            </div>
        </div>   
    </body>
</html>

