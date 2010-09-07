<?php
$server='';
//Если вызывается c момощью метода external дописывать в начало ссылокConfigure::read('App.siteUrl')
$url=explode('?',$_SERVER['REQUEST_URI']);
if($url[0]=='/blocks/external')$server=Configure::read('App.siteUrl');



$links = array();
//$links['/'] = 'Главная';
//$links['/news'] = 'Новости';
$links['/media'] = 'Видео';
$links[Configure::read('App.forumPath')] = 'Форум';
$links['/blogs'] = 'Блоги';
$links['/pages/nashi-partneryi'] = 'Наши партнеры';
$links['/pages/reklama'] = 'Реклама';
$links['/pages/faq'] = 'FAQ';


$highlight = array();
$highlight[Configure::read('App.forumPath')] = '#'.Configure::read('App.forumPath').'#';
$highlight['/pages/igrovyie-serveryi-1'] = '#/pages/igrovyie-#';
$highlight['/media'] = '#/media|/people|/basket#';
$highlight['/blogs'] = '#/blogs|/posts|/comments#';
$highlight['/gallery'] = '#/gallery#';
//$highlight['/news'] = '#/news#';

$highlight['/pages/veschanie-video'] = '#/pages/veschanie|/programma#';
//$highlight['/'] = '#/#';

if(isset($_REQUEST['highlight'])&&trim($_REQUEST['highlight'])!='')
{
$this->here=$_REQUEST['highlight'];
$highlight[$_REQUEST['highlight']]="#".$_REQUEST['highlight']."#";
}

?>
<ul class="mainMenu">
<?php
foreach($links as $key => $name)
{
    $class = $strong = '';
    if (isset($highlight[$key]) &&
        preg_match($highlight[$key], $this->here))
    {
        $class = ' class="active"';
        $strong = '<strong>';
    }
    if(substr($key,0,1)=='/')$key=$server.$key;
    printf('<li%s>%s<a href="%s">%s</a>%s</li>', $class, $strong,$key, $name, str_replace('<s', '</s', $strong));
    echo "\n";
    //echo '<li' . $class . '><a href="' . $key . '">' . $name . '</a></li>';
}
?>
</ul>