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
/*
echo '<h2>omon<br />' .  md5(md5('msNSekAs') . '%`X') . '<br />dc3e83c840730bd9793aecd850b50832</h2>';
echo '<h2>polar<br />' . md5(md5('Zz9BXzGz') . '%`X') . '<br />0675758d44bd74001cff4e069dfa8d97</h2>';
*/
if (!empty($search_words))
{
   foreach ($search_words as $searchWord)
   {
       echo '<h3>' . __('Perhaps what you are looking for is', true) . ' ';
       echo '<a href="'. $searchWord['SearchWord']['url'] . '">' . __('here', true) . '</a>';
       echo '</h3><br>';
   }
}

if ((count($films) == 0) || (!empty($wsmediaPostCount)) || (!empty($animebarPostCount)))
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

if (!empty($films))
	foreach ($films as $row) {
    	extract($row);
?>
    <div class="moviePreviewWrapper">
<?php

if (($Film['is_license'] || $isWS) && (!empty($FilmVariant)))
{
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
	if (!empty($isBest))
	{
?>
			<div class="hd"><img src="/img/vusic/<?=$title?>.gif" alt="<?=$title?>" title="<?=$title?>" /></div>
            
<?php
	}
    $Film['site_id'] = 1;
    if (!empty($Film['site_id'])) // вывод логотипа сайта с которого пришел фильм (анимебар или румедиа)
    {
        ?>
    <div class="logo"><img src="/img/vusic/<?=$side_id?>.png" alt="<?=$side_id?>" title="<?=$side_id?>" /></div>
<?php    
    }
}
?>
        <div class="poster">
        <a href="/media/view/<?= $Film['id']?>">
            <?
            if (!empty($FilmPicture[0]['file_name']))
                echo $html->image($imgPath . $FilmPicture[array_rand($FilmPicture)]['file_name'], array('width' => 80));
            else
                echo $html->image('/img/vusic/noposter.jpg', array('width' => 80)); ?>
         </a>
            <div class="ratings rated_<?= round($MediaRating['rating']) ?>"><div></div></div>
            <?php
            if ($Film['imdb_rating'] != 0)
                echo '<span class="imdb">IMDb: ' . $Film['imdb_rating'] . '</span>';
            ?>
        </div>
        <p class="text">
            <?php
$directors = array();
$actors = array();
foreach ($Person as $data)
{
    if ($data['FilmsPerson']['profession_id'] == 1 && count($directors) < 4)
    {
    	if ($lang == _ENG_)
    	{
    		if (!empty($data['name' . $langFix]))
        		$directors[] = $data['name' . $langFix];
    	}
        else
        	$directors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
    }
    if (($data['FilmsPerson']['profession_id'] == 3
        || $data['FilmsPerson']['profession_id'] == 4)
        && count($actors) < 4)
    {
    	if ($lang == _ENG_)
    	{
    		if (!empty($data['name' . $langFix]))
        		$actors[] = $data['name' . $langFix];
    	}
    	else
        	$actors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
    }
}
if (!empty($directors))
	echo implode(', ', $directors) . '.';
?> <?php echo $Film['year'] ?>
            <span>«<a href="/media/view/<?= $Film['id']?>"><?= $Film['title' . $langFix] ?></a>»</span>
             <?php
shuffle($actors);
$actors=array_slice($actors,0,3);
echo implode(', ', $actors);

?>
            <em><?php
            	if ($lang == _ENG_)
            		echo $app->implodeWithParams(' / ', $Genre, 'title_imdb', ' ', 2);
            	else
            		echo $app->implodeWithParams(' / ', $Genre, 'title', ' ', 2);
            ?></em>
        </p>
    </div>
<?php
}
?>
</div>
<div class="pages">
<?php

//var_export($paginator);
//echo $this->element('paging'); ?>
</div>
<?php
//*
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
