<div id="content">
<?php
        $zone = false;
        $zones = Configure::read('Catalog.allowedIPs');
        $zone = checkAllowedMasks($zones,$_SERVER['REMOTE_ADDR'], 1);
        if($zone)$imgPath = Configure::read('Catalog.imgPath');
        else $imgPath = Configure::read('Catalog.imgPathInet');

$pass = $this->passedArgs;
$pass['action'] = str_replace(Configure::read('Routing.admin') . '_', '', $this->action); // temp
//$paginator->options(array('url' => $pass));
//pr($pass);
?>
<div class="movies">
<?php
$prim = '';
if ($crossSearch)
{
	$prim = '* - ' . __('Search also uses data from all the local resources of your ISP', true);
}

if (!empty($search_words))
{
   foreach ($search_words as $searchWord)
   {
       echo '<h3>' . __('Perhaps what you are looking for is', true) . ' ';
       echo '<a href="'. $searchWord['SearchWord']['url'] . '">' . __('here', true) . '</a>';
       echo '</h3><br>';
   }
}

/*
if ((count($s_films) == 0) || (!empty($wsmediaPostCount)) || (!empty($animebarPostCount)))
{
	if ((count($films) == 0) && (!isset($pass["page"])) && (!empty($wsmediaPostCount) || !empty($animebarPostCount)))
		echo '<h2>' . __('No results for your search', true) . ' :(</h2>';

	if ((count($films) > 0) || (!empty($wsmediaPostCount)) || (!empty($animebarPostCount)))
	{
		$cLinks = array();
		if (!empty($films))
		{
			//$cLinks[] = "<a href=\"http://nsk54.com/media/index/search:{$search}\">nsk54.com " . count($films) . " " . __('matches', true) . "</a>";
		}
		if (!empty($wsmediaPostCount))
		{
			echo '
			<form method="post" name=wsmform action="http://rumedia.ws">
				<input type="hidden" name="do" value="search" />
				<input type="hidden" name="subaction" value="search" />
				<input type="hidden" name="nsk54" value="search" />
				<input type="hidden" name="story" value="' . $search . '" />
			</form>
			';
			$cLinks[] = "<a href=\"http://rumedia.ws\" onclick=\"document.wsmform.submit(); return false;\">RuMedia $wsmediaPostCount " . __('matches', true) . "</a>";
		}
		if (!empty($animebarPostCount))
		{
			echo '
			<form method="post" name=abform action="http://animebar.org">
				<input type="hidden" name="do" value="search" />
				<input type="hidden" name="subaction" value="search" />
				<input type="hidden" name="nsk54" value="search" />
				<input type="hidden" name="story" value="' . $search . '" />
			</form>
			';
			$cLinks[] = "<a href=\"http://animebar.org\" onclick=\"document.abform.submit(); return false;\">AnimeBar $animebarPostCount " . __('matches', true) . "</a>";
		}
		echo "<h2>" . __('Find in catalogs', true) . " (" . implode(', ', $cLinks) . ")</h2>";
	}
}
*/
//pr($s_films);
if (!empty($s_films))
	foreach ($s_films as $row) {
    	extract($row);
?>
    <div class="moviePreviewWrapper">
<?php
//создадим ссылку на фильм
switch ($CS_Film['site_id']){
    case 1:
        $CS_Film['url'] = 'http://rumedia.ws/' . $CS_Film['id_original'].'-'.$CS_Film['slug'].'.html';
        break;
    case 2:
        $CS_Film['url'] = 'http://animebar.org/' . $CS_Film['id_original'].'-'.$CS_Film['slug'].'.html';
        break;
    case 3:
        $CS_Film['url'] = '/media/view/' . $CS_Film['id_original'].'-'.$CS_Film['slug'];
        break;
    default:
        break;
}




if (($CS_Film['is_license']))
{
/*    
    $best = array(//ИСКУССТВЕННО ВЫСТАВЛЯЕМ СОРТИРОВКУ КАЧЕСТВА
    'CamRip'	=> 20,
    'DVDrip'	=> 30,
    'DVDScr'	=> 25,
    'SATrip'	=> 10,
    'Telecine'	=> 15,
    'VHSrip'	=> 15,
    'Telesync'	=> 5,
    'DVDrip'	=> 30,
    'TVrip'		=> 10,
    '270p'		=> 5,
    );
	$isBest = 0;
	foreach ($FilmVariant as $variant)
	{
		if($variant['VideoType']['title'] <> 'web')
		{
			if (!empty($best[$variant['VideoType']['title']]) && ($best[$variant['VideoType']['title']] > $isBest))
			{
				$isBest = $best[$variant['VideoType']['title']];
				$title = $variant['VideoType']['title'];
			}
		}
	}
        
	if (!empty($isBest) && empty($Film['site_id']))
	{
?>
			<div class="hd"><img src="/img/vusic/<?=$title?>.gif" alt="<?=$title?>" title="<?=$title?>" /></div>

<?php
	}
*/        
}

    if (!empty($CS_Film['site_id']) && $CS_Film['site_id']!=3) // вывод логотипа сайта с которого пришел фильм (анимебар или румедиа)
    {
    	$sites = array(1 => array('sitename' => 'rumedia'), 2 => array('sitename' => 'animebar'), 3 => array('sitename' => 'videoxq'));
    	$siteName = $sites[$CS_Film['site_id']]['sitename'];
    	echo '<div class="logo"><img src="/img/vusic/' . $siteName . '.png" alt="' . $siteName . '" title="' . $siteName . '" /></div>';
    }

?>
        <div class="poster">
<?php
	if ($CS_Film['site_id']==3)
	{
?>
        <a href="<?= $CS_Film['url']?>">
            <?
            if (!empty($CS_Film['poster']))
                echo $html->image($CS_Film['poster'], array('width' => 80));
            else
                echo $html->image('/img/vusic/noposter.jpg', array('width' => 80)); ?>
         </a>
            <div class="ratings rated_<?= round($CS_Film['media_rating']) ?>"><div></div></div>
<?php
            
            if ($CS_Film['imdb_rating'] != 0)
                echo '<span class="imdb">IMDb: ' . $CS_Film['imdb_rating'] . '</span>';
            
	}
	else
	{
		//ВЫВОД ПОСТЕРА ВНЕШНЕГО САЙТА
		if (empty($CS_Film['poster']))
		{
			$posterSrc = '/img/vusic/noposter.jpg';
		}
		else
		{
			$posterSrc = $CS_Film["poster"];
		}

		echo '<a href="' . $CS_Film['id_original'] . '-' . $CS_Film['slug']. '"><img src="' . $posterSrc . '" width="80" /></a>';
	}
?>
        </div>
        <p class="text">
            <?php
	if ($CS_Film['site_id'])
	{

$directors = array();
$actors = array();

if (!empty($CS_Film['directors'])){
    echo $CS_Film['directors'] . '.';
}
	
?> <?php echo empty($CS_Film['year'])? '': $CS_Film['year'];
        //echo '<span>«<a href="/media/view/'. $CS_Film['id_original'].'-'. $CS_Film['slug'] .'"><'. $CS_Film['title' . $langFix].'></a>»</span>';
        echo '<span>«<a href="' . $CS_Film['url'] . '">' . $CS_Film['title' . $langFix] . '</a>»</span>';            
        ?>
             <?php
$actors = explode(', ',$CS_Film['actors']);             
shuffle($actors);
$actors=array_slice($actors,0,3);
echo implode(', ', $actors);

?>
            <em><?php
                if(!empty($CS_Film['genres'])){
                    echo $CS_Film['genres'];
                }
            ?></em>
<?php
	}

?>
        </p>
    </div>
<?php
}
?>
</div>
<div class="pages">
</div>
<?php
if (!empty($prim))
{
	echo $prim;
}


$pageNavigator->setMaxPage($pageCount);
$pageNavigator->setInterval(9);
$pageNavigator->setUrl('/media/index');
$pageNavigator->setArgs($pass);
$page = 1;
if (isset($pass["page"]))
	$page = $pass["page"];
echo '<h3>'.$pageNavigator->get($page) . '</h3>';
//*/
?>
</div>
