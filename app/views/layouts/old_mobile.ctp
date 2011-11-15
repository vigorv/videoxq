<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>
        <title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
        <link rel="icon" href="favicon.ico" type="image/x-icon"/>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
        <style type="text/css">
            /* <![CDATA[ */
            a:link,a:visited,a:hover {color:#0033CC;text-decoration:none}
            .poster { float:left; padding:0 12px 0; width:79px; overflow: hidden;}
            .moviePreviewWrapper {float:left;}
            /* ]]> */
        </style>
        <script type="text/javascript">
            /* <![CDATA[ */
            /* ]]> */
        </script>
    </head>
    <body style=" margin:0;color:#333;font-size:13px;font-family:sans-serif;background-color:#fff;width:100%;" >
        <h1 style="background-color:black;padding:0 10px; margin:auto; color:white;">VideoXQ.com</h1>
        <hr size="1" noshade="1" color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;" />
        <div id="content" style="padding:5px; margin:0px;width:90%;">
            <?php if ($lang == _ENG_): ?>
                <h3><?= __('Sorry, Your mobile device didn\'t supported'); ?></h3>            
                <h4><?= __('You can open this site from computer'); ?></h4>
            <?php else: ?>
                <h3><?= __('Извините, Ваше устройство не поддерживается '); ?></h3>            
                <h4><?= __('Вы можете попасть на наш сайт используя компьютер'); ?></h4>
            <? endif; ?>
        </div>
        <div style="border-top:1px solid #999;font-size:80%;background:#EEE;text-align:center">
            <br/>
            <b>Мобильная версия</b>
            <div dir="ltr">&copy;IT-DELUXE.LTD</div>
            <table cellpadding=0 cellspacing=0 border=0><tr><td><a href="http://www.branica.com/"><img id="counter" src="http://counters.branica.com/?i=0&u=true&ox=10&oy=17&c=000000&b=transparent.png&f=Terminator.ttf&fn=true&w=110&h=35&s=10" alt="Branica" width="110" height="35" style="border:none"/><br><font color="#838383" size="-3"></a></font></td></tr></table>

        </div>
    </body>
</html>