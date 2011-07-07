<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo Configure::read('App.siteName') . ' - ' . $title_for_layout; ?></title>
        <meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
        <?=$html->css('iphone/mobile');?>
        <?=$html->css('iphone/iblack');?>
        <link rel="icon" type="image/png" href=""/>
        <link rel="apple-touch-icon" href="" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
    </head>
    <body>
        <div id="header">
            <img id="logo-icon" alt="" src="Logo"/>
            <a id="logo-title" href="/mobile/index"> VideoXQ </a>
        </div>
        <div class="bar" style="text-align: center;">
            <form action="/mobile/search" id="searchform" method="get">
                <input type="text" placeholder="Search film by title…" tabindex="1" id="search-input" name="s"/>
                <input type="hidden" tabindex="2" id="search-submit-hidden" name="submit"/>
            </form>
        </div>
       <div class="bar">
            <a href="/mobile/films" class="button"><?= __('Films', true); ?></a>
            <a href="/mobile/news" class="button"><?= __('News', true); ?></a>
            <a href="/mobile/profile" class="button"><?= __('Profile', true); ?></a>
        </div>
        <ul id="home" title="VideoXQ" selected="true">

            <?= $content_for_layout; ?>
        </ul>
    </body>
</html>
