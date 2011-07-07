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
        <h1 style="background-color:black;padding:0 10px; margin:0; color:#388;">VideoXQ.com</h1>
        <form id="searchForm" action="/mobile/search" method='get' style="padding:5px;text-align: center;">
            <input name="lang" type="hidden" value="<?= $lang; ?>"/>
            <input name="client" type="hidden" value="" />
            <input accesskey="*" name="search" type="text" size="12" maxlength="100" style="color:#333;padding:0;font-family:sans-serif;width:65%" value="" />
            <input type="submit" name="submit" value="<?= __('Search', true); ?>" style="padding:0;color:black;margin-top:2px;font-size:100%" />
        </form>
        <hr size="1" noshade size=1 color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;" />
        <div id="content" style="padding:5px; margin:0px;width:90%;">
            <? echo $content_for_layout; ?>
        </div>
        <div style="border-top:1px solid #999;font-size:80%;background:#EEE;padding:2px">
            <br/>
            Язык:<a href="/lang/">Русский</a><br/>
            <a href="/terms"><?=__('Условия использования и политика конфиденциальности', true); ?></a><br/>
            <a href=""><?= __('Полная версия'); ?></a>
            <br/>
        </div>
        <div style="border-top:1px solid #999;font-size:80%;background:#EEE;text-align:center">
            <br/>
            <b>Мобильная версия</b>
            <div dir="ltr">&copy;IT-DELUXE.LTD</div>
        </div>
    </body>
</html>