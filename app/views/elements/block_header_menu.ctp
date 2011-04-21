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
		'/'			=> __("Homepage", true),
		'/media'	=> __("Video", true),
		'/people'	=> __("People", true),
	);
	if (Configure::read('Config.language') == _RUS_)
	{
		$menuItems[__("root_forum_link", true)]	= __("Forum", true);
		$menuItems['/pages/faq']	= 'FAQ';
		//$menuItems['/basket']		= __("Downloads", true);
	}
	else
	{
		$menuItems[__("root_forum_link", true)]	= __("Forum", true);
	}
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
    //echo "\n";
    //echo '<li' . $class . '><a href="' . $key . '">' . $name . '</a></li>';
}
?>
</ul>