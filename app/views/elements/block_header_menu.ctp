<?php
$server='';
//Если вызывается c момощью метода external дописывать в начало ссылокConfigure::read('App.siteUrl')
$url=explode('?',$_SERVER['REQUEST_URI']);
if($url[0]=='/blocks/external')$server=Configure::read('App.siteUrl');



$links = array();
/*
$links['/'] = 'Главная';
$links[Configure::read('App.forumPath')] = 'Форум';
$links['/media'] = 'Видео';
$links['http://rumedia.ws/'] = 'RuMedia';
$links['http://mygame.ws'] = '�?гры';
$links['/gallery'] = 'Картинки';
$links['http://rumedia.ws/category/music/'] = 'Музыка';
$links['http://animebar.org'] = 'Аниме';
$links['http://fx.nsk54.com/'] = 'Обменник1';

//*/
	$menuItems = array(
		//'/'			=> __("Homepage", true),
		'/media'	=> __("Video", true),
		'/people'	=> __("People", true)/*,
                '/copyrightholders'	=> __("Copyrightholders", true),*/

	);
	if (Configure::read('Config.language') == _RUS_)
	{
		//$menuItems[__("root_forum_link", true)]	= __("Forum", true);
		$menuItems['/forum/index.php']	= __("Forum", true);//НА ВРЕМЯ АКЦИИ
		$menuItems['/pages/faq']	= 'FAQ';
		$menuItems['/news']	= __("Projects", true);
		//$menuItems['/basket']		= __("Downloads", true);
		if (!empty($curLottery))
		{
			//$menuItems['/users/lottery']	= $curLottery['Lottery']['hd'];
			$menuItems['/users/lottery']	= 'Внимание Конкурс!';
		}
	}
	else
	{
		$menuItems[__("root_forum_link", true)]	= __("Forum", true);
	}
	//$menuItems['/news']	= 'Новости партнеров';
	//$menuItems['http://ctcmedia.ru/rus']	= '<div class="stslogo"><img src="http://ctcmedia.ru/upload/chanels/ctc.png" /></div>';
	//$menuItems['http://videomore.ru']	= '<div class="stslogo"><img src="http://videomore.ru/images/sts.png" /></div>';
	//$menuItems['http://videomore.ru']	= '<img src="http://videomore.ru/images/sts_footer.png" />';
	//$menuItems['http://videomore.ru']	= '<img src="http://videomore.ru/images/sts_media.png" />';
	$links = $menuItems;

?>
<ul class="mainMenu">
<?php
foreach($links as $key => $name)
{
    $class = $strong = '';
	if (eregi('^' . $key . '$', $here))
    {
        $class = ' class="active"';
        $strong = '<strong>';
    }
    if(substr($key,0,1)=='/')
    	$key=$server.$key;
    printf('<li%s>%s<a href="%s">%s</a>%s</li>', $class, $strong,$key, $name, str_replace('<s', '</s', $strong));
    if (strpos($key, 'lottery'))
    {
/*
    	echo'<li>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" style="display:block;padding-top:12px;" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="64" height="30" id="stars-banner" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="stars-banner.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="bgcolor" value="#ffffff" /><embed src="/img/stars-banner.swf" quality="high" wmode="transparent" bgcolor="#ffffff" width="64" height="30" name="stars-banner" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
		</li>';
*/
    }
    //echo "\n";
    //echo '<li' . $class . '><a href="' . $key . '">' . $name . '</a></li>';
}
?>
</ul>
